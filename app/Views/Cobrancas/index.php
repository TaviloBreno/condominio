<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Topo da Página -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h4 class="font-weight-bolder mb-0 text-dark">Gestão Financeira & Cobranças</h4>
        <p class="text-sm text-secondary mb-0">Controle de faturas, taxas de reservas de áreas comuns e extratos dos moradores.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('reservas.index') ?>" class="btn btn-outline-primary btn-sm mb-0">
          <i class="far fa-calendar-alt me-1"></i> Ver Reservas
        </a>
      </div>
    </div>

    <!-- 4 Cards de Resumo Financeiro -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-success">Total Recebido</p>
                <h5 class="font-weight-bolder mb-0 text-success">R$ <?= number_format($totalPago, 2, ',', '.') ?></h5>
              </div>
              <div class="icon icon-shape bg-gradient-success text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-check-double text-white"></i>
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
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-warning">Pendente / A Receber</p>
                <h5 class="font-weight-bolder mb-0 text-warning">R$ <?= number_format($totalPendente, 2, ',', '.') ?></h5>
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
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-secondary">Faturas Pagas</p>
                <h5 class="font-weight-bolder mb-0 text-dark"><?= esc($qtdPagas) ?></h5>
              </div>
              <div class="icon icon-shape bg-gray-200 text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-receipt text-dark"></i>
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
                <p class="text-xxs mb-0 text-uppercase font-weight-bold text-secondary">Faturas Pendentes</p>
                <h5 class="font-weight-bolder mb-0 text-dark"><?= esc($qtdPendentes) ?></h5>
              </div>
              <div class="icon icon-shape bg-gray-200 text-center rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-file-invoice-dollar text-dark"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros de Busca -->
    <div class="card mb-4 shadow-sm border-0">
      <div class="card-body p-3">
        <form method="get" action="<?= route_to('cobrancas.index') ?>" class="row g-2 align-items-center">
          <div class="col-12 col-md-6">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" name="busca" class="form-control" placeholder="Buscar por descrição, morador ou apartamento..." value="<?= esc($busca) ?>">
            </div>
          </div>

          <div class="col-8 col-md-4">
            <select name="status" class="form-select form-select-sm">
              <option value="">Todos os Status</option>
              <option value="pendente" <?= ($status === 'pendente') ? 'selected' : '' ?>>Pendentes</option>
              <option value="pago" <?= ($status === 'pago') ? 'selected' : '' ?>>Pagas</option>
              <option value="cancelado" <?= ($status === 'cancelado') ? 'selected' : '' ?>>Canceladas</option>
            </select>
          </div>

          <div class="col-4 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm bg-gradient-info w-100 mb-0">Filtrar</button>
            <?php if (! empty($busca) || ! empty($status)): ?>
              <a href="<?= route_to('cobrancas.index') ?>" class="btn btn-sm btn-outline-secondary mb-0 px-2" title="Limpar filtros">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabela de Cobranças -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="font-weight-bold mb-0 text-dark">Extrato Geral de Faturas</h6>
        <span class="text-xs text-secondary font-weight-bold">Total: <?= esc($pager->getTotal('cobrancas')) ?> faturas</span>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <?php if (empty($cobrancas)): ?>
          <div class="text-center py-5">
            <i class="fas fa-receipt text-secondary fa-3x mb-3"></i>
            <h6 class="text-secondary font-weight-normal">Nenhuma fatura encontrada.</h6>
          </div>
        <?php else: ?>
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Fatura</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Morador / Unidade</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descrição</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vencimento</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cobrancas as $cob): ?>
                  <tr>
                    <td class="ps-3">
                      <span class="text-xs font-weight-bold text-secondary">#<?= str_pad((string) $cob->id, 5, '0', STR_PAD_LEFT) ?></span>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-dark"><?= esc($cob->residente_nome ?? '-') ?></p>
                      <p class="text-xxs text-secondary mb-0">Apt: <?= esc($cob->unidade ?? '-') ?><?= ! empty($cob->bloco) ? ' &bull; Bloco ' . esc($cob->bloco) : '' ?></p>
                    </td>
                    <td>
                      <span class="text-xs font-weight-bold text-dark"><?= esc($cob->descricao) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-dark"><?= esc($cob->getDataVencimentoFormatada()) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold text-dark"><?= esc($cob->getValorFormatado()) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <?= $cob->getStatusBadge() ?>
                    </td>
                    <td class="align-middle text-center">
                      <a href="<?= route_to('cobrancas.detalhes', $cob->id) ?>" class="btn btn-link text-primary text-xs mb-0 p-1">
                        <i class="fas fa-file-invoice me-1"></i> Extrato / Pagar
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Paginação -->
          <?php if ($pager->getPageCount('cobrancas') > 1): ?>
            <div class="d-flex justify-content-center p-3 border-top">
              <?= $pager->links('cobrancas', 'soft_ui') ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
