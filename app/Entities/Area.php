<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Area extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'           => 'integer',
        'capacidade'   => 'integer',
        'taxa_reserva' => 'float',
        'ativo'        => 'boolean',
    ];

    /**
     * Retorna o status formatado em badge HTML para o layout
     */
    public function getStatusBadge(): string
    {
        if (! empty($this->attributes['ativo'])) {
            return '<span class="badge badge-sm bg-gradient-success">Disponível</span>';
        }

        return '<span class="badge badge-sm bg-gradient-secondary">Indisponível</span>';
    }

    /**
     * Retorna a taxa formatada em moeda brasileira
     */
    public function getTaxaFormatada(): string
    {
        $taxa = (float) ($this->attributes['taxa_reserva'] ?? 0);
        if ($taxa <= 0) {
            return 'Gratuito';
        }

        return 'R$ ' . number_format($taxa, 2, ',', '.');
    }

    /**
     * Retorna o horário de funcionamento formatado (HH:MM às HH:MM)
     */
    public function getHorarioFuncionamento(): string
    {
        $inicio = substr((string) ($this->attributes['horario_inicio'] ?? '08:00'), 0, 5);
        $fim    = substr((string) ($this->attributes['horario_fim'] ?? '22:00'), 0, 5);

        return "{$inicio} às {$fim}";
    }

    /**
     * Retorna a capacidade formatada
     */
    public function getCapacidadeFormatada(): string
    {
        $cap = (int) ($this->attributes['capacidade'] ?? 0);
        return $cap > 0 ? "{$cap} pessoas" : 'Livre';
    }
}
