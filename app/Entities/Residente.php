<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Residente extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'              => 'integer',
        'user_id'         => '?integer',
        'ativo'           => 'boolean',
        'user_active'     => '?boolean',
        'primeiro_acesso' => '?boolean',
    ];

    /**
     * Retorna o status textual do residente.
     */
    public function getStatusTexto(): string
    {
        return ! empty($this->attributes['ativo']) ? 'Ativo' : 'Bloqueado';
    }

    /**
     * Retorna a badge HTML com cor do status para o layout Soft UI.
     */
    public function getStatusBadge(): string
    {
        if (! empty($this->attributes['ativo'])) {
            return '<span class="badge badge-sm bg-gradient-success">Ativo</span>';
        }

        return '<span class="badge badge-sm bg-gradient-danger">Bloqueado</span>';
    }

    /**
     * Retorna o CPF formatado (000.000.000-00).
     */
    public function getCpfFormatado(): string
    {
        $cpf = preg_replace('/\D/', '', (string) ($this->attributes['cpf'] ?? ''));
        if (strlen($cpf) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }

        return $this->attributes['cpf'] ?? '';
    }

    /**
     * Retorna telefone formatado.
     */
    public function getTelefoneFormatado(): string
    {
        $tel = preg_replace('/\D/', '', (string) ($this->attributes['telefone'] ?? ''));
        if (strlen($tel) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $tel);
        }
        if (strlen($tel) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);
        }

        return ! empty($this->attributes['telefone']) ? $this->attributes['telefone'] : '-';
    }

    /**
     * Retorna a identificação completa da unidade com Bloco e Torre quando houver.
     */
    public function getUnidadeCompleta(): string
    {
        $partes = [];
        if (! empty($this->attributes['bloco'])) {
            $partes[] = 'Bloco ' . $this->attributes['bloco'];
        }
        if (! empty($this->attributes['torre'])) {
            $partes[] = 'Torre ' . $this->attributes['torre'];
        }
        $partes[] = 'Apt/Unidade ' . ($this->attributes['unidade'] ?? '-');

        return implode(' - ', $partes);
    }
}
