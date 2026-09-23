<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Notificacao extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'            => 'integer',
        'reserva_id'    => '?integer',
        'lida'          => 'boolean',
        'enviada_email' => 'boolean',
    ];

    /**
     * Retorna a classe de ícone FontAwesome correspondente ao tipo de notificação
     */
    public function getIcone(): string
    {
        return match ($this->attributes['tipo'] ?? '') {
            'nova_reserva'         => 'fas fa-calendar-plus text-primary',
            'cancelamento_reserva' => 'fas fa-calendar-times text-danger',
            'cobranca'             => 'fas fa-receipt text-warning',
            default                => 'fas fa-bell text-info',
        };
    }

    /**
     * Retorna a data e hora formatadas para exibição
     */
    public function getDataFormatada(): string
    {
        if (empty($this->attributes['created_at'])) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime((string) $this->attributes['created_at']));
    }

    /**
     * Retorna tempo relativo amigável (ex.: há 5 minutos)
     */
    public function getTempoRelativo(): string
    {
        if (empty($this->attributes['created_at'])) {
            return '';
        }

        $tempo = time() - strtotime((string) $this->attributes['created_at']);

        if ($tempo < 60) {
            return 'agora mesmo';
        }
        if ($tempo < 3600) {
            $min = (int) ($tempo / 60);
            return "há {$min} min";
        }
        if ($tempo < 86400) {
            $horas = (int) ($tempo / 3600);
            return "há {$horas} h";
        }

        $dias = (int) ($tempo / 86400);
        return "há {$dias} d";
    }

    /**
     * Verifica se a notificação foi lida
     */
    public function foiLida(): bool
    {
        return (bool) ($this->attributes['lida'] ?? false);
    }
}
