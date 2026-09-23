<?php

namespace App\Models;

use App\Entities\Residente;
use CodeIgniter\Model;

class ResidenteModel extends Model
{
    protected $table            = 'residentes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Residente::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nome',
        'cpf',
        'telefone',
        'email',
        'unidade',
        'bloco',
        'torre',
        'ativo',
    ];

    // Dates & Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'user_id'  => 'permit_empty|is_natural_no_zero',
        'nome'     => 'required|min_length[3]|max_length[150]',
        'cpf'      => 'required|min_length[11]|max_length[14]|is_unique[residentes.cpf,id,{id}]',
        'telefone' => 'permit_empty|min_length[8]|max_length[20]',
        'email'    => 'required|valid_email|max_length[255]|is_unique[residentes.email,id,{id}]',
        'unidade'  => 'required|max_length[20]',
        'bloco'    => 'permit_empty|max_length[20]',
        'torre'    => 'permit_empty|max_length[20]',
        'ativo'    => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O campo Nome Completo é obrigatório.',
            'min_length' => 'O Nome deve conter pelo menos 3 caracteres.',
            'max_length' => 'O Nome não pode exceder 150 caracteres.',
        ],
        'cpf' => [
            'required'   => 'O campo CPF é obrigatório.',
            'min_length' => 'O CPF informado é inválido.',
            'max_length' => 'O CPF informado é inválido.',
            'is_unique'  => 'Este CPF já está cadastrado para outro residente.',
        ],
        'telefone' => [
            'min_length' => 'O telefone informado é muito curto.',
            'max_length' => 'O telefone não pode exceder 20 caracteres.',
        ],
        'email' => [
            'required'    => 'O campo E-mail é obrigatório.',
            'valid_email' => 'Por favor, informe um endereço de e-mail válido.',
            'max_length'  => 'O e-mail não pode exceder 255 caracteres.',
            'is_unique'   => 'Este e-mail já está cadastrado para outro residente.',
        ],
        'unidade' => [
            'required'   => 'A Unidade / Apartamento é obrigatória.',
            'max_length' => 'A unidade não pode exceder 20 caracteres.',
        ],
        'bloco' => [
            'max_length' => 'O bloco não pode exceder 20 caracteres.',
        ],
        'torre' => [
            'max_length' => 'A torre não pode exceder 20 caracteres.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['limparFormatacoes'];
    protected $beforeUpdate   = ['limparFormatacoes'];

    /**
     * Limpa pontuações de CPF e telefone antes de persistir
     */
    protected function limparFormatacoes(array $data): array
    {
        if (isset($data['data']['cpf'])) {
            $data['data']['cpf'] = preg_replace('/\D/', '', (string) $data['data']['cpf']);
        }

        if (isset($data['data']['telefone']) && $data['data']['telefone'] !== null) {
            $data['data']['telefone'] = preg_replace('/\D/', '', (string) $data['data']['telefone']);
        }

        return $data;
    }

    /*
     * --------------------------------------------------------------------
     * Métodos de Consulta (Item 4)
     * --------------------------------------------------------------------
     */

    /**
     * Configura consulta com JOIN na tabela users e filtros opcionais
     * Retorna o próprio Model preparado para paginação ou ->findAll()
     */
    public function listarComUsuario(?string $busca = null, ?string $bloco = null, ?string $unidade = null, ?int $ativo = null): self
    {
        $this->select('residentes.*, users.username, users.active as user_active, users.last_active, users.status as user_status, users.tipo as user_tipo, users.primeiro_acesso')
             ->join('users', 'users.id = residentes.user_id', 'left');

        if (! empty($busca)) {
            $buscaLimpa = preg_replace('/\D/', '', $busca);
            $this->groupStart()
                 ->like('residentes.nome', $busca)
                 ->orLike('residentes.email', $busca)
                 ->orLike('residentes.unidade', $busca);

            if (! empty($buscaLimpa)) {
                $this->orLike('residentes.cpf', $buscaLimpa);
            }
            $this->groupEnd();
        }

        if ($bloco !== null && $bloco !== '') {
            $this->where('residentes.bloco', $bloco);
        }

        if ($unidade !== null && $unidade !== '') {
            $this->where('residentes.unidade', $unidade);
        }

        if ($ativo !== null && $ativo !== '') {
            $this->where('residentes.ativo', (int) $ativo);
        }

        return $this->orderBy('residentes.nome', 'ASC');
    }

    /**
     * Busca um residente pelo ID com os dados do usuário associado
     */
    public function obterComUsuario(int $id): ?Residente
    {
        return $this->select('residentes.*, users.username, users.active as user_active, users.last_active, users.status as user_status, users.tipo as user_tipo, users.primeiro_acesso')
                    ->join('users', 'users.id = residentes.user_id', 'left')
                    ->where('residentes.id', $id)
                    ->first();
    }

    /**
     * Busca residentes por Unidade e Bloco
     *
     * @return array<Residente>
     */
    public function buscarPorUnidade(string $unidade, ?string $bloco = null): array
    {
        $this->where('unidade', $unidade);

        if ($bloco !== null && $bloco !== '') {
            $this->where('bloco', $bloco);
        }

        return $this->findAll();
    }

    /**
     * Busca um residente vinculado a um user_id do Shield
     */
    public function buscarPorUserId(int $userId): ?Residente
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * Busca residente por CPF (com ou sem pontuação)
     */
    public function buscarPorCpf(string $cpf): ?Residente
    {
        $cpfLimpo = preg_replace('/\D/', '', $cpf);
        return $this->where('cpf', $cpfLimpo)->first();
    }

    /**
     * Busca residente por e-mail
     */
    public function buscarPorEmail(string $email): ?Residente
    {
        return $this->where('email', $email)->first();
    }
}
