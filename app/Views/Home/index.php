<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Banner de Boas-Vindas & Ações Rápidas -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card bg-gradient-dark shadow-lg border-0 overflow-hidden position-relative">
      <div class="card-body p-4 position-relative z-index-1">
        <div class="row align-items-center">
          <div class="col-12 col-lg-8">
            <span class="badge bg-gradient-primary mb-2 text-uppercase font-weight-bold">Sistema de Gestão Condominial</span>
            <h3 class="text-white font-weight-bolder mb-1">Bem-vindo ao Painel do Condomínio</h3>
            <p class="text-white opacity-8 text-sm mb-3">
              Gerencie moradores, controle o uso dos espaços compartilhados e acompanhe reservas e receitas em tempo real.
            </p>
            <div class="d-flex flex-wrap gap-2">
              <a href="<?= route_to('residentes.novo') ?>" class="btn btn-sm bg-gradient-primary mb-0 shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Novo Residente
              </a>
              <a href="<?= route_to('areas.novo') ?>" class="btn btn-sm btn-white mb-0 shadow-sm">
                <i class="fas fa-plus me-1 text-primary"></i> Nova Área Comum
              </a>
              <a href="<?= route_to('areas.index') ?>" class="btn btn-sm btn-outline-white mb-0">
                <i class="fas fa-swimming-pool me-1"></i> Ver Áreas
              </a>
              <a href="<?= route_to('residentes.index') ?>" class="btn btn-sm btn-outline-white mb-0">
                <i class="fas fa-users me-1"></i> Lista de Moradores
              </a>
            </div>
          </div>
          <div class="col-12 col-lg-4 text-lg-end mt-4 mt-lg-0">
            <div class="d-inline-block text-start bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
              <div class="text-xs text-white opacity-8"><i class="far fa-clock me-1"></i> Status do Sistema</div>
              <div class="text-white font-weight-bold text-sm mb-1">Operação Normal</div>
              <div class="text-xxs text-white opacity-7">Portaria &bull; Reservas &bull; Moradores</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 4 Cards de Métricas Principais (KPIs) -->
