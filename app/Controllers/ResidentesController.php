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

    public function novo()
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function criar()
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function detalhes(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function editar(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function atualizar(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function excluir(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function toggleStatus(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function criarUsuario(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }

    public function toggleAcessoUsuario(int $id)
    {
        return redirect()->to(route_to('residentes.index'));
    }
}
