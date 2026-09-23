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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('areas.index') ?>">Áreas Comuns</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Editar Área</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Editar: <?= esc($area->nome) ?></h4>
      </div>
      <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= route_to('areas.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
        <form method="post" action="<?= route_to('areas.excluir', $area->id) ?>" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta área comum? Ela será desativada do sistema.');">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-outline-danger btn-sm mb-0">
            <i class="fas fa-trash-alt me-1"></i> Excluir Área
          </button>
        </form>
      </div>
    </div>

    <!-- Formulário de Edição (Item 3) -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-0 font-weight-bold">Atualizar Informações da Área Comum</h6>
          <p class="text-xs text-secondary mb-3">Modifique os dados do espaço, horários ou taxas associadas.</p>
        </div>
        <div class="mb-3">
          <?= $area->getStatusBadge() ?>
        </div>
      </div>
      <div class="card-body p-4">
        <form method="post" action="<?= route_to('areas.atualizar', $area->id) ?>" autocomplete="off">
          <?= csrf_field() ?>

          <!-- Seção 1: Identificação da Área -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-cube me-1 text-primary"></i> 1. Identificação e Regras
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
              <label class="form-label text-sm font-weight-bold">Nome da Área <span class="text-danger">*</span></label>
              <input type="text" name="nome" class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>" value="<?= esc(old('nome', $area->nome)) ?>" placeholder="Ex.: Salão de Festas Principal" required>
              <?php if (isset($errors['nome'])): ?>
                <div class="invalid-feedback"><?= esc($errors['nome']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Capacidade Máxima (pessoas) <span class="text-danger">*</span></label>
              <input type="number" name="capacidade" min="1" max="1000" class="form-control <?= isset($errors['capacidade']) ? 'is-invalid' : '' ?>" value="<?= esc(old('capacidade', (string)$area->capacidade)) ?>" placeholder="Ex.: 50" required>
              <?php if (isset($errors['capacidade'])): ?>
                <div class="invalid-feedback"><?= esc($errors['capacidade']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12">
              <label class="form-label text-sm font-weight-bold">Descrição e Regras de Utilização</label>
              <textarea name="descricao" rows="3" class="form-control <?= isset($errors['descricao']) ? 'is-invalid' : '' ?>" placeholder="Regras de utilização, equipamentos disponíveis, etc."><?= esc(old('descricao', (string)$area->descricao)) ?></textarea>
              <?php if (isset($errors['descricao'])): ?>
                <div class="invalid-feedback"><?= esc($errors['descricao']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 2: Funcionamento e Taxas -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="far fa-clock me-1 text-primary"></i> 2. Horários e Valores
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Horário Inicial de Funcionamento <span class="text-danger">*</span></label>
              <input type="time" name="horario_inicio" class="form-control <?= isset($errors['horario_inicio']) ? 'is-invalid' : '' ?>" value="<?= esc(old('horario_inicio', substr((string)$area->horario_inicio, 0, 5))) ?>" required>
              <?php if (isset($errors['horario_inicio'])): ?>
                <div class="invalid-feedback"><?= esc($errors['horario_inicio']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Abertura para reservas.</span>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Horário Limite de Encerramento <span class="text-danger">*</span></label>
              <input type="time" name="horario_fim" class="form-control <?= isset($errors['horario_fim']) ? 'is-invalid' : '' ?>" value="<?= esc(old('horario_fim', substr((string)$area->horario_fim, 0, 5))) ?>" required>
              <?php if (isset($errors['horario_fim'])): ?>
                <div class="invalid-feedback"><?= esc($errors['horario_fim']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Limite de desocupação e silêncio.</span>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Taxa de Reserva (R$)</label>
              <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" min="0" name="taxa_reserva" class="form-control <?= isset($errors['taxa_reserva']) ? 'is-invalid' : '' ?>" value="<?= esc(old('taxa_reserva', number_format((float)$area->taxa_reserva, 2, '.', ''))) ?>" placeholder="0.00">
                <?php if (isset($errors['taxa_reserva'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['taxa_reserva']) ?></div>
                <?php endif; ?>
              </div>
              <span class="text-xxs text-secondary">0.00 se gratuito aos moradores.</span>
            </div>
          </div>

          <!-- Seção 3: Disponibilidade -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-toggle-on me-1 text-primary"></i> 3. Disponibilidade
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Status da Área <span class="text-danger">*</span></label>
              <select name="ativo" class="form-select <?= isset($errors['ativo']) ? 'is-invalid' : '' ?>" required>
                <option value="1" <?= old('ativo', (string)$area->ativo) === '1' ? 'selected' : '' ?>>Disponível para Reservas (Ativo)</option>
                <option value="0" <?= old('ativo', (string)$area->ativo) === '0' ? 'selected' : '' ?>>Indisponível / Manutenção (Inativo)</option>
              </select>
              <?php if (isset($errors['ativo'])): ?>
                <div class="invalid-feedback"><?= esc($errors['ativo']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Botões de Ação -->
          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= route_to('areas.index') ?>" class="btn btn-light mb-0">
              <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn bg-gradient-info mb-0">
              <i class="fas fa-save me-1"></i> Salvar Alterações
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