<div class="row g-3 mb-4">
  <!-- Card 1: Residentes -->
  <div class="col-xl-3 col-sm-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-8">
            <div class="numbers">
              <p class="text-xs mb-0 text-uppercase font-weight-bold text-secondary">Total de Residentes</p>
              <h4 class="font-weight-bolder mb-0 text-dark">
                <?= esc($totalResidentes) ?>
              </h4>
              <span class="text-success text-xs font-weight-bolder">
                <i class="fas fa-check-circle me-1"></i><?= esc($residentesAtivos) ?> ativos
              </span>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle d-flex align-items-center justify-content-center ms-auto">
              <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
        <div class="mt-2 pt-2 border-top">
          <a href="<?= route_to('residentes.index') ?>" class="text-xs text-primary font-weight-bold d-flex justify-content-between align-items-center">
            <span>Gerenciar Moradores</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Áreas Comuns -->
  <div class="col-xl-3 col-sm-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-8">
            <div class="numbers">
              <p class="text-xs mb-0 text-uppercase font-weight-bold text-secondary">Áreas Comuns</p>
              <h4 class="font-weight-bolder mb-0 text-dark">
                <?= esc($totalAreas) ?>
              </h4>
              <span class="text-info text-xs font-weight-bolder">
                <i class="fas fa-door-open me-1"></i><?= esc($areasAtivas) ?> disponíveis
              </span>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle d-flex align-items-center justify-content-center ms-auto">
              <i class="fas fa-swimming-pool text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
        <div class="mt-2 pt-2 border-top">
          <a href="<?= route_to('areas.index') ?>" class="text-xs text-info font-weight-bold d-flex justify-content-between align-items-center">
            <span>Explorar Espaços</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3: Reservas -->
  <div class="col-xl-3 col-sm-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-8">
            <div class="numbers">
              <p class="text-xs mb-0 text-uppercase font-weight-bold text-secondary">Reservas de Espaços</p>
              <h4 class="font-weight-bolder mb-0 text-dark">
                <?= esc($totalReservas) ?>
              </h4>
              <span class="text-success text-xs font-weight-bolder">
                <i class="fas fa-calendar-check me-1"></i><?= esc($reservasConfirmadas) ?> confirmadas
              </span>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle d-flex align-items-center justify-content-center ms-auto">
              <i class="far fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
        <div class="mt-2 pt-2 border-top">
          <span class="text-xs text-secondary font-weight-bold">
            <?= esc($reservasPendentes) ?> pendente(s) de aprovação
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4: Arrecadação de Taxas -->
  <div class="col-xl-3 col-sm-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-8">
            <div class="numbers">
              <p class="text-xs mb-0 text-uppercase font-weight-bold text-secondary">Receita de Reservas</p>
              <h4 class="font-weight-bolder mb-0 text-dark">
                R$ <?= number_format($totalArrecadado, 2, ',', '.') ?>
              </h4>
              <span class="text-warning text-xs font-weight-bolder">
                <i class="fas fa-receipt me-1"></i>Taxas confirmadas
              </span>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center ms-auto">
              <i class="fas fa-hand-holding-usd text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
        <div class="mt-2 pt-2 border-top">
          <span class="text-xs text-secondary font-weight-bold">
            Taxas de uso condominial
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Grade Central de Conteúdo -->
<div class="row g-4 mb-4">
  <!-- Coluna da Esquerda: Áreas e Próximas Reservas -->
  <div class="col-12 col-lg-8">
    <!-- Bloco 1: Áreas Comuns em Destaque -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-0 font-weight-bold text-dark">Espaços e Áreas Comuns</h6>
          <p class="text-xs text-secondary mb-3">Disponibilidade e capacidade das instalações sociais do condomínio.</p>
        </div>
        <a href="<?= route_to('areas.index') ?>" class="btn btn-outline-primary btn-sm mb-3">
          Ver Todas (<?= esc($totalAreas) ?>)
        </a>
      </div>
      <div class="card-body p-3">
        <?php if (empty($areasDestaque)): ?>
          <div class="text-center py-4">
            <i class="fas fa-swimming-pool text-secondary fa-2x mb-2"></i>
            <p class="text-sm text-secondary mb-2">Nenhuma área comum cadastrada.</p>
            <a href="<?= route_to('areas.novo') ?>" class="btn btn-sm bg-gradient-primary">
              <i class="fas fa-plus me-1"></i> Cadastrar Primeira Área
            </a>
          </div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($areasDestaque as $area): ?>
              <div class="col-12 col-md-6">
                <div class="border border-radius-md p-3 h-100 hover-shadow transition-all bg-white position-relative">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-sm font-weight-bold text-truncate" title="<?= esc($area->nome) ?>">
                      <?= esc($area->nome) ?>
                    </h6>
                    <?= $area->getStatusBadge() ?>
                  </div>
                  <p class="text-xs text-secondary mb-2" style="min-height: 32px;">
                    <?= esc(character_limiter($area->descricao ?: 'Área compartilhada do condomínio.', 65)) ?>
                  </p>
                  <div class="d-flex justify-content-between text-xxs text-secondary pt-2 border-top">
                    <span><i class="fas fa-users me-1 text-primary"></i> <?= esc($area->getCapacidadeFormatada()) ?></span>
                    <span><i class="far fa-clock me-1 text-info"></i> <?= esc($area->getHorarioFuncionamento()) ?></span>
                    <span class="font-weight-bold text-dark"><i class="fas fa-tag me-1 text-success"></i> <?= esc($area->getTaxaFormatada()) ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Bloco 2: Próximas Reservas Agendadas -->
    <div class="card shadow-sm border-0">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-0 font-weight-bold text-dark">Agendamentos Recentes & Reservas</h6>
          <p class="text-xs text-secondary mb-3">Histórico recente de reservas solicitadas pelos moradores.</p>
        </div>
      </div>
      <div class="card-body p-0">
        <?php if (empty($proximasReservas)): ?>
          <div class="text-center py-5">
            <i class="far fa-calendar-times text-secondary fa-2x mb-2"></i>
            <p class="text-sm text-secondary mb-0">Nenhuma reserva registrada até o momento.</p>
            <p class="text-xs text-muted">As reservas solicitadas pelos residentes aparecerão listadas aqui.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Área Comum</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Morador / Unidade</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data & Horário</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Taxa</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($proximasReservas as $res): ?>
                  <tr>
                    <td>
                      <div class="d-flex px-3 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs font-weight-bold text-dark"><?= esc($res->area_nome ?? 'Área') ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-dark"><?= esc($res->residente_nome ?? '-') ?></p>
                      <p class="text-xxs text-secondary mb-0">Apt: <?= esc($res->unidade ?? '-') ?><?= ! empty($res->bloco) ? ' | Bloco ' . esc($res->bloco) : '' ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-dark d-block"><?= esc($res->getDataFormatada()) ?></span>
                      <span class="text-xxs text-secondary"><?= esc($res->getHorarioFormatado()) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold <?= $res->valor_taxa > 0 ? 'text-success' : 'text-secondary' ?>">
                        <?= esc($res->getTaxaFormatada()) ?>
                      </span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <?= $res->getStatusBadge() ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Coluna da Direita: Moradores e Avisos -->
  <div class="col-12 col-lg-4">
    <!-- Bloco 3: Últimos Residentes Cadastrados -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-0 font-weight-bold text-dark">Novos Moradores</h6>
          <p class="text-xs text-secondary mb-3">Últimos residentes cadastrados.</p>
        </div>
        <a href="<?= route_to('residentes.index') ?>" class="text-xs text-primary font-weight-bold">
          Ver todos
        </a>
      </div>
      <div class="card-body p-3">
        <?php if (empty($ultimosResidentes)): ?>
          <div class="text-center py-4">
            <i class="fas fa-users text-secondary fa-2x mb-2"></i>
            <p class="text-xs text-secondary mb-0">Nenhum residente cadastrado.</p>
          </div>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($ultimosResidentes as $residente): ?>
              <li class="list-group-item border-0 d-flex justify-content-between align-items-center px-0 py-2">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm bg-gradient-primary rounded-circle me-3 d-flex align-items-center justify-content-center text-white font-weight-bold text-xs">
                    <?= esc(mb_substr($residente->nome, 0, 2)) ?>
                  </div>
                  <div class="d-flex flex-column">
                    <h6 class="mb-0 text-xs font-weight-bold text-dark"><?= esc($residente->nome) ?></h6>
                    <span class="text-xxs text-secondary">
                      <i class="fas fa-home me-1"></i><?= esc($residente->getUnidadeCompleta()) ?>
                    </span>
                  </div>
                </div>
                <div>
                  <?= $residente->getStatusBadge() ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- Bloco 4: Informativos e Normas do Condomínio -->
    <div class="card shadow-sm border-0 mb-4 bg-gray-100">
      <div class="card-body p-3">
        <h6 class="text-sm font-weight-bold text-dark mb-2">
          <i class="fas fa-bullhorn text-primary me-2"></i>Normas e Avisos do Condomínio
        </h6>
        <div class="d-flex align-items-start mb-3 pt-2 border-top">
          <i class="fas fa-volume-mute text-warning mt-1 me-2"></i>
          <div>
            <span class="text-xs font-weight-bold text-dark d-block">Horário de Silêncio</span>
            <span class="text-xxs text-secondary">Entre 22:00 e 08:00 é proibido ruídos sonoros excessivos nas áreas comuns e apartamentos.</span>
          </div>
        </div>

        <div class="d-flex align-items-start mb-3">
          <i class="far fa-calendar-times text-danger mt-1 me-2"></i>
          <div>
            <span class="text-xs font-weight-bold text-dark d-block">Cancelamento de Reservas</span>
            <span class="text-xxs text-secondary">Cancelamentos devem ser efetuados com até 24 horas de antecedência pelo sistema.</span>
          </div>
        </div>

        <div class="d-flex align-items-start mb-2">
          <i class="fas fa-shield-alt text-success mt-1 me-2"></i>
          <div>
            <span class="text-xs font-weight-bold text-dark d-block">Identificação e Portaria</span>
            <span class="text-xxs text-secondary">Mantenha os cadastros de moradores e convidados atualizados para agilidade na portaria.</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bloco 5: Contatos de Emergência -->
    <div class="card shadow-sm border-0">
      <div class="card-body p-3">
        <h6 class="text-sm font-weight-bold text-dark mb-2">
          <i class="fas fa-phone-alt text-info me-2"></i>Canais Administrativos
        </h6>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom text-xs">
          <span class="text-secondary"><i class="fas fa-building me-1"></i> Administração:</span>
          <span class="font-weight-bold text-dark">(11) 3344-5500</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom text-xs">
          <span class="text-secondary"><i class="fas fa-door-closed me-1"></i> Portaria 24 Horas:</span>
          <span class="font-weight-bold text-dark">Ramal 94</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2 text-xs">
          <span class="text-secondary"><i class="fas fa-envelope me-1"></i> E-mail Síndico:</span>
          <span class="font-weight-bold text-primary">sindico@condominio.com</span>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>