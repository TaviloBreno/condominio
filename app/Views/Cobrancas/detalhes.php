<?= $this->extend('Layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 col-lg-10 mx-auto">
    <!-- Breadcrumb e Ações de Topo -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-0 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= route_to('cobrancas.index') ?>">Cobranças</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Extrato / Fatura</li>
          </ol>
        </nav>
        <h4 class="font-weight-bolder mb-0 text-dark">
          Fatura #<?= str_pad((string) $cobranca->id, 5, '0', STR_PAD_LEFT) ?>
        </h4>
      </div>
      <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= route_to('cobrancas.index') ?>" class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
        <button type="button" class="btn btn-outline-primary btn-sm mb-0" onclick="window.print();">
          <i class="fas fa-print me-1"></i> Imprimir Fatura
        </button>
      </div>
    </div>

    <!-- Card Principal da Fatura / Extrato -->
    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
      <!-- Cabeçalho da Fatura -->
      <div class="card-header bg-gradient-dark p-4 text-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
          <div>
            <span class="badge bg-white text-dark text-xxs mb-2 font-weight-bold">CONDOMÍNIO RESIDENCIAL</span>
            <h5 class="text-white font-weight-bolder mb-0">Extrato de Cobrança Condominial</h5>
            <p class="text-xs text-white opacity-8 mb-0">CNPJ: 00.000.000/0001-00 &bull; Administração e Portaria</p>
          </div>
          <div class="text-md-end mt-3 mt-md-0">
            <div class="mb-1"><?= $cobranca->getStatusBadge() ?></div>
            <span class="text-xs text-white opacity-8 d-block">Vencimento:</span>
            <h6 class="text-white font-weight-bolder mb-0"><?= esc($cobranca->getDataVencimentoFormatada()) ?></h6>
          </div>
        </div>
      </div>

      <div class="card-body p-4">
        <!-- Identificação das Partes -->
        <div class="row g-4 mb-4 pb-4 border-bottom">
          <div class="col-12 col-md-6">
            <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder mb-2">
              <i class="fas fa-user me-1 text-primary"></i> Sacado / Morador Responsável
            </h6>
            <h6 class="text-dark font-weight-bold mb-1"><?= esc($cobranca->residente_nome) ?></h6>
            <p class="text-xs text-secondary mb-1">
              <strong>Unidade:</strong> Apto <?= esc($cobranca->unidade) ?>
              <?= ! empty($cobranca->bloco) ? ' &bull; Bloco ' . esc($cobranca->bloco) : '' ?>
            </p>
            <p class="text-xs text-secondary mb-0">
              <strong>CPF:</strong> <?= esc($cobranca->cpf ?: 'Não informado') ?> &bull; <strong>Telefone:</strong> <?= esc($cobranca->telefone ?: '-') ?>
            </p>
          </div>

          <div class="col-12 col-md-6 text-md-end">
            <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder mb-2">
              <i class="fas fa-file-invoice me-1 text-info"></i> Dados da Fatura
            </h6>
            <p class="text-xs text-secondary mb-1">
              <strong>Número do Documento:</strong> #<?= str_pad((string) $cobranca->id, 5, '0', STR_PAD_LEFT) ?>
            </p>
            <p class="text-xs text-secondary mb-1">
              <strong>Data de Emissão:</strong> <?= date('d/m/Y', strtotime($cobranca->created_at)) ?>
            </p>
            <?php if (! empty($cobranca->data_pagamento)): ?>
              <p class="text-xs text-success font-weight-bold mb-0">
                <strong>Liquidado em:</strong> <?= esc($cobranca->getDataPagamentoFormatada()) ?> (<?= esc($cobranca->forma_pagamento ?: 'PIX') ?>)
              </p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Discriminação dos Serviços / Reserva -->
        <div class="table-responsive mb-4">
          <table class="table align-items-center mb-0">
            <thead class="bg-gray-100">
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Item / Descrição</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data de Utilização</th>
                <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-3">Valor</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="ps-3">
                  <h6 class="text-xs font-weight-bold mb-0 text-dark"><?= esc($cobranca->descricao) ?></h6>
                  <p class="text-xxs text-secondary mb-0">Locação e taxa de manutenção do espaço compartilhado.</p>
                </td>
                <td class="align-middle text-center text-xs font-weight-bold text-dark">
                  <?= ! empty($cobranca->data_reserva) ? date('d/m/Y', strtotime($cobranca->data_reserva)) : '-' ?>
                </td>
                <td class="align-middle text-end pe-3 font-weight-bold text-dark">
                  <?= esc($cobranca->getValorFormatado()) ?>
                </td>
              </tr>
              <tr class="bg-gray-100">
                <td colspan="2" class="text-end font-weight-bold text-dark text-xs ps-3">TOTAL DA FATURA:</td>
                <td class="text-end font-weight-bolder text-primary text-base pe-3">
                  <?= esc($cobranca->getValorFormatado()) ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Bloco de Pagamento PIX e Quitação -->
        <?php if ($cobranca->podePagar()): ?>
          <div class="card bg-gray-100 border-0 p-3 mb-4">
            <div class="row align-items-center">
              <div class="col-12 col-md-4 text-center border-end-md">
                <div class="bg-white p-3 d-inline-block rounded-3 shadow-sm border mb-2">
                  <!-- QR Code ilustrativo via SVG elegante -->
                  <svg width="130" height="130" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="8" height="8" rx="1.5" stroke="#344767" stroke-width="2"/>
                    <rect x="4.5" y="4.5" width="3" height="3" fill="#cb0c9f"/>
                    <rect x="14" y="2" width="8" height="8" rx="1.5" stroke="#344767" stroke-width="2"/>
                    <rect x="16.5" y="4.5" width="3" height="3" fill="#cb0c9f"/>
                    <rect x="2" y="14" width="8" height="8" rx="1.5" stroke="#344767" stroke-width="2"/>
                    <rect x="4.5" y="16.5" width="3" height="3" fill="#cb0c9f"/>
                    <path d="M14 14h2v2h-2zM18 14h4v2h-4zM14 18h4v4h-4zM20 18h2v4h-2z" fill="#344767"/>
                  </svg>
                </div>
                <div class="text-xxs font-weight-bold text-uppercase text-secondary">
                  <i class="fab fa-pix text-info me-1"></i> Pague Instantaneamente com PIX
                </div>
              </div>

              <div class="col-12 col-md-8 ps-md-4 mt-3 mt-md-0">
                <h6 class="text-xs font-weight-bold mb-1 text-dark">Código PIX Copia e Cola:</h6>
                <div class="input-group input-group-sm mb-3">
                  <input type="text" id="pixCode" class="form-control" value="<?= esc($cobranca->codigo_barras) ?>" readonly>
                  <button class="btn btn-outline-primary mb-0" type="button" onclick="navigator.clipboard.writeText(document.getElementById('pixCode').value); alert('Código PIX copiado!');">
                    <i class="fas fa-copy me-1"></i> Copiar Código
                  </button>
                </div>

                <div class="alert alert-warning text-white text-xs mb-3" role="alert">
                  <i class="fas fa-info-circle me-1"></i> Ao efetuar o pagamento, a baixa é processada automaticamente pelo sistema do condomínio.
                </div>

                <!-- Ação de Simulação de Liquidação -->
                <form method="post" action="<?= route_to('cobrancas.pagar', $cobranca->id) ?>" onsubmit="return confirm('Deseja confirmar a quitação desta cobrança via PIX?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="forma_pagamento" value="PIX">
                  <button type="submit" class="btn bg-gradient-success btn-sm mb-0">
                    <i class="fas fa-check-circle me-1"></i> Confirmar Pagamento da Fatura (PIX)
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php elseif ($cobranca->status === 'pago'): ?>
          <div class="alert alert-success text-white p-3 border-radius-md mb-4" role="alert">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle fa-2x me-3"></i>
              <div>
                <h6 class="text-white font-weight-bold mb-0">Fatura Liquidada com Sucesso</h6>
                <span class="text-xs">
                  Pagamento confirmado em <?= esc($cobranca->getDataPagamentoFormatada()) ?> via <?= esc($cobranca->forma_pagamento ?: 'PIX') ?>. Recibo válido como comprovante de quitação.
                </span>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-secondary text-white p-3 border-radius-md mb-4" role="alert">
            <i class="fas fa-ban me-2"></i> Esta fatura encontra-se <strong><?= esc($cobranca->status) ?></strong>.
          </div>
        <?php endif; ?>

        <!-- Observações e Regulamento -->
        <?php if (! empty($cobranca->observacoes)): ?>
          <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder mb-1">Observações do Documento:</h6>
          <p class="text-xs text-secondary mb-0"><?= nl2br(esc($cobranca->observacoes)) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
