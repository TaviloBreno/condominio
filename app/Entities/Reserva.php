<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Reserva extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'           => 'integer',
        'area_id'      => 'integer',
        'residente_id' => 'integer',
        'valor_taxa'   => 'float',
    ];

    /**
     * Retorna badge HTML estilizado com o status da reserva
     */
    public function getStatusBadge(): string
    {
        return match ($this->attributes['status'] ?? 'pendente') {
            'confirmada' => '<span class="badge badge-sm bg-gradient-success">Confirmada</span>',
            'pendente'   => '<span class="badge badge-sm bg-gradient-warning">Pendente</span>',
            'cancelada'  => '<span class="badge badge-sm bg-gradient-secondary">Cancelada</span>',
            default      => '<span class="badge badge-sm bg-gradient-light text-dark">' . esc(ucfirst($this->attributes['status'] ?? '')) . '</span>',
        };
    }

    /**
     * Retorna a data no formato brasileiro dd/mm/aaaa
     */
    public function getDataFormatada(): string
    {
        if (empty($this->attributes['data_reserva'])) {
            return '-';
        }

        return date('d/m/Y', strtotime((string) $this->attributes['data_reserva']));
    }

    /**
     * Retorna intervalo de horário formatado (ex.: 09:00 às 13:00)
     */
    public function getHorarioFormatado(): string
    {
        $inicio = ! empty($this->attributes['horario_inicio']) ? substr((string) $this->attributes['horario_inicio'], 0, 5) : '00:00';
        $fim    = ! empty($this->attributes['horario_fim']) ? substr((string) $this->attributes['horario_fim'], 0, 5) : '00:00';

        return "{$inicio} às {$fim}";
    }

    /**
     * Retorna o valor da taxa da reserva formatado em reais ou 'Gratuito'
     */
    public function getTaxaFormatada(): string
    {
        $taxa = (float) ($this->attributes['valor_taxa'] ?? 0);

        if ($taxa <= 0.00) {
            return 'Gratuito';
        }

        return 'R$ ' . number_format($taxa, 2, ',', '.');
    }

    /**
     * Informa se a reserva pode ser cancelada
     */
    public function podeCancelar(): bool
    {
        if (($this->attributes['status'] ?? '') === 'cancelada') {
            return false;
        }

        $dataHoraReserva = strtotime(($this->attributes['data_reserva'] ?? '') . ' ' . ($this->attributes['horario_inicio'] ?? '00:00'));

        return $dataHoraReserva >= time();
    }
}
