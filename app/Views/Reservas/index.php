<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Topo da Página e Botão Nova Reserva -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h4 class="font-weight-bolder mb-0 text-dark">Reservas de Áreas Comuns</h4>
        <p class="text-sm text-secondary mb-0">Gerencie e acompanhe a ocupação dos espaços sociais do condomínio.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('reservas.novo') ?>" class="btn bg-gradient-primary btn-sm mb-0 shadow-sm">
          <i class="fas fa-calendar-plus me-1"></i> Nova Reserva
        </a>
      </div>
    </div>

    <!-- 4 Cards de Resumo Rápido -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-secondary">Total Geral</p>
                <h5 class="font-weight-bolder mb-0 text-dark"><?= esc($totalGeral) ?></h5>
              </div>
              <div class="icon icon-shape bg-gray-200 text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-calendar text-dark"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-success">Confirmadas</p>
                <h5 class="font-weight-bolder mb-0 text-success"><?= esc($totalConfirmadas) ?></h5>
              </div>
              <div class="icon icon-shape bg-gradient-success text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-check text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-warning">Pendentes</p>
                <h5 class="font-weight-bolder mb-0 text-warning"><?= esc($totalPendentes) ?></h5>
              </div>
              <div class="icon icon-shape bg-gradient-warning text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-clock text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-secondary">Canceladas</p>
                <h5 class="font-weight-bolder mb-0 text-secondary"><?= esc($totalCanceladas) ?></h5>
              </div>
              <div class="icon icon-shape bg-gradient-secondary text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-ban text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="card mb-4 shadow-sm border-0">
      <div class="card-body p-3">
        <form method="get" action="<?= route_to('reservas.index') ?>" class="row g-2 align-items-center">
          <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" name="busca" class="form-control" placeholder="Buscar por área, morador ou apto..." value="<?= esc($busca) ?>">
            </div>
          </div>

          <div class="col-6 col-md-3">
            <select name="status" class="form-select form-select-sm">
              <option value="">Todos os Status</option>
              <option value="confirmada" <?= ($status === 'confirmada') ? 'selected' : '' ?>>Confirmadas</option>
              <option value="pendente" <?= ($status === 'pendente') ? 'selected' : '' ?>>Pendentes</option>
              <option value="cancelada" <?= ($status === 'cancelada') ? 'selected' : '' ?>>Canceladas</option>
            </select>
          </div>

          <div class="col-6 col-md-3">
            <input type="date" name="data" class="form-control form-control-sm" value="<?= esc($data) ?>" title="Filtrar por data específica">
          </div>

          <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm bg-gradient-info w-100 mb-0">
              <i class="fas fa-filter me-1"></i> Filtrar
            </button>
            <?php if (! empty($busca) || ! empty($status) || ! empty($data)): ?>
              <a href="<?= route_to('reservas.index') ?>" class="btn btn-sm btn-outline-secondary mb-0 px-2" title="Limpar filtros">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabela de Reservas -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="font-weight-bold mb-0 text-dark">Listagem de Agendamentos</h6>
        <span class="text-xs text-secondary font-weight-bold">Total: <?= esc($pager->getTotal('reservas')) ?> registros</span>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <?php if (empty($reservas)): ?>
          <div class="text-center py-5">
            <i class="far fa-calendar-times text-secondary fa-3x mb-3"></i>
            <h6 class="text-secondary font-weight-normal">Nenhuma reserva encontrada para os filtros selecionados.</h6>
            <a href="<?= route_to('reservas.novo') ?>" class="btn bg-gradient-primary btn-sm mt-2">
              <i class="fas fa-plus me-1"></i> Criar Nova Reserva
            </a>
          </div>
        <?php else: ?>
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Cód.</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Espaço / Área</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Morador / Unidade</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data & Horário</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Taxa</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservas as $res): ?>
                  <tr>
                    <td class="ps-3">
                      <span class="text-xs font-weight-bold text-secondary">#<?= str_pad((string) $res->id, 4, '0', STR_PAD_LEFT) ?></span>
                    </td>
                    <td>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-xs font-weight-bold text-dark"><?= esc($res->area_nome ?? 'Área') ?></h6>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-dark"><?= esc($res->residente_nome ?? '-') ?></p>
                      <p class="text-xxs text-secondary mb-0">Apto: <?= esc($res->unidade ?? '-') ?><?= ! empty($res->bloco) ? ' &bull; Bloco ' . esc($res->bloco) : '' ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-dark d-block"><?= esc($res->getDataFormatada()) ?></span>
                      <span class="text-xxs text-secondary"><?= esc($res->getHorarioFormatado()) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold <?= $res->valor_taxa > 0 ? 'text-success' : 'text-primary' ?>">
                        <?= esc($res->getTaxaFormatada()) ?>
                      </span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <?= $res->getStatusBadge() ?>
                    </td>
                    <td class="align-middle text-center">
                      <a href="<?= route_to('reservas.detalhes', $res->id) ?>" class="btn btn-link text-primary text-xs mb-0 p-1" title="Ver detalhes completos">
                        <i class="fas fa-eye me-1"></i> Detalhes
                      </a>

                      <?php if ($res->podeCancelar()): ?>
                        <form method="post" action="<?= route_to('reservas.cancelar', $res->id) ?>" class="d-inline" onsubmit="return confirm('Deseja realmente cancelar esta reserva?');">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-link text-danger text-xs mb-0 p-1" title="Cancelar reserva">
                            <i class="fas fa-times me-1"></i> Cancelar
                          </button>
                        </form>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Paginação -->
          <?php if ($pager->getPageCount('reservas') > 1): ?>
            <div class="d-flex justify-content-center p-3 border-top">
              <?= $pager->links('reservas', 'soft_ui') ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
