<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Topo e Ação Principal -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h4 class="font-weight-bolder mb-0 text-dark">Áreas Comuns</h4>
        <p class="text-sm text-secondary mb-0">Espaços compartilhados do condomínio disponíveis para reserva pelos moradores.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('areas.novo') ?>" class="btn bg-gradient-primary btn-sm mb-0">
          <i class="fas fa-plus me-1"></i> Nova Área Comum
        </a>
      </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm border-0">
      <div class="card-body p-3">
        <form method="get" action="<?= route_to('areas.index') ?>" class="row g-2 align-items-center">
          <div class="col-12 col-md-6">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" name="busca" class="form-control" placeholder="Buscar área por nome ou descrição..." value="<?= esc($busca) ?>">
            </div>
          </div>

          <div class="col-6 col-md-3">
            <select name="ativo" class="form-select form-select-sm">
              <option value="">Todos os Status</option>
              <option value="1" <?= ($ativo === '1') ? 'selected' : '' ?>>Disponíveis</option>
              <option value="0" <?= ($ativo === '0') ? 'selected' : '' ?>>Indisponíveis</option>
            </select>
          </div>

          <div class="col-6 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm bg-gradient-info w-100 mb-0">
              <i class="fas fa-filter me-1"></i> Filtrar
            </button>
            <?php if (! empty($busca) || $ativo !== ''): ?>
              <a href="<?= route_to('areas.index') ?>" class="btn btn-sm btn-outline-secondary mb-0 px-2" title="Limpar filtros">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Grid de Áreas Comuns -->
    <?php if (empty($areas)): ?>
      <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
          <i class="fas fa-swimming-pool text-secondary fa-3x mb-3"></i>
          <h6 class="text-secondary font-weight-normal">Nenhuma área comum cadastrada no momento.</h6>
          <a href="<?= route_to('areas.novo') ?>" class="btn bg-gradient-primary btn-sm mt-2">
            <i class="fas fa-plus me-1"></i> Cadastrar Primeira Área Comum
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="row g-4 mb-4">
        <?php foreach ($areas as $area): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
              <div class="card-header pb-2 border-bottom bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="font-weight-bolder text-dark mb-0 text-truncate" title="<?= esc($area->nome) ?>">
                    <?= esc($area->nome) ?>
                  </h5>
                  <?= $area->getStatusBadge() ?>
                </div>
              </div>

              <div class="card-body py-3">
                <p class="text-xs text-secondary mb-3" style="min-height: 38px;">
                  <?= esc(character_limiter($area->descricao ?: 'Sem descrição informada.', 90)) ?>
                </p>

                <div class="bg-gray-100 p-3 border-radius-md mb-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs text-secondary"><i class="fas fa-users me-1 text-primary"></i> Capacidade:</span>
                    <span class="text-xs font-weight-bold text-dark"><?= esc($area->getCapacidadeFormatada()) ?></span>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs text-secondary"><i class="far fa-clock me-1 text-info"></i> Horário:</span>
                    <span class="text-xs font-weight-bold text-dark"><?= esc($area->getHorarioFuncionamento()) ?></span>
                  </div>

                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-xs text-secondary"><i class="fas fa-dollar-sign me-1 text-success"></i> Taxa:</span>
                    <span class="text-xs font-weight-bolder <?= $area->taxa_reserva > 0 ? 'text-success' : 'text-primary' ?>">
                      <?= esc($area->getTaxaFormatada()) ?>
                    </span>
                  </div>
                </div>
              </div>

              <div class="card-footer pt-0 border-top bg-transparent d-flex justify-content-between align-items-center">
                <form method="post" action="<?= route_to('areas.toggleStatus', $area->id) ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <?php if ($area->ativo): ?>
                    <button type="submit" class="btn btn-link text-warning text-xs mb-0 p-0" title="Desativar área">
                      <i class="fas fa-pause me-1"></i> Desativar
                    </button>
                  <?php else: ?>
                    <button type="submit" class="btn btn-link text-success text-xs mb-0 p-0" title="Ativar área">
                      <i class="fas fa-play me-1"></i> Ativar
                    </button>
                  <?php endif; ?>
                </form>

                <div class="d-flex gap-2">
                  <a href="<?= route_to('areas.editar', $area->id) ?>" class="btn btn-link text-dark text-xs mb-0 p-1" title="Editar área">
                    <i class="fas fa-pencil-alt me-1"></i> Editar
                  </a>

                  <form method="post" action="<?= route_to('areas.excluir', $area->id) ?>" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-link text-danger text-xs mb-0 p-1" onclick="return confirm('Deseja excluir a área <?= esc($area->nome) ?>?');" title="Excluir área">
                      <i class="fas fa-trash-alt me-1"></i> Excluir
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Paginação Soft UI -->
      <?php if ($pager->getPageCount('areas') > 1): ?>
        <div class="d-flex justify-content-center mt-2">
          <?= $pager->links('areas', 'soft_ui') ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
