<?php

namespace App\Controllers;

use App\Models\AreaModel;
use App\Models\ReservaModel;
use App\Models\ResidenteModel;
use App\Services\NotificacaoService;

class ReservasController extends BaseController
{
    protected ReservaModel $reservaModel;
    protected AreaModel $areaModel;
    protected ResidenteModel $residenteModel;
    protected NotificacaoService $notificacaoService;

    public function __construct()
    {
        $this->reservaModel        = new ReservaModel();
        $this->areaModel           = new AreaModel();
        $this->residenteModel      = new ResidenteModel();
        $this->notificacaoService  = new NotificacaoService();
        helper(['form', 'url', 'text']);
    }

    /**
     * Listagem geral de reservas com filtros e paginação (Item 8)
     */
    public function index(): string
    {
        $busca  = trim((string) $this->request->getGet('busca'));
        $status = trim((string) $this->request->getGet('status'));
        $data   = trim((string) $this->request->getGet('data'));

        $query = $this->reservaModel->filtrarReservas($busca, $status, $data);
        $reservas = $query->paginate(10, 'reservas');
        $pager    = $this->reservaModel->pager;

        // Contadores para cartões de resumo
        $totalGeral       = $this->reservaModel->countAllResults();
        $totalConfirmadas = $this->reservaModel->where('status', 'confirmada')->countAllResults();
        $totalPendentes   = $this->reservaModel->where('status', 'pendente')->countAllResults();
        $totalCanceladas  = $this->reservaModel->where('status', 'cancelada')->countAllResults();

        $dados = [
            'title'            => 'Reservas de Áreas Comuns',
            'reservas'         => $reservas,
            'pager'            => $pager,
            'busca'            => $busca,
            'status'           => $status,
            'data'             => $data,
            'totalGeral'       => $totalGeral,
            'totalConfirmadas' => $totalConfirmadas,
            'totalPendentes'   => $totalPendentes,
            'totalCanceladas'  => $totalCanceladas,
        ];

        return view('Reservas/index', $dados);
    }

    /**
     * Exibe o formulário de cadastro de nova reserva (Item 9)
     */
    public function novo(): string
    {
        $areaIdSelecionada = (int) $this->request->getGet('area_id');
        $dataPrevia        = trim((string) $this->request->getGet('data'));

        $areas      = $this->areaModel->listarDisponiveis();
        $residentes = $this->residenteModel->where('ativo', 1)->orderBy('nome', 'ASC')->findAll();

        $dados = [
            'title'             => 'Solicitar Reserva de Espaço',
            'areas'             => $areas,
            'residentes'        => $residentes,
            'areaIdSelecionada' => $areaIdSelecionada,
            'dataPrevia'        => $dataPrevia ?: date('Y-m-d'),
            'errors'            => session('errors') ?? [],
        ];

        return view('Reservas/novo', $dados);
    }

    /**
     * Processa a criação e validação da reserva (Item 9)
     */
    public function criar()
    {
        $dados = [
            'area_id'        => (int) $this->request->getPost('area_id'),
            'residente_id'   => (int) $this->request->getPost('residente_id'),
            'data_reserva'   => trim((string) $this->request->getPost('data_reserva')),
            'horario_inicio' => trim((string) $this->request->getPost('horario_inicio')),
            'horario_fim'    => trim((string) $this->request->getPost('horario_fim')),
            'observacoes'    => trim((string) $this->request->getPost('observacoes')) ?: null,
            'status'         => 'confirmada',
        ];

        $reservaId = $this->reservaModel->criarReserva($dados);

        if (! $reservaId) {
            $erros = $this->reservaModel->errors();
            $primeiroErro = ! empty($erros) ? reset($erros) : 'Não foi possível efetuar a reserva. Verifique os dados informados.';

            return redirect()->back()
                             ->withInput()
                             ->with('errors', $erros)
                             ->with('erro', $primeiroErro);
        }

        // Dispara notificação por e-mail e registro interno para o síndico (Item 11)
        $reserva = $this->reservaModel->buscarDetalhada($reservaId);
        if ($reserva) {
            $this->notificacaoService->notificarNovaReserva($reserva);

            // Geração automática de cobrança vinculada à reserva (Item 14 e 15)
            $cobrancaModel = new \App\Models\CobrancaModel();
            $cobrancaModel->gerarParaReserva($reserva);
        }

        return redirect()->to(route_to('reservas.detalhes', $reservaId))
                         ->with('sucesso', 'Reserva criada e confirmada! O síndico foi notificado e a cobrança gerada.');
    }

    /**
     * Exibe os detalhes completos de uma reserva (Item 8)
     */
    public function detalhes(int $id)
    {
        $reserva = $this->reservaModel->buscarDetalhada($id);

        if (! $reserva) {
            return redirect()->to(route_to('reservas.index'))
                             ->with('erro', 'Reserva não encontrada.');
        }

        $dados = [
            'title'   => 'Detalhes da Reserva #' . str_pad((string) $reserva->id, 4, '0', STR_PAD_LEFT),
            'reserva' => $reserva,
        ];

        return view('Reservas/detalhes', $dados);
    }

    /**
     * Cancela uma reserva existente com notificação e liberação de horário (Item 12)
     */
    public function cancelar(int $id)
    {
        $reserva = $this->reservaModel->buscarDetalhada($id);

        if (! $reserva) {
            return redirect()->to(route_to('reservas.index'))
                             ->with('erro', 'Reserva não encontrada.');
        }

        $motivo = trim((string) $this->request->getPost('motivo'));

        if (! $this->reservaModel->cancelarReserva($id, $motivo)) {
            $erros = $this->reservaModel->errors();
            $msg   = ! empty($erros) ? reset($erros) : 'Não foi possível cancelar a reserva.';

            return redirect()->back()->with('erro', $msg);
        }

        // Notifica o síndico do cancelamento e registra no banco (Item 12)
        $this->notificacaoService->notificarCancelamentoReserva($reserva, $motivo);

        // Cancela eventuais cobranças pendentes da reserva (Item 15)
        $cobrancaModel = new \App\Models\CobrancaModel();
        $cobrancaModel->cancelarPorReserva($id);

        return redirect()->back()->with('sucesso', 'Reserva cancelada com sucesso! O horário foi liberado.');
    }

    /**
     * Confirma manualmente uma reserva pendente
     */
    public function confirmar(int $id)
    {
        if (! $this->reservaModel->confirmarReserva($id)) {
            return redirect()->back()->with('erro', 'Não foi possível confirmar a reserva.');
        }

        return redirect()->back()->with('sucesso', 'Reserva confirmada com sucesso!');
    }
}
