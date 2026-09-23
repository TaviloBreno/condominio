<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Breadcrumb e Botão Voltar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-0 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('residentes.index') ?>">Residentes</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page"><?= esc($residente->nome) ?></li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Ficha do Residente</h4>
      </div>
      <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="<?= route_to('residentes.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
        <a href="<?= route_to('residentes.editar', $residente->id) ?>" class="btn bg-gradient-primary btn-sm mb-0">
          <i class="fas fa-pencil-alt me-1"></i> Editar Dados
        </a>
      </div>
    </div>

    <!-- Header Perfil do Residente (Item 8) -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="avatar avatar-xl bg-gradient-primary rounded-circle text-white d-flex align-items-center justify-content-center font-weight-bold fs-3">
              <?= strtoupper(substr($residente->nome, 0, 1)) ?>
            </div>
          </div>
          <div class="col">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
              <h4 class="mb-0 font-weight-bolder"><?= esc($residente->nome) ?></h4>
              <?= $residente->getStatusBadge() ?>
              <?php if ($residente->user_id): ?>
                <span class="badge bg-gradient-info badge-sm">
                  <i class="fas fa-user-check me-1"></i> Acesso Liberado
                </span>
              <?php else: ?>
                <span class="badge bg-light text-secondary border badge-sm">
                  <i class="fas fa-user-times me-1"></i> Sem Usuário Shield
                </span>
              <?php endif; ?>
            </div>
            <p class="text-secondary text-sm mb-0">
              <i class="fas fa-building me-1 text-primary"></i> <?= esc($residente->getUnidadeCompleta()) ?>
              <span class="mx-2">•</span>
              <i class="far fa-id-card me-1"></i> CPF: <?= esc($residente->getCpfFormatado()) ?>
            </p>
          </div>
          <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <!-- Ações Rápidas (Item 9) -->
            <form method="post" action="<?= route_to('residentes.toggleStatus', $residente->id) ?>" class="d-inline">
              <?= csrf_field() ?>
              <?php if ($residente->ativo): ?>
                <button type="submit" class="btn btn-outline-danger btn-sm mb-0" onclick="return confirm('Deseja realmente bloquear este residente? O acesso ao sistema será suspenso.');">
                  <i class="fas fa-ban me-1"></i> Bloquear Residente
                </button>
              <?php else: ?>
                <button type="submit" class="btn btn-outline-success btn-sm mb-0">
                  <i class="fas fa-check-circle me-1"></i> Desbloquear Residente
                </button>
              <?php endif; ?>
            </form>

            <form method="post" action="<?= route_to('residentes.excluir', $residente->id) ?>" class="d-inline">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-link text-danger text-sm mb-0 p-2" onclick="return confirm('ATENÇÃO: Deseja realmente excluir este residente? O cadastro será desativado.');" title="Excluir cadastro">
                <i class="fas fa-trash-alt"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Coluna Esquerda: Dados Pessoais e Contatos (Item 8 e 9) -->
      <div class="col-12 col-lg-7 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-address-card me-2 text-primary"></i>Dados Cadastrais e Contato</h6>
            <a href="<?= route_to('residentes.editar', $residente->id) ?>" class="text-xs text-primary font-weight-bold">
              <i class="fas fa-edit me-1"></i>Editar
            </a>
          </div>
          <div class="card-body">
            <div class="row gy-3">
              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Nome Completo</span>
                <span class="text-sm font-weight-bold text-dark"><?= esc($residente->nome) ?></span>
              </div>

              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">CPF</span>
                <span class="text-sm font-weight-bold text-dark"><?= esc($residente->getCpfFormatado()) ?></span>
              </div>

              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">E-mail</span>
                <a href="mailto:<?= esc($residente->email) ?>" class="text-sm font-weight-bold text-info">
                  <i class="far fa-envelope me-1"></i><?= esc($residente->email) ?>
                </a>
              </div>

              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Telefone / WhatsApp</span>
                <?php if (! empty($residente->telefone)): ?>
                  <a href="https://wa.me/55<?= preg_replace('/\D/', '', $residente->telefone) ?>" target="_blank" class="text-sm font-weight-bold text-success">
                    <i class="fab fa-whatsapp me-1"></i><?= esc($residente->getTelefoneFormatado()) ?>
                  </a>
                <?php else: ?>
                  <span class="text-sm text-secondary">Não informado</span>
                <?php endif; ?>
              </div>

              <div class="col-12"><hr class="horizontal dark my-2"></div>

              <div class="col-12 col-sm-4">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Apartamento / Unidade</span>
                <span class="badge bg-light text-dark font-weight-bold fs-6">
                  <?= esc($residente->unidade) ?>
                </span>
              </div>

              <div class="col-12 col-sm-4">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Bloco</span>
                <span class="text-sm font-weight-bold text-dark"><?= esc($residente->bloco ?: 'Nenhum') ?></span>
              </div>

              <div class="col-12 col-sm-4">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Torre</span>
                <span class="text-sm font-weight-bold text-dark"><?= esc($residente->torre ?: 'Nenhuma') ?></span>
              </div>

              <div class="col-12"><hr class="horizontal dark my-2"></div>

              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Data de Registro</span>
                <span class="text-sm text-secondary">
                  <?= $residente->created_at ? $residente->created_at->format('d/m/Y \à\s H:i') : '-' ?>
                </span>
              </div>

              <div class="col-12 col-sm-6">
                <span class="text-xs text-secondary text-uppercase font-weight-bolder d-block">Última Atualização</span>
                <span class="text-sm text-secondary">
                  <?= $residente->updated_at ? $residente->updated_at->format('d/m/Y \à\s H:i') : '-' ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Coluna Direita: Usuário Shield e Moradores da Unidade (Item 9) -->
      <div class="col-12 col-lg-5 mb-4">
        <!-- Card Acesso Shield -->
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-shield-alt me-2 text-info"></i>Conta de Acesso (Shield)</h6>
            <?php if ($residente->user_id): ?>
              <span class="badge bg-gradient-dark badge-sm">Role: Residente</span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <?php if ($residente->user_id): ?>
              <div class="d-flex align-items-center mb-3">
                <div class="avatar avatar-md bg-gradient-info rounded-circle me-3 d-flex align-items-center justify-content-center text-white">
                  <i class="fas fa-user-lock"></i>
                </div>
                <div>
                  <h6 class="mb-0 font-weight-bold"><?= esc($residente->username) ?></h6>
                  <p class="text-xs text-secondary mb-0">ID de Usuário: #<?= (int) $residente->user_id ?></p>
                </div>
              </div>

              <div class="bg-gray-100 p-3 border-radius-md mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="text-xs font-weight-bold text-secondary">Status do Login:</span>
                  <?php if ($residente->user_active): ?>
                    <span class="badge badge-sm bg-gradient-success">Ativo / Liberado</span>
                  <?php else: ?>
                    <span class="badge badge-sm bg-gradient-danger">Suspenso / Bloqueado</span>
                  <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="text-xs font-weight-bold text-secondary">Primeiro Acesso:</span>
                  <span class="text-xs text-dark font-weight-bold">
                    <?= $residente->primeiro_acesso ? 'Pendente (Senha temporária)' : 'Realizado' ?>
                  </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-xs font-weight-bold text-secondary">Última Atividade:</span>
                  <span class="text-xs text-dark">
                    <?= $residente->last_active ? date('d/m/Y H:i', strtotime($residente->last_active)) : 'Nunca acessou' ?>
                  </span>
                </div>
              </div>

              <!-- Ação de Bloqueio/Liberação de Acesso (Item 16 / Item 9) -->
              <form method="post" action="<?= route_to('residentes.toggleAcessoUsuario', $residente->id) ?>">
                <?= csrf_field() ?>
                <?php if ($residente->user_active): ?>
                  <button type="submit" class="btn btn-outline-danger btn-sm w-100 mb-0" onclick="return confirm('Deseja suspender as credenciais de login deste residente?');">
                    <i class="fas fa-user-slash me-1"></i> Bloquear Acesso ao Portal
                  </button>
                <?php else: ?>
                  <button type="submit" class="btn bg-gradient-success btn-sm w-100 mb-0">
                    <i class="fas fa-user-check me-1"></i> Liberar Acesso ao Portal
                  </button>
                <?php endif; ?>
              </form>

            <?php else: ?>
              <div class="text-center py-3">
                <i class="fas fa-key text-warning fa-2x mb-2"></i>
                <h6 class="font-weight-bold text-sm mb-1">Nenhum Usuário Vinculado</h6>
                <p class="text-xs text-secondary mb-3">
                  Este morador ainda não possui credenciais para login no aplicativo ou portal do condomínio.
                </p>
                <a href="<?= route_to('residentes.novoUsuario', $residente->id) ?>" class="btn bg-gradient-info btn-sm mb-0 w-100">
                  <i class="fas fa-user-plus me-1"></i> Configurar e Gerar Acesso Shield
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Card Outros Moradores na Mesma Unidade -->
        <div class="card shadow-sm border-0">
          <div class="card-header pb-0">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-users me-2 text-dark"></i>Outros Residentes na Unidade</h6>
          </div>
          <div class="card-body p-3">
            <?php if (empty($outrosMoradores)): ?>
              <p class="text-xs text-muted mb-0 py-2">
                Nenhum outro residente registrado na unidade <?= esc($residente->unidade) ?>.
              </p>
            <?php else: ?>
              <ul class="list-group list-group-flush">
                <?php foreach ($outrosMoradores as $outro): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <div>
                      <h6 class="text-sm font-weight-bold mb-0">
                        <a href="<?= route_to('residentes.detalhes', $outro->id) ?>" class="text-dark">
                          <?= esc($outro->nome) ?>
                        </a>
                      </h6>
                      <span class="text-xs text-secondary"><?= esc($outro->email) ?></span>
                    </div>
                    <?= $outro->getStatusBadge() ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
