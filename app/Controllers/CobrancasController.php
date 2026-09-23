<?php

namespace App\Controllers;

use App\Models\CobrancaModel;

class CobrancasController extends BaseController
{
    protected CobrancaModel $cobrancaModel;

    public function __construct()
    {
        $this->cobrancaModel = new CobrancaModel();
        helper(['form', 'url', 'text']);
    }

    /**
     * Listagem geral de cobranças com filtros e estatísticas financeiras (Item 16)
     */
    public function index(): string
    {
        $busca  = trim((string) $this->request->getGet('busca'));
        $status = trim((string) $this->request->getGet('status'));

        $query     = $this->cobrancaModel->filtrarCobrancas($busca, $status);
        $cobrancas = $query->paginate(10, 'cobrancas');
        $pager     = $this->cobrancaModel->pager;

        // Métricas financeiras
        $totalPendenteRow = $this->cobrancaModel->builder()
                                               ->selectSum('valor')
                                               ->where('status', 'pendente')
                                               ->where('deleted_at', null)
                                               ->get()
                                               ->getRow();
        $totalPendente = (float) ($totalPendenteRow->valor ?? 0.00);

        $totalPagoRow = $this->cobrancaModel->builder()
                                            ->selectSum('valor')
                                            ->where('status', 'pago')
                                            ->where('deleted_at', null)
                                            ->get()
                                            ->getRow();
        $totalPago = (float) ($totalPagoRow->valor ?? 0.00);

        $qtdPendentes = $this->cobrancaModel->where('status', 'pendente')->countAllResults();
        $qtdPagas     = $this->cobrancaModel->where('status', 'pago')->countAllResults();

        $dados = [
            'title'         => 'Gestão de Cobranças e Taxas',
            'cobrancas'     => $cobrancas,
            'pager'         => $pager,
            'busca'         => $busca,
            'status'        => $status,
            'totalPendente' => $totalPendente,
            'totalPago'     => $totalPago,
            'qtdPendentes'  => $qtdPendentes,
            'qtdPagas'      => $qtdPagas,
        ];

        return view('Cobrancas/index', $dados);
    }

    /**
     * Exibe o extrato e detalhes da cobrança para o residente (Item 16)
     */
    public function detalhes(int $id)
    {
        $cobranca = $this->cobrancaModel->buscarDetalhada($id);

        if (! $cobranca) {
            return redirect()->to(route_to('cobrancas.index'))
                             ->with('erro', 'Cobrança não encontrada.');
        }

        $dados = [
            'title'    => 'Extrato da Fatura #' . str_pad((string) $cobranca->id, 5, '0', STR_PAD_LEFT),
            'cobranca' => $cobranca,
        ];

        return view('Cobrancas/detalhes', $dados);
    }

    /**
     * Processa a confirmação/liquidação do pagamento (Item 15 e 16)
     */
    public function pagar(int $id)
    {
        $forma = trim((string) $this->request->getPost('forma_pagamento')) ?: 'PIX';

        if (! $this->cobrancaModel->registrarPagamento($id, $forma, 'Confirmação de pagamento no portal')) {
            $erros = $this->cobrancaModel->errors();
            $msg   = ! empty($erros) ? reset($erros) : 'Não foi possível confirmar o pagamento.';

            return redirect()->back()->with('erro', $msg);
        }

        return redirect()->back()->with('sucesso', 'Pagamento confirmado com sucesso! Recibo liquidado.');
    }
}
