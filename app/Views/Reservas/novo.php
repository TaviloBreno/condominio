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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Nova Reserva</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Agendar Espaço Comum</h4>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('reservas.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
      </div>
    </div>

    <!-- Formulário de Nova Reserva (Item 9) -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom">
        <h6 class="mb-0 font-weight-bold">Dados do Agendamento</h6>
        <p class="text-xs text-secondary mb-3">Selecione o espaço desejado, morador responsável e o período de uso pretendido.</p>
      </div>
      <div class="card-body p-4">
        <form method="post" action="<?= route_to('reservas.criar') ?>" autocomplete="off">
          <?= csrf_field() ?>

          <!-- Seção 1: Espaço e Morador -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-building me-1 text-primary"></i> 1. Espaço e Responsável
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">Área Comum / Instalação <span class="text-danger">*</span></label>
              <select name="area_id" id="areaSelect" class="form-select <?= isset($errors['area_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Selecione a Área Comum --</option>
                <?php foreach ($areas as $a): ?>
                  <option value="<?= esc($a->id) ?>"
                          data-inicio="<?= substr((string) $a->horario_inicio, 0, 5) ?>"
                          data-fim="<?= substr((string) $a->horario_fim, 0, 5) ?>"
                          data-taxa="<?= esc($a->getTaxaFormatada()) ?>"
                          data-capacidade="<?= esc($a->getCapacidadeFormatada()) ?>"
                          <?= (old('area_id', $areaIdSelecionada) == $a->id) ? 'selected' : '' ?>>
                    <?= esc($a->nome) ?> (<?= esc($a->getTaxaFormatada()) ?> - Até <?= esc($a->capacidade) ?> pessoas)
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['area_id'])): ?>
                <div class="invalid-feedback"><?= esc($errors['area_id']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary" id="areaInfoHelp">O horário de reserva deve respeitar o horário de funcionamento do local.</span>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">Morador Solicitante <span class="text-danger">*</span></label>
              <select name="residente_id" class="form-select <?= isset($errors['residente_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Selecione o Morador --</option>
                <?php foreach ($residentes as $r): ?>
                  <option value="<?= esc($r->id) ?>" <?= (old('residente_id') == $r->id) ? 'selected' : '' ?>>
                    <?= esc($r->nome) ?> — Apto: <?= esc($r->unidade) ?><?= ! empty($r->bloco) ? ' (Bloco ' . esc($r->bloco) . ')' : '' ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['residente_id'])): ?>
                <div class="invalid-feedback"><?= esc($errors['residente_id']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 2: Data e Horários -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="far fa-clock me-1 text-primary"></i> 2. Data e Período de Uso
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Data do Evento <span class="text-danger">*</span></label>
              <input type="date" name="data_reserva" min="<?= date('Y-m-d') ?>" class="form-control <?= isset($errors['data_reserva']) ? 'is-invalid' : '' ?>" value="<?= esc(old('data_reserva', $dataPrevia)) ?>" required>
              <?php if (isset($errors['data_reserva'])): ?>
                <div class="invalid-feedback"><?= esc($errors['data_reserva']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Horário de Início <span class="text-danger">*</span></label>
              <input type="time" name="horario_inicio" class="form-control <?= isset($errors['horario_inicio']) ? 'is-invalid' : '' ?>" value="<?= esc(old('horario_inicio', '10:00')) ?>" required>
              <?php if (isset($errors['horario_inicio'])): ?>
                <div class="invalid-feedback"><?= esc($errors['horario_inicio']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Horário de Término <span class="text-danger">*</span></label>
              <input type="time" name="horario_fim" class="form-control <?= isset($errors['horario_fim']) ? 'is-invalid' : '' ?>" value="<?= esc(old('horario_fim', '18:00')) ?>" required>
              <?php if (isset($errors['horario_fim'])): ?>
                <div class="invalid-feedback"><?= esc($errors['horario_fim']) ?></div>
              <?php endif; ?>
            </div>

            <?php if (isset($errors['conflito'])): ?>
              <div class="col-12">
                <div class="alert alert-danger text-white text-xs mb-0" role="alert">
                  <i class="fas fa-exclamation-triangle me-1"></i> <?= esc($errors['conflito']) ?>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Seção 3: Observações -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-edit me-1 text-primary"></i> 3. Observações / Finalidade do Evento
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12">
              <label class="form-label text-sm font-weight-bold">Descrição da Confraternização</label>
              <textarea name="observacoes" rows="3" class="form-control <?= isset($errors['observacoes']) ? 'is-invalid' : '' ?>" placeholder="Ex.: Aniversário infantil com aproximadamente 20 convidados. Utilizaremos churrasqueira e mesas."><?= esc(old('observacoes')) ?></textarea>
              <?php if (isset($errors['observacoes'])): ?>
                <div class="invalid-feedback"><?= esc($errors['observacoes']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Alerta de Normas de Uso -->
          <div class="bg-gray-100 p-3 border-radius-md mb-4 text-xs text-secondary">
            <div class="font-weight-bold text-dark mb-1">
              <i class="fas fa-info-circle text-primary me-1"></i> Regulamento de Uso dos Espaços Comuns:
            </div>
            <ul class="mb-0 ps-3">
              <li>O morador responsável deve acompanhar a entrega e vistoria do espaço na saída.</li>
              <li>A cobrança da taxa de reserva (quando houver) será gerada para a unidade vinculada.</li>
              <li>Cancelamentos devem ser efetuados com até 24 horas de antecedência pelo sistema.</li>
            </ul>
          </div>

          <!-- Botões de Ação -->
          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= route_to('reservas.index') ?>" class="btn btn-light mb-0">
              <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn bg-gradient-primary mb-0">
              <i class="fas fa-check me-1"></i> Confirmar Reserva
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
