<?php

namespace App\Controllers;

use App\Models\AreaModel;

class AreasController extends BaseController
{
    protected AreaModel $areaModel;

    public function __construct()
    {
        $this->areaModel = new AreaModel();
        helper(['form', 'url', 'text']);
    }

    /**
     * Listagem das áreas comuns com filtros e paginação
     */
    public function index(): string
    {
        $busca = trim((string) $this->request->getGet('busca'));
        $ativo = $this->request->getGet('ativo');

        $modelQuery = $this->areaModel->filtrarAreas($busca, ($ativo !== null && $ativo !== '') ? (int) $ativo : null);

        $areas = $modelQuery->paginate(9, 'areas');
        $pager = $this->areaModel->pager;

        $dados = [
            'title' => 'Áreas Comuns do Condomínio',
            'areas' => $areas,
            'pager' => $pager,
            'busca' => $busca,
            'ativo' => $ativo,
            'total' => $pager->getTotal('areas'),
        ];

        return view('Areas/index', $dados);
    }

    /**
     * Formulário para cadastro de uma nova área (Item 2)
     */
    public function novo(): string
    {
        $dados = [
            'title'  => 'Cadastrar Área Comum',
            'errors' => session('errors') ?? [],
        ];

        return view('Areas/novo', $dados);
    }

    /**
     * Processa a criação e validação de uma nova área comum (Item 2)
     */
    public function criar()
    {
        $taxaRaw = trim((string) $this->request->getPost('taxa_reserva'));
        $taxaLimpa = str_replace(',', '.', preg_replace('/[^\d,\.]/', '', $taxaRaw));
        $taxa = ($taxaLimpa !== '') ? (float) $taxaLimpa : 0.00;

        $dados = [
            'nome'           => trim((string) $this->request->getPost('nome')),
            'descricao'      => trim((string) $this->request->getPost('descricao')) ?: null,
            'capacidade'     => (int) $this->request->getPost('capacidade'),
            'horario_inicio' => trim((string) $this->request->getPost('horario_inicio')),
            'horario_fim'    => trim((string) $this->request->getPost('horario_fim')),
            'taxa_reserva'   => $taxa,
            'ativo'          => (int) ($this->request->getPost('ativo') ?? 1),
        ];

        if (! $this->areaModel->save($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->areaModel->errors())
                             ->with('erro', 'Por favor, verifique os erros apontados no formulário.');
        }

        return redirect()->to(route_to('areas.index'))
                         ->with('sucesso', "Área '{$dados['nome']}' cadastrada com sucesso!");
    }

    /**
     * Formulário de edição pré-preenchido de área comum (Item 3)
     */
    public function editar(int $id)
    {
        $area = $this->areaModel->find($id);

        if (! $area) {
            return redirect()->to(route_to('areas.index'))
                             ->with('erro', 'Área comum não encontrada para edição.');
        }

        $dados = [
            'title'  => 'Editar Área: ' . $area->nome,
            'area'   => $area,
            'errors' => session('errors') ?? [],
        ];

        return view('Areas/editar', $dados);
    }

    /**
     * Processa o update e validação da área comum (Item 3)
     */
    public function atualizar(int $id)
    {
        $area = $this->areaModel->find($id);

        if (! $area) {
            return redirect()->to(route_to('areas.index'))
                             ->with('erro', 'Área comum não encontrada.');
        }

        $taxaRaw = trim((string) $this->request->getPost('taxa_reserva'));
        $taxaLimpa = str_replace(',', '.', preg_replace('/[^\d,\.]/', '', $taxaRaw));
        $taxa = ($taxaLimpa !== '') ? (float) $taxaLimpa : 0.00;

        $dados = [
            'id'             => $id,
            'nome'           => trim((string) $this->request->getPost('nome')),
            'descricao'      => trim((string) $this->request->getPost('descricao')) ?: null,
            'capacidade'     => (int) $this->request->getPost('capacidade'),
            'horario_inicio' => trim((string) $this->request->getPost('horario_inicio')),
            'horario_fim'    => trim((string) $this->request->getPost('horario_fim')),
            'taxa_reserva'   => $taxa,
            'ativo'          => (int) $this->request->getPost('ativo'),
        ];

        if (! $this->areaModel->save($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->areaModel->errors())
                             ->with('erro', 'Erro ao atualizar a área comum. Corrija os dados informados.');
        }

        return redirect()->to(route_to('areas.index'))
                         ->with('sucesso', "Área '{$dados['nome']}' atualizada com sucesso!");
    }

    /**
     * Exclusão lógica (soft delete) da área comum (Item 3)
     */
    public function excluir(int $id)
    {
        $area = $this->areaModel->find($id);

        if (! $area) {
            return redirect()->to(route_to('areas.index'))
                             ->with('erro', 'Área não encontrada para exclusão.');
        }

        if ($this->areaModel->delete($id)) {
            return redirect()->to(route_to('areas.index'))
                             ->with('sucesso', "Área '{$area->nome}' excluída com sucesso!");
        }

        return redirect()->to(route_to('areas.index'))
                         ->with('erro', 'Não foi possível excluir a área comum.');
    }

    /**
     * Alterna status ativo/inativo da área comum (Item 3)
     */
    public function toggleStatus(int $id)
    {
        $area = $this->areaModel->find($id);

        if (! $area) {
            return redirect()->to(route_to('areas.index'))
                             ->with('erro', 'Área comum não encontrada.');
        }

        $novoStatus = $area->ativo ? 0 : 1;
        $this->areaModel->update($id, ['ativo' => $novoStatus]);

        $statusTexto = $novoStatus ? 'disponibilizada' : 'indisponibilizada';

        return redirect()->back()
                         ->with('sucesso', "A área {$area->nome} foi {$statusTexto} com sucesso!");
    }
}
