<?php

namespace App\Models;

use App\Entities\Reserva;
use CodeIgniter\Model;

class ReservaModel extends Model
{
    // =========================================================================
    // Item 5: Propriedades Base ($table, $allowedFields, $validationRules)
    // =========================================================================

    protected $table            = 'reservas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Reserva::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'area_id',
        'residente_id',
        'data_reserva',
        'horario_inicio',
        'horario_fim',
        'status',
        'valor_taxa',
        'observacoes',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Regras de validação nativas do CodeIgniter 4
    protected $validationRules = [
        'id'             => 'permit_empty|is_natural_no_zero',
        'area_id'        => 'required|is_natural_no_zero',
        'residente_id'   => 'required|is_natural_no_zero',
        'data_reserva'   => 'required|valid_date[Y-m-d]',
        'horario_inicio' => 'required',
        'horario_fim'    => 'required',
        'status'         => 'required|in_list[pendente,confirmada,cancelada]',
        'valor_taxa'     => 'permit_empty|numeric|greater_than_equal_to[0]',
        'observacoes'    => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'area_id' => [
            'required'           => 'A área comum a ser reservada é obrigatória.',
            'is_natural_no_zero' => 'Identificador da área inválido.',
        ],
        'residente_id' => [
            'required'           => 'O morador solicitante da reserva é obrigatório.',
            'is_natural_no_zero' => 'Identificador do residente inválido.',
        ],
        'data_reserva' => [
            'required'   => 'A data da reserva é obrigatória.',
            'valid_date' => 'A data informada deve estar no formato AAAA-MM-DD.',
        ],
        'horario_inicio' => [
            'required' => 'O horário de início da reserva é obrigatório.',
        ],
        'horario_fim' => [
            'required' => 'O horário de término da reserva é obrigatório.',
        ],
        'status' => [
            'required' => 'O status da reserva é obrigatório.',
            'in_list'  => 'O status deve ser pendente, confirmada ou cancelada.',
        ],
        'valor_taxa' => [
            'numeric'               => 'A taxa da reserva deve ter um valor numérico válido.',
            'greater_than_equal_to' => 'A taxa de reserva não pode ser negativa.',
        ],
        'observacoes' => [
            'max_length' => 'As observações não podem ultrapassar 1000 caracteres.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // =========================================================================
    // Item 6: Métodos de Consulta
    // =========================================================================

    /**
     * Verifica se há conflito de horário/data para a mesma área comum.
     * Retorna true se houver sobreposição com outra reserva ativa/confirmada.
     */
    public function temConflito(
        int $areaId,
        string $dataReserva,
        string $horarioInicio,
        string $horarioFim,
        ?int $reservaIdIgnorar = null
    ): bool {
        $builder = $this->where('area_id', $areaId)
                        ->where('data_reserva', $dataReserva)
                        ->where('status !=', 'cancelada')
                        ->where('horario_inicio <', $horarioFim)
                        ->where('horario_fim >', $horarioInicio);

        if ($reservaIdIgnorar !== null) {
            $builder->where('id !=', $reservaIdIgnorar);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Lista reservas ativas de uma determinada área comum (para calendário/timeline)
     *
     * @return array<Reserva>
     */
    public function listarPorArea(int $areaId, ?string $dataInicio = null, ?string $dataFim = null): array
    {
        $builder = $this->select('reservas.*, residentes.nome as residente_nome, residentes.unidade, residentes.bloco, areas.nome as area_nome')
                        ->join('residentes', 'residentes.id = reservas.residente_id', 'inner')
                        ->join('areas', 'areas.id = reservas.area_id', 'inner')
                        ->where('reservas.area_id', $areaId)
                        ->where('reservas.status !=', 'cancelada');

        if ($dataInicio !== null) {
            $builder->where('reservas.data_reserva >=', $dataInicio);
        }

        if ($dataFim !== null) {
            $builder->where('reservas.data_reserva <=', $dataFim);
        }

        return $builder->orderBy('reservas.data_reserva', 'ASC')
                       ->orderBy('reservas.horario_inicio', 'ASC')
                       ->findAll();
    }

    /**
     * Lista reservas efetuadas por um morador específico
     *
     * @return array<Reserva>
     */
    public function listarPorResidente(int $residenteId): array
    {
        return $this->select('reservas.*, areas.nome as area_nome, areas.taxa_reserva as area_taxa, areas.capacidade as area_capacidade')
                    ->join('areas', 'areas.id = reservas.area_id', 'inner')
                    ->where('reservas.residente_id', $residenteId)
                    ->orderBy('reservas.data_reserva', 'DESC')
                    ->orderBy('reservas.horario_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Prepara consulta com joins para listagem geral de reservas no painel administrativo
     */
    public function filtrarReservas(?string $busca = null, ?string $status = null, ?string $data = null): self
    {
        $this->select('reservas.*, areas.nome as area_nome, residentes.nome as residente_nome, residentes.unidade, residentes.bloco, residentes.telefone, residentes.email')
             ->join('areas', 'areas.id = reservas.area_id', 'inner')
             ->join('residentes', 'residentes.id = reservas.residente_id', 'inner');

        if (! empty($status)) {
            $this->where('reservas.status', $status);
        }

        if (! empty($data)) {
            $this->where('reservas.data_reserva', $data);
        }

        if (! empty($busca)) {
            $this->groupStart()
                 ->like('areas.nome', $busca)
                 ->orLike('residentes.nome', $busca)
                 ->orLike('residentes.unidade', $busca)
                 ->groupEnd();
        }

        return $this->orderBy('reservas.data_reserva', 'DESC')
                    ->orderBy('reservas.horario_inicio', 'DESC');
    }

    /**
     * Localiza uma reserva específica com todos os dados da área e do morador
     */
    public function buscarDetalhada(int $id): ?Reserva
    {
        return $this->select('reservas.*, areas.nome as area_nome, areas.descricao as area_descricao, areas.capacidade as area_capacidade, areas.horario_inicio as area_horario_inicio, areas.horario_fim as area_horario_fim, residentes.nome as residente_nome, residentes.unidade, residentes.bloco, residentes.telefone, residentes.email')
                    ->join('areas', 'areas.id = reservas.area_id', 'inner')
                    ->join('residentes', 'residentes.id = reservas.residente_id', 'inner')
                    ->where('reservas.id', $id)
                    ->first();
    }
}
