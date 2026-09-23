<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
Residentes do Condomínio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Cabeçalho e Ações -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h4 class="font-weight-bolder mb-0 text-dark">Residentes do Condomínio</h4>
        <p class="text-sm text-secondary mb-0">Gerencie os moradores, suas respectivas unidades e acessos ao sistema.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('residentes.novo') ?>" class="btn bg-gradient-primary btn-sm mb-0">
          <i class="fas fa-plus me-1"></i> Novo Residente
        </a>
      </div>
    </div>

    <!-- Card de Busca e Filtros (Item 7) -->
    <div class="card mb-4 shadow-sm border-0">
      <div class="card-body p-3">
        <form method="get" action="<?= route_to('residentes.index') ?>" class="row g-2 align-items-center">
          <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, CPF, e-mail ou unidade..." value="<?= esc($busca) ?>">
            </div>
          </div>

          <div class="col-6 col-md-2">
            <select name="bloco" class="form-select form-select-sm">
              <option value="">Todos os Blocos</option>
              <?php foreach ($blocos as $b): ?>
                <option value="<?= esc($b) ?>" <?= ($bloco === (string) $b) ? 'selected' : '' ?>>
                  Bloco <?= esc($b) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-6 col-md-2">
            <select name="ativo" class="form-select form-select-sm">
              <option value="">Todos os Status</option>
              <option value="1" <?= ($ativo === '1') ? 'selected' : '' ?>>Ativos</option>
              <option value="0" <?= ($ativo === '0') ? 'selected' : '' ?>>Bloqueados</option>
            </select>
          </div>

          <div class="col-6 col-md-2">
            <select name="ordenar" class="form-select form-select-sm">
              <option value="nome" <?= ($ordenar === 'nome') ? 'selected' : '' ?>>Ordenar por Nome</option>
              <option value="unidade" <?= ($ordenar === 'unidade') ? 'selected' : '' ?>>Ordenar por Unidade</option>
              <option value="created_at" <?= ($ordenar === 'created_at') ? 'selected' : '' ?>>Mais recentes</option>
            </select>
          </div>

          <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm bg-gradient-info w-100 mb-0">
              <i class="fas fa-filter me-1"></i> Filtrar
            </button>
            <?php if (! empty($busca) || $bloco !== '' || $ativo !== '' || $ordenar !== 'nome'): ?>
              <a href="<?= route_to('residentes.index') ?>" class="btn btn-sm btn-outline-secondary mb-0 px-2" title="Limpar filtros">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabela de Residentes (Item 6 e 7) -->
    <div class="card mb-4 shadow-sm border-0">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Listagem de Residentes</h6>
        <span class="badge bg-light text-dark font-weight-bold">
          <?= (int) ($total ?? count($residentes)) ?> cadastrado(s)
        </span>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Residente</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unidade / Local</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contato</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuário Shield</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-secondary opacity-7 text-center">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($residentes)): ?>
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <i class="fas fa-users-slash text-secondary fa-3x mb-3"></i>
                      <h6 class="text-secondary font-weight-normal">Nenhum residente encontrado.</h6>
                      <?php if (! empty($busca) || $bloco !== '' || $ativo !== ''): ?>
                        <p class="text-xs text-muted mb-3">Tente alterar os termos da busca ou remover os filtros aplicados.</p>
                        <a href="<?= route_to('residentes.index') ?>" class="btn btn-outline-primary btn-sm">Limpar Filtros</a>
                      <?php else: ?>
                        <a href="<?= route_to('residentes.novo') ?>" class="btn bg-gradient-primary btn-sm mt-2">
                          <i class="fas fa-user-plus me-1"></i> Cadastrar Primeiro Residente
                        </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($residentes as $residente): ?>
                  <tr>
                    <td>
                      <div class="d-flex px-3 py-1 align-items-center">
                        <div class="avatar avatar-sm me-3 bg-gradient-dark rounded-circle text-white d-flex align-items-center justify-content-center font-weight-bold">
                          <?= strtoupper(substr($residente->nome, 0, 1)) ?>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm font-weight-bold">
                            <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="text-dark">
                              <?= esc($residente->nome) ?>
                            </a>
                          </h6>
                          <p class="text-xs text-secondary mb-0">
                            <i class="far fa-id-card me-1"></i><?= esc($residente->getCpfFormatado()) ?>
                          </p>
                        </div>
                      </div>
                    </td>

                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-dark">
                        <i class="fas fa-door-open me-1 text-primary"></i><?= esc($residente->getUnidadeCompleta()) ?>
                      </p>
                    </td>

                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-dark">
                        <i class="far fa-envelope me-1 text-secondary"></i><?= esc($residente->email) ?>
                      </p>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fas fa-phone-alt me-1"></i><?= esc($residente->getTelefoneFormatado()) ?>
                      </p>
                    </td>

                    <td class="align-middle text-center text-sm">
                      <?php if ($residente->user_id): ?>
                        <span class="badge badge-sm <?= ($residente->user_active) ? 'bg-gradient-info' : 'bg-gradient-secondary' ?>" title="<?= esc($residente->username) ?>">
                          <i class="fas fa-user-shield me-1"></i>
                          <?= esc($residente->username) ?>
                          <?= ($residente->user_active) ? '' : ' (Inativo)' ?>
                        </span>
                      <?php else: ?>
                        <span class="badge badge-sm bg-light text-secondary border">
                          <i class="fas fa-user-slash me-1"></i> Sem acesso
                        </span>
                      <?php endif; ?>
                    </td>

                    <td class="align-middle text-center text-sm">
                      <?= $residente->getStatusBadge() ?>
                    </td>

                    <td class="align-middle text-center">
                      <div class="d-inline-flex gap-1">
                        <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="btn btn-link text-info text-gradient px-2 mb-0" title="Ver Detalhes">
                          <i class="fas fa-eye text-sm"></i>
                        </a>
                        <a href="<?= route_to('residentes.editar', $residente->id) ?>" class="btn btn-link text-dark px-2 mb-0" title="Editar Residente">
                          <i class="fas fa-pencil-alt text-sm"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginação Soft UI (Item 7) -->
        <?php if (! empty($residentes) && $pager->getPageCount('residentes') > 1): ?>
          <div class="card-footer py-3 d-flex justify-content-center">
            <?= $pager->links('residentes', 'soft_ui') ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
