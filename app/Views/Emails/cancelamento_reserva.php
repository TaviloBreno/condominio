<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cancelamento de Reserva</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; color: #344767; }
    .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(310deg, #ea0606 0%, #ff667c 100%); padding: 30px 24px; text-align: center; color: #ffffff; }
    .header h2 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
    .header p { margin: 6px 0 0; opacity: 0.9; font-size: 14px; }
    .content { padding: 30px 24px; }
    .badge { display: inline-block; padding: 4px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; border-radius: 6px; background-color: #f8d7da; color: #842029; margin-bottom: 16px; }
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .info-table td { padding: 10px 12px; border-bottom: 1px solid #f0f2f5; font-size: 14px; }
    .info-table td.label { font-weight: 600; color: #8392ab; width: 38%; }
    .info-table td.val { font-weight: 600; color: #252f40; }
    .motivo-box { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 14px 16px; border-radius: 4px; font-size: 13px; color: #664d03; margin-bottom: 24px; }
    .button-container { text-align: center; margin: 28px 0; }
    .btn { background: linear-gradient(310deg, #141727 0%, #3a416f 100%); color: #ffffff !important; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; display: inline-block; }
    .footer { text-align: center; font-size: 12px; color: #8392ab; padding: 20px 24px; background-color: #f8f9fa; border-top: 1px solid #f0f2f5; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>🏢 Gestão Condominial</h2>
      <p>Aviso de Cancelamento de Reserva</p>
    </div>

    <div class="content">
      <span class="badge">Reserva Cancelada</span>
      <h3 style="margin-top: 0; color: #252f40; font-size: 18px;">Área: <?= esc($reserva->area_nome) ?></h3>
      <p style="font-size: 14px; color: #67748e; margin-bottom: 20px;">
        Informamos que a reserva abaixo foi <strong>cancelada</strong> e o horário foi liberado no calendário:
      </p>

      <table class="info-table">
        <tr>
          <td class="label">Espaço Desocupado:</td>
          <td class="val"><?= esc($reserva->area_nome) ?></td>
        </tr>
        <tr>
          <td class="label">Morador Titular:</td>
          <td class="val"><?= esc($reserva->residente_nome) ?> (Apto: <?= esc($reserva->unidade) ?>)</td>
        </tr>
        <tr>
          <td class="label">Data Prevista:</td>
          <td class="val"><?= esc($reserva->getDataFormatada()) ?></td>
        </tr>
        <tr>
          <td class="label">Horário Liberado:</td>
          <td class="val"><?= esc($reserva->getHorarioFormatado()) ?></td>
        </tr>
      </table>

      <?php if (! empty($motivo)): ?>
        <div class="motivo-box">
          <strong>Justificativa do Cancelamento:</strong><br>
          <?= nl2br(esc($motivo)) ?>
        </div>
      <?php endif; ?>

      <div class="button-container">
        <a href="<?= base_url('reservas') ?>" class="btn">Visualizar Calendário de Reservas</a>
      </div>
    </div>

    <div class="footer">
      Este é um e-mail automático gerado pelo Sistema de Gestão Condominial.<br>
      © <?= date('Y') ?> Condomínio Residencial. Todos os direitos reservados.
    </div>
  </div>
</body>
</html>
