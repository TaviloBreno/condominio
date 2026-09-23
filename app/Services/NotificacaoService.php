<?php

namespace App\Services;

use App\Entities\Reserva;
use App\Models\NotificacaoModel;
use Config\Services;

class NotificacaoService
{
    protected NotificacaoModel $notificacaoModel;
    protected string $emailSindico;

    public function __construct()
    {
        $this->notificacaoModel = new NotificacaoModel();
        $this->emailSindico      = (string) (env('condominio.sindico_email') ?: 'sindico@condominio.com');
    }

    /**
     * Dispara notificação de nova reserva para o síndico:
     * 1. Registra no banco de dados (tabela notificacoes)
     * 2. Envia e-mail formatado via CI4 Email Service
     */
    public function notificarNovaReserva(Reserva $reserva): bool
    {
        $titulo   = "Nova Reserva: {$reserva->area_nome} - {$reserva->residente_nome}";
        $mensagem = "O morador {$reserva->residente_nome} (Apto {$reserva->unidade}) agendou a área '{$reserva->area_nome}' para o dia {$reserva->getDataFormatada()} ({$reserva->getHorarioFormatado()}).";

        // 1. Persistência na tabela interna de notificações
        $notificacaoId = $this->notificacaoModel->registrarNotificacao(
            'nova_reserva',
            $titulo,
            $mensagem,
            $reserva->id,
            $this->emailSindico
        );

        // 2. Disparo de e-mail ao síndico
        $enviado = $this->enviarEmail(
            $this->emailSindico,
            "Nova Reserva Confirmada: {$reserva->area_nome}",
            view('Emails/nova_reserva_sindico', ['reserva' => $reserva])
        );

        if ($enviado && $notificacaoId) {
            $this->notificacaoModel->update($notificacaoId, ['enviada_email' => 1]);
        }

        return (bool) $notificacaoId;
    }

    /**
     * Dispara notificação de cancelamento de reserva
     */
    public function notificarCancelamentoReserva(Reserva $reserva, ?string $motivo = null): bool
    {
        $titulo   = "Reserva Cancelada: {$reserva->area_nome}";
        $mensagem = "A reserva de {$reserva->residente_nome} para o espaço '{$reserva->area_nome}' em {$reserva->getDataFormatada()} foi cancelada." . ($motivo ? " Motivo: {$motivo}" : '');

        // 1. Persistência no banco
        $notificacaoId = $this->notificacaoModel->registrarNotificacao(
            'cancelamento_reserva',
            $titulo,
            $mensagem,
            $reserva->id,
            $this->emailSindico
        );

        // 2. Disparo de e-mail ao síndico
        $enviado = $this->enviarEmail(
            $this->emailSindico,
            "Reserva Cancelada: {$reserva->area_nome}",
            view('Emails/cancelamento_reserva', ['reserva' => $reserva, 'motivo' => $motivo])
        );

        if ($enviado && $notificacaoId) {
            $this->notificacaoModel->update($notificacaoId, ['enviada_email' => 1]);
        }

        return (bool) $notificacaoId;
    }

    /**
     * Envia e-mail em formato HTML usando o serviço nativo do CodeIgniter
     */
    protected function enviarEmail(string $destinatario, string $assunto, string $corpoHtml): bool
    {
        try {
            $email = Services::email();
            $email->setTo($destinatario);
            $email->setSubject($assunto);
            $email->setMessage($corpoHtml);
            $email->setMailType('html');

            return (bool) $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', '[NotificacaoService] Falha no envio de e-mail: ' . $e->getMessage());
            return false;
        }
    }
}
