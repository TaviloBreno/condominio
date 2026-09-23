<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nova Reserva Confirmada</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; color: #344767; }
    .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(310deg, #7928ca 0%, #ff0080 100%); padding: 30px 24px; text-align: center; color: #ffffff; }
    .header h2 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
    .header p { margin: 6px 0 0; opacity: 0.9; font-size: 14px; }
    .content { padding: 30px 24px; }
    .badge { display: inline-block; padding: 4px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; border-radius: 6px; background-color: #d1e7dd; color: #0f5132; margin-bottom: 16px; }
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .info-table td { padding: 10px 12px; border-bottom: 1px solid #f0f2f5; font-size: 14px; }
    .info-table td.label { font-weight: 600; color: #8392ab; width: 38%; }
    .info-table td.val { font-weight: 600; color: #252f40; }
    .obs-box { background-color: #f8f9fa; border-left: 4px solid #cb0c9f; padding: 14px 16px; border-radius: 4px; font-size: 13px; color: #495057; margin-bottom: 24px; }
    .button-container { text-align: center; margin: 28px 0; }
    .btn { background: linear-gradient(310deg, #7928ca 0%, #ff0080 100%); color: #ffffff !important; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; display: inline-block; }
    .footer { text-align: center; font-size: 12px; color: #8392ab; padding: 20px 24px; background-color: #f8f9fa; border-top: 1px solid #f0f2f5; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>🏢 Gestão Condominial</h2>
      <p>Notificação ao Síndico e Administração</p>
    </div>

    <div class="content">
      <span class="badge">Novo Agendamento Confirmado</span>
      <h3 style="margin-top: 0; color: #252f40; font-size: 18px;">Reserva da Área: <?= esc($reserva->area_nome) ?></h3>
      <p style="font-size: 14px; color: #67748e; margin-bottom: 20px;">
        Uma nova reserva foi registrada no sistema. Confira os dados completos do agendamento abaixo:
      </p>

      <table class="info-table">
        <tr>
          <td class="label">Espaço Reservado:</td>
          <td class="val"><?= esc($reserva->area_nome) ?></td>
        </tr>
        <tr>
          <td class="label">Morador Titular:</td>
          <td class="val"><?= esc($reserva->residente_nome) ?></td>
        </tr>
        <tr>
          <td class="label">Localização:</td>
          <td class="val">Apto <?= esc($reserva->unidade) ?><?= ! empty($reserva->bloco) ? ' &bull; Bloco ' . esc($reserva->bloco) : '' ?></td>
        </tr>
        <tr>
          <td class="label">Telefone / Whats:</td>
          <td class="val"><?= esc($reserva->telefone ?: 'Não informado') ?></td>
        </tr>
        <tr>
          <td class="label">Data do Evento:</td>
          <td class="val"><?= esc($reserva->getDataFormatada()) ?></td>
        </tr>
        <tr>
          <td class="label">Horário de Uso:</td>
          <td class="val"><?= esc($reserva->getHorarioFormatado()) ?></td>
        </tr>
        <tr>
          <td class="label">Taxa de Locação:</td>
          <td class="val" style="color: #2dce89;"><?= esc($reserva->getTaxaFormatada()) ?></td>
        </tr>
      </table>

      <?php if (! empty($reserva->observacoes)): ?>
        <div class="obs-box">
          <strong>Finalidade / Observações:</strong><br>
          <?= nl2br(esc($reserva->observacoes)) ?>
        </div>
      <?php endif; ?>

      <div class="button-container">
        <a href="<?= base_url('reservas/detalhes/' . $reserva->id) ?>" class="btn">Acessar Painel de Reservas</a>
      </div>
    </div>

    <div class="footer">
      Este é um e-mail automático gerado pelo Sistema de Gestão Condominial.<br>
      © <?= date('Y') ?> Condomínio Residencial. Todos os direitos reservados.
    </div>
  </div>
</body>
</html>
