<?php

namespace App\Controllers;

use App\Models\AreaModel;
use App\Models\ReservaModel;
use App\Models\ResidenteModel;

class HomeController extends BaseController
{
    protected ResidenteModel $residenteModel;
    protected AreaModel $areaModel;
    protected ReservaModel $reservaModel;

    public function __construct()
    {
        $this->residenteModel = new ResidenteModel();
        $this->areaModel      = new AreaModel();
        $this->reservaModel   = new ReservaModel();
        helper(['form', 'url', 'text']);
    }

    /**
     * Dashboard principal do condomínio
     */
    public function index(): string
    {
        // 1. Estatísticas de Residentes
        $totalResidentes   = $this->residenteModel->countAllResults();
        $residentesAtivos  = $this->residenteModel->where('ativo', 1)->countAllResults();
        $ultimosResidentes = $this->residenteModel->orderBy('id', 'DESC')->findAll(5);

        // 2. Estatísticas de Áreas Comuns
        $totalAreas    = $this->areaModel->countAllResults();
        $areasAtivas   = $this->areaModel->where('ativo', 1)->countAllResults();
        $areasDestaque = $this->areaModel->orderBy('nome', 'ASC')->findAll(6);

        // 3. Estatísticas de Reservas
        $totalReservas       = $this->reservaModel->countAllResults();
        $reservasConfirmadas = $this->reservaModel->where('status', 'confirmada')->countAllResults();
        $reservasPendentes   = $this->reservaModel->where('status', 'pendente')->countAllResults();

        // 4. Arrecadação de taxas de reservas (MySQL seguro)
        $arrecadacaoRow = $this->reservaModel->builder()
                                            ->selectSum('valor_taxa')
                                            ->where('status', 'confirmada')
                                            ->where('deleted_at', null)
                                            ->get()
                                            ->getRow();
        $totalArrecadado = (float) ($arrecadacaoRow->valor_taxa ?? 0.00);

        // 5. Próximas reservas registradas
        $proximasReservas = $this->reservaModel->filtrarReservas(null, null, null)->findAll(6);

        $data = [
            'title'               => 'Painel de Gestão Condominial',
            'totalResidentes'     => $totalResidentes,
            'residentesAtivos'    => $residentesAtivos,
            'ultimosResidentes'   => $ultimosResidentes,
            'totalAreas'          => $totalAreas,
            'areasAtivas'         => $areasAtivas,
            'areasDestaque'       => $areasDestaque,
            'totalReservas'       => $totalReservas,
            'reservasConfirmadas' => $reservasConfirmadas,
            'reservasPendentes'   => $reservasPendentes,
            'totalArrecadado'     => $totalArrecadado,
            'proximasReservas'    => $proximasReservas,
        ];

        return view('Home/index', $data);
    }
}
