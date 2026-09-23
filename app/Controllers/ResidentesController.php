<?php

namespace App\Controllers;

use App\Models\ResidenteModel;

class ResidentesController extends BaseController
{
    protected ResidenteModel $residenteModel;

    public function __construct()
    {
        $this->residenteModel = new ResidenteModel();
        helper(['form', 'url', 'text']);
    }

    /**
     * Listagem de residentes com busca, filtros, ordenação e paginação (Itens 6 e 7)
     */
    public function index(): string
    {
        $busca    = trim((string) $this->request->getGet('busca'));
        $bloco    = trim((string) $this->request->getGet('bloco'));
        $unidade  = trim((string) $this->request->getGet('unidade'));
        $ativo    = $this->request->getGet('ativo');
        $ordenar  = $this->request->getGet('ordenar') ?? 'nome';
        $direcao  = strtoupper($this->request->getGet('direcao') ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $porPagina = (int) ($this->request->getGet('por_pagina') ?? 10);
        if ($porPagina < 5 || $porPagina > 100) {
            $porPagina = 10;
        }

        // Colunas permitidas para ordenação
        $colunasValidas = ['nome', 'unidade', 'bloco', 'created_at', 'ativo'];
        if (! in_array($ordenar, $colunasValidas, true)) {
            $ordenar = 'nome';
        }

        $ativoFiltro = ($ativo !== null && $ativo !== '') ? (int) $ativo : null;

        // Monta consulta com JOIN do usuário e filtros
        $modelQuery = $this->residenteModel->listarComUsuario($busca, $bloco, $unidade, $ativoFiltro);
        $modelQuery->orderBy('residentes.' . $ordenar, $direcao);

        // Paginação nativa do CodeIgniter 4
        $residentes = $this->residenteModel->paginate($porPagina, 'residentes');
        $pager      = $this->residenteModel->pager;

        // Lista de blocos para filtro dinâmico
        $blocos = $this->residenteModel->builder()
            ->select('bloco')
            ->where('bloco IS NOT NULL')
            ->where('bloco !=', '')
            ->where('deleted_at IS NULL')
            ->groupBy('bloco')
            ->orderBy('bloco', 'ASC')
            ->get()
            ->getResultArray();

        $dados = [
            'title'      => 'Residentes do Condomínio',
            'residentes' => $residentes,
            'pager'      => $pager,
            'busca'      => $busca,
            'bloco'      => $bloco,
            'unidade'    => $unidade,
            'ativo'      => $ativo,
            'ordenar'    => $ordenar,
            'direcao'    => $direcao,
            'porPagina'  => $porPagina,
            'blocos'     => array_column($blocos, 'bloco'),
            'total'      => $pager->getTotal('residentes'),
        ];

        return view('Residentes/index', $dados);
    }

    /**
     * Exibe formulário de cadastro de novo residente (Item 13)
     */
    public function novo(): string
    {
        $dados = [
            'title'  => 'Novo Residente',
            'errors' => session('errors') ?? [],
        ];

        return view('Residentes/novo', $dados);
    }

    /**
     * Processa a criação de um novo residente e validações (Item 13)
     */
    public function criar()
    {
        $dados = [
            'nome'     => trim((string) $this->request->getPost('nome')),
            'cpf'      => preg_replace('/\D/', '', (string) $this->request->getPost('cpf')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'telefone' => preg_replace('/\D/', '', (string) $this->request->getPost('telefone')),
            'unidade'  => trim((string) $this->request->getPost('unidade')),
            'bloco'    => trim((string) $this->request->getPost('bloco')) ?: null,
            'torre'    => trim((string) $this->request->getPost('torre')) ?: null,
            'ativo'    => (int) ($this->request->getPost('ativo') ?? 1),
        ];

        // Validação e persistência via ResidenteModel
        if (! $this->residenteModel->save($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->residenteModel->errors())
                             ->with('erro', 'Por favor, corrija os erros apontados no formulário.');
        }

        $residenteId = (int) $this->residenteModel->getInsertID();

        // Se marcou para criar usuário automaticamente, avisa ou delega
        $criarUsuario = (bool) $this->request->getPost('criar_usuario');
        if ($criarUsuario) {
            return redirect()->to(route_to('residentes.detalhes', $residenteId))
                             ->with('sucesso', 'Residente cadastrado com sucesso! Prossiga com a criação do acesso Shield.');
        }

        return redirect()->to(route_to('residentes.detalhes', $residenteId))
                         ->with('sucesso', 'Residente cadastrado com sucesso!');
    }

    /**
     * Exibe a tela de detalhes completos do residente e usuário vinculado (Itens 8 e 9)
     */
    public function detalhes(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        // Busca outros residentes cadastrados na mesma unidade/bloco
        $coResidentesBuilder = $this->residenteModel->where('unidade', $residente->unidade)
                                                    ->where('id !=', $id);
        if (! empty($residente->bloco)) {
            $coResidentesBuilder->where('bloco', $residente->bloco);
        }
        $outrosMoradores = $coResidentesBuilder->findAll();

        $dados = [
            'title'           => 'Detalhes do Residente: ' . $residente->nome,
            'residente'       => $residente,
            'outrosMoradores' => $outrosMoradores,
        ];

        return view('Residentes/detalhes', $dados);
    }

    /**
     * Formulário de edição pré-preenchido do residente (Item 10)
     */
    public function editar(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado para edição.');
        }

        $dados = [
            'title'     => 'Editar Residente: ' . $residente->nome,
            'residente' => $residente,
            'errors'    => session('errors') ?? [],
        ];

        return view('Residentes/editar', $dados);
    }

    /**
     * Processa a atualização do residente, validação e sincronização com Shield (Itens 11 e 12)
     */
    public function atualizar(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        $dados = [
            'id'       => $id,
            'nome'     => trim((string) $this->request->getPost('nome')),
            'cpf'      => preg_replace('/\D/', '', (string) $this->request->getPost('cpf')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'telefone' => preg_replace('/\D/', '', (string) $this->request->getPost('telefone')),
            'unidade'  => trim((string) $this->request->getPost('unidade')),
            'bloco'    => trim((string) $this->request->getPost('bloco')) ?: null,
            'torre'    => trim((string) $this->request->getPost('torre')) ?: null,
            'ativo'    => (int) $this->request->getPost('ativo'),
        ];

        // Validação no Model
        if (! $this->residenteModel->save($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->residenteModel->errors())
                             ->with('erro', 'Ocorreram erros de validação. Por favor, verifique os campos.');
        }

        // Regras de negócio pós-atualização (Item 12):
        // Sincroniza dados com usuário Shield se existir
        if ($residente->user_id) {
            $db = \Config\Database::connect();
            $db->transStart();

            // Sincroniza status ativo/bloqueado
            $db->table('users')
               ->where('id', $residente->user_id)
               ->update([
                   'active'         => $dados['ativo'],
                   'status_message' => $dados['ativo'] ? null : 'Acesso suspenso pela administração do condomínio.',
               ]);

            // Se o e-mail foi alterado, atualiza o secret de autenticação
            if ($residente->email !== $dados['email']) {
                $db->table('auth_identities')
                   ->where('user_id', $residente->user_id)
                   ->where('type', 'email_password')
                   ->update(['secret' => $dados['email']]);
            }

            $db->transComplete();
        }

        return redirect()->to(route_to('residentes.detalhes', $id))
                         ->with('sucesso', 'Cadastro do residente atualizado com sucesso!');
    }

    /**
     * Processa a exclusão lógica do residente e revogação de acesso (Item 13)
     */
    public function excluir(int $id)
    {
        $residente = $this->residenteModel->find($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado para exclusão.');
        }

        if ($this->residenteModel->excluirResidente($id)) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('sucesso', "Residente {$residente->nome} excluído com sucesso!");
        }

        return redirect()->to(route_to('residentes.index'))
                         ->with('erro', 'Não foi possível excluir o residente. Tente novamente.');
    }

    /**
     * Alterna status (Ativo <-> Bloqueado) do residente (Item 13)
     */
    public function toggleStatus(int $id)
    {
        $residente = $this->residenteModel->find($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        if ($this->residenteModel->alternarStatus($id)) {
            $novoStatusTexto = $residente->ativo ? 'bloqueado' : 'ativado';
            return redirect()->back()
                             ->with('sucesso', "Residente {$residente->nome} foi {$novoStatusTexto} com sucesso!");
        }

        return redirect()->back()
                         ->with('erro', 'Não foi possível alterar o status do residente.');
    }

    /**
     * Tela para configurar e criar o usuário Shield vinculado (Item 14)
     */
    public function novoUsuario(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        if ($residente->user_id) {
            return redirect()->to(route_to('residentes.detalhes', $id))
                             ->with('erro', 'Este residente já possui uma conta de usuário Shield vinculada.');
        }

        // Sugestão de username amigável baseada no nome
        $partesNome   = explode(' ', trim($residente->nome));
        $primeiroNome = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $partesNome[0] ?? 'morador'));
        $ultimoNome   = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', end($partesNome) ?: 'residente'));
        $primeiroNome = preg_replace('/[^a-z0-9]/', '', $primeiroNome);
        $ultimoNome   = preg_replace('/[^a-z0-9]/', '', $ultimoNome);
        $sugestaoUsername = $primeiroNome . '.' . $ultimoNome;

        // Sugestão de senha segura temporária
        $senhaSugerida = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*'), 0, 10);

        $dados = [
            'title'            => 'Criar Usuário para ' . $residente->nome,
            'residente'        => $residente,
            'sugestaoUsername' => $sugestaoUsername,
            'senhaSugerida'    => $senhaSugerida,
            'errors'           => session('errors') ?? [],
        ];

        return view('Residentes/novo_usuario', $dados);
    }

