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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('residentes.index') ?>">Residentes</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('residentes.detalhes', $residente->id) ?>"><?= esc($residente->nome) ?></a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Editar Residente</h4>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Cancelar e Voltar
        </a>
      </div>
    </div>

    <!-- Formulário de Edição (Item 10) -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom">
        <h6 class="mb-0 font-weight-bold">Dados do Morador e Localização</h6>
        <p class="text-xs text-secondary mb-3">Preencha os campos abaixo para atualizar o cadastro do residente.</p>
      </div>
      <div class="card-body p-4">
        <form method="post" action="<?= route_to('residentes.atualizar', $residente->id) ?>" autocomplete="off">
          <?= csrf_field() ?>

          <!-- Seção 1: Dados Pessoais -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-user me-1 text-primary"></i> 1. Identificação Pessoal
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
              <label class="form-label text-sm font-weight-bold">Nome Completo <span class="text-danger">*</span></label>
              <input type="text" name="nome" class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>" value="<?= esc(old('nome', $residente->nome)) ?>" placeholder="Ex.: João da Silva" required>
              <?php if (isset($errors['nome'])): ?>
                <div class="invalid-feedback"><?= esc($errors['nome']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">CPF <span class="text-danger">*</span></label>
              <input type="text" name="cpf" class="form-control <?= isset($errors['cpf']) ? 'is-invalid' : '' ?>" value="<?= esc(old('cpf', $residente->getCpfFormatado())) ?>" placeholder="000.000.000-00" required>
              <?php if (isset($errors['cpf'])): ?>
                <div class="invalid-feedback"><?= esc($errors['cpf']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 2: Contatos -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-phone me-1 text-primary"></i> 2. Canais de Contato
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-7">
              <label class="form-label text-sm font-weight-bold">E-mail <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= esc(old('email', $residente->email)) ?>" placeholder="joao@exemplo.com" required>
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Utilizado para comunicações do condomínio e credenciais do Shield.</span>
            </div>

            <div class="col-12 col-md-5">
              <label class="form-label text-sm font-weight-bold">Telefone / WhatsApp</label>
              <input type="text" name="telefone" class="form-control <?= isset($errors['telefone']) ? 'is-invalid' : '' ?>" value="<?= esc(old('telefone', $residente->getTelefoneFormatado())) ?>" placeholder="(00) 00000-0000">
              <?php if (isset($errors['telefone'])): ?>
                <div class="invalid-feedback"><?= esc($errors['telefone']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 3: Localização da Unidade -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-home me-1 text-primary"></i> 3. Localização no Condomínio
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Apartamento / Unidade <span class="text-danger">*</span></label>
              <input type="text" name="unidade" class="form-control <?= isset($errors['unidade']) ? 'is-invalid' : '' ?>" value="<?= esc(old('unidade', $residente->unidade)) ?>" placeholder="Ex.: 102, 34B" required>
              <?php if (isset($errors['unidade'])): ?>
                <div class="invalid-feedback"><?= esc($errors['unidade']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-6 col-md-4">
              <label class="form-label text-sm font-weight-bold">Bloco</label>
              <input type="text" name="bloco" class="form-control <?= isset($errors['bloco']) ? 'is-invalid' : '' ?>" value="<?= esc(old('bloco', $residente->bloco)) ?>" placeholder="Ex.: A, 01">
              <?php if (isset($errors['bloco'])): ?>
                <div class="invalid-feedback"><?= esc($errors['bloco']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-6 col-md-4">
              <label class="form-label text-sm font-weight-bold">Torre</label>
              <input type="text" name="torre" class="form-control <?= isset($errors['torre']) ? 'is-invalid' : '' ?>" value="<?= esc(old('torre', $residente->torre)) ?>" placeholder="Ex.: Sul, 02">
              <?php if (isset($errors['torre'])): ?>
                <div class="invalid-feedback"><?= esc($errors['torre']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 4: Status do Cadastro -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-toggle-on me-1 text-primary"></i> 4. Status e Permissões
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">Status do Residente</label>
              <select name="ativo" class="form-select <?= isset($errors['ativo']) ? 'is-invalid' : '' ?>">
                <option value="1" <?= old('ativo', (string) $residente->ativo) === '1' ? 'selected' : '' ?>>Ativo (Acesso normal)</option>
                <option value="0" <?= old('ativo', (string) $residente->ativo) === '0' ? 'selected' : '' ?>>Bloqueado (Acesso suspenso)</option>
              </select>
              <?php if (isset($errors['ativo'])): ?>
                <div class="invalid-feedback"><?= esc($errors['ativo']) ?></div>
              <?php endif; ?>
            </div>

            <?php if ($residente->user_id): ?>
              <div class="col-12 col-md-6">
                <div class="bg-light p-3 border-radius-md border">
                  <span class="text-xs text-secondary font-weight-bold d-block">
                    <i class="fas fa-link me-1 text-info"></i> Usuário Shield Vinculado
                  </span>
                  <span class="text-sm font-weight-bold text-dark"><?= esc($residente->username) ?> (ID #<?= (int) $residente->user_id ?>)</span>
                  <p class="text-xxs text-secondary mb-0 mt-1">Alterações no e-mail atualizarão automaticamente os dados no módulo de autenticação.</p>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Botões de Ação -->
          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="btn btn-light mb-0">
              Cancelar
            </a>
            <button type="submit" class="btn bg-gradient-primary mb-0">
              <i class="fas fa-save me-1"></i> Salvar Alterações
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
