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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Novo Cadastro</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Cadastrar Novo Residente</h4>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('residentes.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
      </div>
    </div>

    <!-- Formulário de Cadastro (Item 13) -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom">
        <h6 class="mb-0 font-weight-bold">Dados do Morador e Localização</h6>
        <p class="text-xs text-secondary mb-3">Preencha as informações para registrar o morador no condomínio.</p>
      </div>
      <div class="card-body p-4">
        <form method="post" action="<?= route_to('residentes.criar') ?>" autocomplete="off">
          <?= csrf_field() ?>

          <!-- Seção 1: Dados Pessoais -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-user me-1 text-primary"></i> 1. Identificação Pessoal
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
              <label class="form-label text-sm font-weight-bold">Nome Completo <span class="text-danger">*</span></label>
              <input type="text" name="nome" class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>" value="<?= esc(old('nome')) ?>" placeholder="Ex.: Maria Souza de Oliveira" required>
              <?php if (isset($errors['nome'])): ?>
                <div class="invalid-feedback"><?= esc($errors['nome']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">CPF <span class="text-danger">*</span></label>
              <input type="text" name="cpf" class="form-control <?= isset($errors['cpf']) ? 'is-invalid' : '' ?>" value="<?= esc(old('cpf')) ?>" placeholder="000.000.000-00" required>
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
              <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= esc(old('email')) ?>" placeholder="maria@exemplo.com" required>
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Será utilizado para notificações do condomínio e usuário de login no Shield.</span>
            </div>

            <div class="col-12 col-md-5">
              <label class="form-label text-sm font-weight-bold">Telefone / WhatsApp</label>
              <input type="text" name="telefone" class="form-control <?= isset($errors['telefone']) ? 'is-invalid' : '' ?>" value="<?= esc(old('telefone')) ?>" placeholder="(00) 00000-0000">
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
              <input type="text" name="unidade" class="form-control <?= isset($errors['unidade']) ? 'is-invalid' : '' ?>" value="<?= esc(old('unidade')) ?>" placeholder="Ex.: 101, 202-B" required>
              <?php if (isset($errors['unidade'])): ?>
                <div class="invalid-feedback"><?= esc($errors['unidade']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-6 col-md-4">
              <label class="form-label text-sm font-weight-bold">Bloco</label>
              <input type="text" name="bloco" class="form-control <?= isset($errors['bloco']) ? 'is-invalid' : '' ?>" value="<?= esc(old('bloco')) ?>" placeholder="Ex.: A, 01">
              <?php if (isset($errors['bloco'])): ?>
                <div class="invalid-feedback"><?= esc($errors['bloco']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-6 col-md-4">
              <label class="form-label text-sm font-weight-bold">Torre</label>
              <input type="text" name="torre" class="form-control <?= isset($errors['torre']) ? 'is-invalid' : '' ?>" value="<?= esc(old('torre')) ?>" placeholder="Ex.: Norte, 01">
              <?php if (isset($errors['torre'])): ?>
                <div class="invalid-feedback"><?= esc($errors['torre']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Seção 4: Status e Acesso Shield -->
          <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
            <i class="fas fa-shield-alt me-1 text-primary"></i> 4. Status e Autenticação (Shield)
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">Status Inicial</label>
              <select name="ativo" class="form-select">
                <option value="1" <?= old('ativo', '1') === '1' ? 'selected' : '' ?>>Ativo (Liberado)</option>
                <option value="0" <?= old('ativo') === '0' ? 'selected' : '' ?>>Bloqueado</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-check form-switch mt-md-4 pt-md-2">
                <input class="form-check-input" type="checkbox" name="criar_usuario" id="criarUsuarioCheck" value="1" <?= old('criar_usuario', '1') ? 'checked' : '' ?>>
                <label class="form-check-label text-sm font-weight-bold" for="criarUsuarioCheck">
                  Criar automaticamente o usuário de acesso ao sistema (Shield)
                </label>
                <small class="text-xxs text-secondary d-block">Gera convite com senha temporária vinculada ao e-mail informado.</small>
              </div>
            </div>
          </div>

          <!-- Botões de Ação -->
          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= route_to('residentes.index') ?>" class="btn btn-light mb-0">
              Cancelar
            </a>
            <button type="submit" class="btn bg-gradient-primary mb-0">
              <i class="fas fa-check me-1"></i> Cadastrar Residente
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