    /**
     * Cria e vincula o usuário do Shield ao residente (Item 15)
     */
    public function criarUsuario(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        if ($residente->user_id) {
            return redirect()->to(route_to('residentes.detalhes', $id))
                             ->with('erro', 'Este residente já possui um usuário vinculado.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $email    = strtolower(trim((string) ($this->request->getPost('email') ?: $residente->email)));
        $password = trim((string) $this->request->getPost('password'));

        // Se a senha não foi informada, gera uma temporária
        if (empty($password)) {
            $password = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*'), 0, 10);
        }

        // Validação dos dados do usuário
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
        ], [
            'username' => [
                'required'   => 'O nome de usuário é obrigatório.',
                'min_length' => 'O nome de usuário deve ter pelo menos 3 caracteres.',
                'is_unique'  => 'Este nome de usuário já está em uso por outra conta.',
            ],
            'email' => [
                'required'    => 'O e-mail é obrigatório.',
                'valid_email' => 'Informe um e-mail válido.',
                'is_unique'   => 'Este e-mail já está em uso por outro usuário no sistema.',
            ],
            'password' => [
                'required'   => 'A senha é obrigatória.',
                'min_length' => 'A senha deve conter pelo menos 8 caracteres.',
            ],
        ]);

        if (! $validation->run(['username' => $username, 'email' => $email, 'password' => $password])) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $validation->getErrors())
                             ->with('erro', 'Corrija os erros para criar a conta de acesso.');
        }

        // Cria o usuário através do Shield
        $usersProvider = auth()->getProvider();
        $userEntityClass = $usersProvider->returnType;

        $user = new $userEntityClass([
            'username'        => $username,
            'email'           => $email,
            'password'        => $password,
            'active'          => 1,
            'residente_id'    => $id,
            'tipo'            => 'residente',
            'primeiro_acesso' => 1,
        ]);

        $usersProvider->save($user);
        $userId = $usersProvider->getInsertID();

        // Associa ao grupo/role 'residente'
        $createdUser = $usersProvider->findById($userId);
        if ($createdUser && method_exists($createdUser, 'addGroup')) {
            $createdUser->addGroup('residente');
        }

        // Vincula bidirecionalmente na base do condomínio
        $this->residenteModel->vincularUsuario($id, $userId);

        // Se o e-mail cadastrado diferir, sincroniza no residente
        if ($residente->email !== $email) {
            $this->residenteModel->update($id, ['email' => $email]);
        }

        return redirect()->to(route_to('residentes.detalhes', $id))
                         ->with('sucesso', "Usuário criado com sucesso para {$residente->nome}! Login: <strong>{$username}</strong> | Senha temporária: <strong>{$password}</strong> (repasse estas credenciais ao morador).");
    }

    /**
     * Alterna o status de acesso do usuário Shield (Item 16)
     */
    public function toggleAcessoUsuario(int $id)
    {
        $residente = $this->residenteModel->obterComUsuario($id);

        if (! $residente) {
            return redirect()->to(route_to('residentes.index'))
                             ->with('erro', 'Residente não encontrado.');
        }

        if (! $residente->user_id) {
            return redirect()->to(route_to('residentes.detalhes', $id))
                             ->with('erro', 'Este residente não possui usuário Shield vinculado.');
        }

        $usersProvider = auth()->getProvider();
        $user = $usersProvider->findById($residente->user_id);

        if (! $user) {
            return redirect()->to(route_to('residentes.detalhes', $id))
                             ->with('erro', 'Registro de usuário não encontrado no Shield.');
        }

        $novoStatus = $user->active ? 0 : 1;
        $user->active = $novoStatus;
        $user->status_message = $novoStatus ? null : 'Acesso suspenso pela administração do condomínio.';

        $usersProvider->save($user);

        $statusTexto = $novoStatus ? 'liberado' : 'bloqueado';

        return redirect()->to(route_to('residentes.detalhes', $id))
                         ->with('sucesso', "Acesso ao sistema para o residente {$residente->nome} foi {$statusTexto} com sucesso!");
    }
}
