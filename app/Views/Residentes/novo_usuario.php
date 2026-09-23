<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 col-lg-8 mx-auto">
    <!-- Breadcrumb e Topo -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-0 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('residentes.index') ?>">Residentes</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('residentes.detalhes', $residente->id) ?>"><?= esc($residente->nome) ?></a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Criar Usuário</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">Gerar Usuário de Acesso (Shield)</h4>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
      </div>
    </div>

    <!-- Card de Criação do Usuário (Item 14) -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header pb-0 border-bottom">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar avatar-md bg-gradient-info rounded-circle me-3 d-flex align-items-center justify-content-center text-white">
            <i class="fas fa-user-shield"></i>
          </div>
          <div>
            <h6 class="mb-0 font-weight-bold">Acesso ao Portal para <?= esc($residente->nome) ?></h6>
            <p class="text-xs text-secondary mb-0">Unidade: <?= esc($residente->getUnidadeCompleta()) ?></p>
          </div>
        </div>
      </div>

      <div class="card-body p-4">
        <form method="post" action="<?= route_to('residentes.criarUsuario', $residente->id) ?>" autocomplete="off">
          <?= csrf_field() ?>

          <div class="alert alert-light border mb-4" role="alert">
            <div class="d-flex align-items-center">
              <i class="fas fa-info-circle text-info me-2 fs-5"></i>
              <span class="text-xs text-dark">
                O usuário criado será associado automaticamente à role <strong>Residente</strong> com a flag <strong>primeiro_acesso = 1</strong> para exigir a redefinição da senha no primeiro login.
              </span>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">Nome de Usuário (Username) <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= esc(old('username', $sugestaoUsername)) ?>" required>
              <?php if (isset($errors['username'])): ?>
                <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Identificador único utilizado para login (ex.: nome.sobrenome).</span>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label text-sm font-weight-bold">E-mail de Login <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= esc(old('email', $residente->email)) ?>" required>
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">E-mail associado ao cadastro do morador.</span>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
              <label class="form-label text-sm font-weight-bold">Senha Temporária / Provisória</label>
              <div class="input-group">
                <input type="text" id="senhaInput" name="password" class="form-control font-monospace <?= isset($errors['password']) ? 'is-invalid' : '' ?>" value="<?= esc(old('password', $senhaSugerida)) ?>">
                <button type="button" class="btn btn-outline-secondary mb-0" onclick="gerarNovaSenha()" title="Gerar nova senha">
                  <i class="fas fa-sync-alt"></i> Gerar
                </button>
              </div>
              <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback d-block"><?= esc($errors['password']) ?></div>
              <?php endif; ?>
              <span class="text-xxs text-secondary">Deixe em branco para usar uma senha temporária gerada automaticamente pelo sistema.</span>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label text-sm font-weight-bold">Grupo / Perfil</label>
              <input type="text" class="form-control bg-light" value="Residente" readonly>
              <span class="text-xxs text-secondary">Definido pelas políticas do condomínio.</span>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="<?= route_to('residentes.detalhes', $residente->id) ?>" class="btn btn-light mb-0">
              Cancelar
            </a>
            <button type="submit" class="btn bg-gradient-info mb-0">
              <i class="fas fa-check-circle me-1"></i> Criar Usuário e Vincular
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function gerarNovaSenha() {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*';
  let senha = '';
  for (let i = 0; i < 10; i++) {
    senha += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  document.getElementById('senhaInput').value = senha;
}
</script>
<?= $this->endSection() ?>
