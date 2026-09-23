<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Cobranca extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'data_pagamento',
    ];

    protected $casts = [
        'id'           => 'integer',
        'reserva_id'   => '?integer',
        'residente_id' => 'integer',
        'valor'        => 'float',
    ];

    /**
     * Retorna badge HTML estilizado com o status de pagamento
     */
    public function getStatusBadge(): string
    {
        $status = $this->attributes['status'] ?? 'pendente';

        // Checagem se venceu e continua pendente
        if ($status === 'pendente' && ! empty($this->attributes['data_vencimento']) && strtotime($this->attributes['data_vencimento']) < strtotime(date('Y-m-d'))) {
            return '<span class="badge badge-sm bg-gradient-danger">Vencido</span>';
        }

        return match ($status) {
            'pago'      => '<span class="badge badge-sm bg-gradient-success">Pago</span>',
            'pendente'  => '<span class="badge badge-sm bg-gradient-warning">Pendente</span>',
            'cancelado' => '<span class="badge badge-sm bg-gradient-secondary">Cancelado</span>',
            'atrasado'  => '<span class="badge badge-sm bg-gradient-danger">Atrasado</span>',
            default     => '<span class="badge badge-sm bg-gradient-light text-dark">' . esc(ucfirst($status)) . '</span>',
        };
    }

    /**
     * Retorna o valor formatado em reais
     */
    public function getValorFormatado(): string
    {
        $valor = (float) ($this->attributes['valor'] ?? 0);

        if ($valor <= 0.00) {
            return 'R$ 0,00 (Isento)';
        }

        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    /**
     * Retorna data de vencimento no padrão dd/mm/aaaa
     */
    public function getDataVencimentoFormatada(): string
    {
        if (empty($this->attributes['data_vencimento'])) {
            return '-';
        }

        return date('d/m/Y', strtotime((string) $this->attributes['data_vencimento']));
    }

    /**
     * Retorna data de quitação com hora ou traço
     */
    public function getDataPagamentoFormatada(): string
    {
        if (empty($this->attributes['data_pagamento'])) {
            return '-';
        }

        return date('d/m/Y \à\s H:i', strtotime((string) $this->attributes['data_pagamento']));
    }

    /**
     * Verifica se a cobrança está apta a receber pagamento
     */
    public function podePagar(): bool
    {
        $status = $this->attributes['status'] ?? 'pendente';

        return in_array($status, ['pendente', 'atrasado'], true);
    }
}
