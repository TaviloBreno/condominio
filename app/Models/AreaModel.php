<?php

namespace App\Models;

use App\Entities\Area;
use CodeIgniter\Model;

class AreaModel extends Model
{
    protected $table            = 'areas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Area::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'descricao',
        'capacidade',
        'horario_inicio',
        'horario_fim',
        'taxa_reserva',
        'ativo',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'             => 'permit_empty|is_natural_no_zero',
        'nome'           => 'required|min_length[3]|max_length[100]',
        'descricao'      => 'permit_empty|max_length[1000]',
        'capacidade'     => 'required|is_natural_no_zero',
        'horario_inicio' => 'required',
        'horario_fim'    => 'required',
        'taxa_reserva'   => 'permit_empty|numeric|greater_than_equal_to[0]',
        'ativo'          => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome da área comum é obrigatório.',
            'min_length' => 'O nome deve conter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode exceder 100 caracteres.',
        ],
        'capacidade' => [
            'required'           => 'A capacidade máxima de pessoas é obrigatória.',
            'is_natural_no_zero' => 'A capacidade deve ser um número inteiro maior que zero.',
        ],
        'horario_inicio' => [
            'required' => 'O horário inicial de funcionamento é obrigatório.',
        ],
        'horario_fim' => [
            'required' => 'O horário limite de funcionamento é obrigatório.',
        ],
        'taxa_reserva' => [
            'numeric'                => 'O valor da taxa de reserva deve ser numérico.',
            'greater_than_equal_to' => 'A taxa não pode ser um valor negativo.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Retorna todas as áreas ativas para reservas
     *
     * @return array<Area>
     */
    public function listarDisponiveis(): array
    {
        return $this->where('ativo', 1)
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }

    /**
     * Monta consulta de áreas com suporte a busca e filtros
     */
    public function filtrarAreas(?string $busca = null, ?int $ativo = null): self
    {
        if (! empty($busca)) {
            $this->groupStart()
                 ->like('nome', $busca)
                 ->orLike('descricao', $busca)
                 ->groupEnd();
        }

        if ($ativo !== null && $ativo !== '') {
            $this->where('ativo', (int) $ativo);
        }

        return $this->orderBy('nome', 'ASC');
    }
}
