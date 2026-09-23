<?php

namespace App\Models;

use App\Entities\Notificacao;
use CodeIgniter\Model;

class NotificacaoModel extends Model
{
    protected $table            = 'notificacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Notificacao::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tipo',
        'titulo',
        'mensagem',
        'destinatario_email',
        'reserva_id',
        'lida',
        'enviada_email',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'                 => 'permit_empty|is_natural_no_zero',
        'tipo'               => 'required|max_length[50]',
        'titulo'             => 'required|min_length[3]|max_length[150]',
        'mensagem'           => 'required',
        'destinatario_email' => 'permit_empty|valid_email|max_length[255]',
        'reserva_id'         => 'permit_empty|is_natural_no_zero',
        'lida'               => 'permit_empty|in_list[0,1]',
        'enviada_email'      => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Retorna a lista de notificações pendentes / não lidas
     *
     * @return array<Notificacao>
     */
    public function listarNaoLidas(int $limit = 10): array
    {
        return $this->where('lida', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Contabiliza o total de notificações não lidas para o badge da barra de navegação
     */
    public function contarNaoLidas(): int
    {
        return $this->where('lida', 0)->countAllResults();
    }

    /**
     * Marca uma notificação individual como lida
     */
    public function marcarComoLida(int $id): bool
    {
        return (bool) $this->update($id, ['lida' => 1]);
    }

    /**
     * Marca todas as notificações pendentes como lidas
     */
    public function marcarTodasComoLidas(): bool
    {
        return (bool) $this->where('lida', 0)
                           ->set(['lida' => 1])
                           ->update();
    }

    /**
     * Registra uma notificação no banco de dados
     */
    public function registrarNotificacao(
        string $tipo,
        string $titulo,
        string $mensagem,
        ?int $reservaId = null,
        ?string $email = null
    ): int|false {
        $dados = [
            'tipo'               => $tipo,
            'titulo'             => $titulo,
            'mensagem'           => $mensagem,
            'reserva_id'         => $reservaId,
            'destinatario_email' => $email,
            'lida'               => 0,
            'enviada_email'      => 0,
        ];

        $id = $this->insert($dados, true);

        return $id ? (int) $id : false;
    }
}
