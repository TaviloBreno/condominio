<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 col-lg-10 mx-auto">
    <!-- Breadcrumb e Topo -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-0 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('reservas.index') ?>">Reservas</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detalhes</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">
          Reserva #<?= str_pad((string) $reserva->id, 4, '0', STR_PAD_LEFT) ?>
        </h4>
      </div>
      <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= route_to('reservas.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
        <button type="button" class="btn btn-outline-primary btn-sm mb-0" onclick="window.print();">
          <i class="fas fa-print me-1"></i> Imprimir Comprovante
        </button>
      </div>
    </div>

    <!-- Card de Detalhes da Reserva -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-0 font-weight-bold">Comprovante e Informações do Agendamento</h6>
          <p class="text-xs text-secondary mb-3">Registrado em <?= date('d/m/Y \à\s H:i', strtotime($reserva->created_at)) ?></p>
        </div>
        <div class="mb-3">
          <?= $reserva->getStatusBadge() ?>
        </div>
      </div>
      <div class="card-body p-4">
        <div class="row g-4 mb-4">
          <!-- Coluna 1: Espaço Reservado -->
          <div class="col-12 col-md-6">
            <div class="bg-gray-100 p-3 border-radius-md h-100">
              <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                <i class="fas fa-swimming-pool me-1 text-primary"></i> Espaço / Área Comum
              </h6>
              <h5 class="text-dark font-weight-bold mb-1"><?= esc($reserva->area_nome) ?></h5>
              <?php if (! empty($reserva->area_descricao)): ?>
                <p class="text-xs text-secondary mb-3"><?= esc($reserva->area_descricao) ?></p>
              <?php endif; ?>

              <div class="text-xs text-secondary mb-1">
                <span class="font-weight-bold text-dark">Capacidade do Local:</span> <?= esc($reserva->area_capacidade ?? '-') ?> pessoas
              </div>
              <div class="text-xs text-secondary mb-1">
                <span class="font-weight-bold text-dark">Horário de Funcionamento:</span> <?= substr((string) ($reserva->area_horario_inicio ?? '00:00'), 0, 5) ?> às <?= substr((string) ($reserva->area_horario_fim ?? '00:00'), 0, 5) ?>
              </div>
              <div class="text-xs text-secondary">
                <span class="font-weight-bold text-dark">Taxa Aplicada:</span>
                <span class="font-weight-bold text-success"><?= esc($reserva->getTaxaFormatada()) ?></span>
              </div>
            </div>
          </div>

          <!-- Coluna 2: Morador Solicitante -->
          <div class="col-12 col-md-6">
            <div class="bg-gray-100 p-3 border-radius-md h-100">
              <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                <i class="fas fa-user me-1 text-primary"></i> Morador Responsável
              </h6>
              <h5 class="text-dark font-weight-bold mb-1"><?= esc($reserva->residente_nome) ?></h5>
              <p class="text-xs text-secondary mb-2">
                <i class="fas fa-home me-1"></i> Apartamento/Unidade: <strong><?= esc($reserva->unidade) ?></strong>
                <?= ! empty($reserva->bloco) ? ' &bull; Bloco: ' . esc($reserva->bloco) : '' ?>
              </p>

              <div class="text-xs text-secondary mb-1">
                <span class="font-weight-bold text-dark"><i class="fas fa-phone me-1"></i> Telefone:</span> <?= esc($reserva->telefone ?: 'Não informado') ?>
              </div>
              <div class="text-xs text-secondary">
                <span class="font-weight-bold text-dark"><i class="fas fa-envelope me-1"></i> E-mail:</span> <?= esc($reserva->email) ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Seção: Data, Horários e Cobrança -->
        <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
          <i class="far fa-calendar-check me-1 text-primary"></i> Detalhes da Utilização
        </h6>

        <div class="row g-3 mb-4">
          <div class="col-12 col-md-4">
            <div class="border border-radius-md p-3 text-center">
              <span class="text-xs text-secondary text-uppercase d-block mb-1">Data da Reserva</span>
              <h5 class="text-primary font-weight-bolder mb-0"><?= esc($reserva->getDataFormatada()) ?></h5>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="border border-radius-md p-3 text-center">
              <span class="text-xs text-secondary text-uppercase d-block mb-1">Período Reservado</span>
              <h5 class="text-dark font-weight-bolder mb-0"><?= esc($reserva->getHorarioFormatado()) ?></h5>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="border border-radius-md p-3 text-center">
              <span class="text-xs text-secondary text-uppercase d-block mb-1">Taxa de Locação</span>
              <h5 class="text-success font-weight-bolder mb-0"><?= esc($reserva->getTaxaFormatada()) ?></h5>
            </div>
          </div>
        </div>

        <!-- Observações e Histórico -->
        <?php if (! empty($reserva->observacoes)): ?>
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-2">
            <i class="fas fa-info-circle me-1 text-primary"></i> Observações e Histórico
          </h6>
          <div class="alert alert-secondary text-white text-xs mb-4" role="alert">
            <?= nl2br(esc($reserva->observacoes)) ?>
          </div>
        <?php endif; ?>

        <!-- Ação de Cancelamento -->
        <?php if ($reserva->podeCancelar()): ?>
          <div class="border-top pt-4 mt-4">
            <h6 class="text-danger font-weight-bold text-sm mb-2">
              <i class="fas fa-exclamation-triangle me-1"></i> Cancelamento da Reserva
            </h6>
            <p class="text-xs text-secondary mb-3">
              Caso seja necessário desmarcar o agendamento, informe o motivo abaixo. O cancelamento liberará o espaço para outros moradores.
            </p>
            <form method="post" action="<?= route_to('reservas.cancelar', $reserva->id) ?>" onsubmit="return confirm('Deseja realmente cancelar esta reserva?');">
              <?= csrf_field() ?>
              <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                  <input type="text" name="motivo" class="form-control form-control-sm" placeholder="Motivo do cancelamento (opcional)...">
                </div>
                <div class="col-12 col-md-4">
                  <button type="submit" class="btn btn-outline-danger btn-sm w-100 mb-0">
                    <i class="fas fa-times me-1"></i> Confirmar Cancelamento
                  </button>
                </div>
              </div>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
