<?php

namespace App\Models;

use App\Entities\Cobranca;
use App\Entities\Reserva;
use CodeIgniter\Model;

class CobrancaModel extends Model
{
    protected $table            = 'cobrancas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Cobranca::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reserva_id',
        'residente_id',
        'descricao',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'codigo_barras',
        'forma_pagamento',
        'observacoes',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Regras de validação nativas
    protected $validationRules = [
        'id'              => 'permit_empty|is_natural_no_zero',
        'reserva_id'      => 'permit_empty|is_natural_no_zero',
        'residente_id'    => 'required|is_natural_no_zero',
        'descricao'       => 'required|min_length[3]|max_length[255]',
        'valor'           => 'required|numeric|greater_than_equal_to[0]',
        'data_vencimento' => 'required|valid_date[Y-m-d]',
        'status'          => 'required|in_list[pendente,pago,cancelado,atrasado]',
    ];

    protected $validationMessages = [
        'residente_id' => [
            'required' => 'O residente titular da cobrança é obrigatório.',
        ],
        'descricao' => [
            'required' => 'A descrição da cobrança é obrigatória.',
        ],
        'valor' => [
            'required' => 'O valor da cobrança é obrigatório.',
            'numeric'  => 'O valor deve ser numérico.',
        ],
        'data_vencimento' => [
            'required'   => 'A data de vencimento é obrigatória.',
            'valid_date' => 'A data de vencimento deve estar no formato AAAA-MM-DD.',
        ],
    ];

    // =========================================================================
    // Item 14 & 15: Regras de Cálculo, Geração Automática e Status de Pagamento
    // =========================================================================

    /**
     * Gera e persiste automaticamente uma cobrança atrelada a uma reserva
     *
     * @param Reserva|int $reserva
     * @return Cobranca|null
     */
    public function gerarParaReserva($reserva): ?Cobranca
    {
        if (is_int($reserva)) {
            $reservaModel = new ReservaModel();
            $reserva = $reservaModel->buscarDetalhada($reserva);
        }

        if (! $reserva) {
            return null;
        }

        $valorTaxa = (float) ($reserva->valor_taxa ?? 0);

        // Se a reserva é gratuita (valor 0.00), não gera pendência financeira
        if ($valorTaxa <= 0.00) {
            return null;
        }

        // Verifica se já existe cobrança gerada para esta reserva
        $cobrancaExistente = $this->where('reserva_id', $reserva->id)->first();
        if ($cobrancaExistente) {
            return $cobrancaExistente;
        }

        // Regra de Vencimento: 3 dias após a solicitação ou no dia da reserva
        $vencimentoCalculado = date('Y-m-d', strtotime('+3 days'));
        if (strtotime($vencimentoCalculado) > strtotime((string) $reserva->data_reserva)) {
            $vencimentoCalculado = $reserva->data_reserva;
        }

        // Geração de código PIX sintético
        $pixHash = strtoupper(substr(md5('CONDO_' . $reserva->id . '_' . time()), 0, 32));
        $codigoPix = "00020126580014br.gov.bcb.pix0136" . $pixHash . "520400005303986540" . number_format($valorTaxa, 2, '.', '') . "5802BR5925CONDOMINIO RESIDENCIAL6009SAO PAULO62070503***6304";

        $dadosCobranca = [
            'reserva_id'      => $reserva->id,
            'residente_id'    => $reserva->residente_id,
            'descricao'       => "Taxa de Reserva: " . ($reserva->area_nome ?? 'Área Comum') . " (" . $reserva->getDataFormatada() . ")",
            'valor'           => $valorTaxa,
            'data_vencimento' => $vencimentoCalculado,
            'status'          => 'pendente',
            'codigo_barras'   => $codigoPix,
            'forma_pagamento' => 'PIX',
            'observacoes'     => "Gerada automaticamente na confirmação da Reserva #" . str_pad((string) $reserva->id, 4, '0', STR_PAD_LEFT),
        ];

        $id = $this->insert($dadosCobranca, true);

        return $id ? $this->find($id) : null;
    }

    /**
     * Registra a liquidação/pagamento de uma cobrança
     */
    public function registrarPagamento(int $cobrancaId, string $formaPagamento = 'PIX', ?string $obs = null): bool
    {
        $cobranca = $this->find($cobrancaId);

        if (! $cobranca) {
            $this->errors = ['cobranca' => 'Cobrança não encontrada.'];
            return false;
        }

        $atualizacao = [
            'status'          => 'pago',
            'data_pagamento'  => date('Y-m-d H:i:s'),
            'forma_pagamento' => $formaPagamento,
        ];

        if (! empty($obs)) {
            $novaObs = trim((string) $cobranca->observacoes . "\n" . "[Pago via {$formaPagamento} em " . date('d/m/Y H:i') . ": {$obs}]");
            $atualizacao['observacoes'] = $novaObs;
        }

        return (bool) $this->update($cobrancaId, $atualizacao);
    }

    /**
     * Cancela cobranças atreladas a uma reserva que foi desmarcada
     */
    public function cancelarPorReserva(int $reservaId): bool
    {
        return (bool) $this->where('reserva_id', $reservaId)
                           ->where('status', 'pendente')
                           ->set([
                               'status'      => 'cancelado',
                               'observacoes' => 'Cancelada devido ao cancelamento do agendamento da reserva.',
                           ])
                           ->update();
    }

    /**
     * Consulta cobranças com filtros e dados do residente
     */
    public function filtrarCobrancas(?string $busca = null, ?string $status = null): self
    {
        $this->select('cobrancas.*, residentes.nome as residente_nome, residentes.unidade, residentes.bloco, residentes.telefone, residentes.cpf, reservas.data_reserva, areas.nome as area_nome')
             ->join('residentes', 'residentes.id = cobrancas.residente_id', 'inner')
             ->join('reservas', 'reservas.id = cobrancas.reserva_id', 'left')
             ->join('areas', 'areas.id = reservas.area_id', 'left');

        if (! empty($status)) {
            $this->where('cobrancas.status', $status);
        }

        if (! empty($busca)) {
            $this->groupStart()
                 ->like('cobrancas.descricao', $busca)
                 ->orLike('residentes.nome', $busca)
                 ->orLike('residentes.unidade', $busca)
                 ->groupEnd();
        }

        return $this->orderBy('cobrancas.id', 'DESC');
    }

    /**
     * Localiza uma cobrança específica com detalhes completos
     */
    public function buscarDetalhada(int $id): ?Cobranca
    {
        return $this->select('cobrancas.*, residentes.nome as residente_nome, residentes.unidade, residentes.bloco, residentes.telefone, residentes.email, residentes.cpf, reservas.data_reserva, reservas.horario_inicio, reservas.horario_fim, areas.nome as area_nome')
                    ->join('residentes', 'residentes.id = cobrancas.residente_id', 'inner')
                    ->join('reservas', 'reservas.id = cobrancas.reserva_id', 'left')
                    ->join('areas', 'areas.id = reservas.area_id', 'left')
                    ->where('cobrancas.id', $id)
                    ->first();
    }
}
