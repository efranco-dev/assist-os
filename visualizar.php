<?php
require('conexao.php');

$cliente_id = filter_input(INPUT_GET, 'cliente_id', FILTER_VALIDATE_INT);
$os_id      = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($cliente_id) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->execute([':id' => $cliente_id]);
    $cliente = $stmt->fetch();
    if (!$cliente) { header('Location: clientes.php'); exit(); }

    $ordens = $pdo->prepare("SELECT * FROM ordens_servico WHERE cliente_id = :id ORDER BY data_entrada DESC");
    $ordens->execute([':id' => $cliente_id]);
    $ordens_list = $ordens->fetchAll();
    ?>
    <!doctype html>
    <html lang="pt-BR" data-bs-theme="light">
    <head>
      <title><?= htmlspecialchars($cliente['nome']) ?> - Assist-OS</title>
      <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
      <link href="css/bootstrap.min.css" rel="stylesheet" />
      <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
      <?php require('header.php'); ?>
      <main class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-semibold mb-0"><?= htmlspecialchars($cliente['nome']) ?></h5>
          <a href="clientes.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
        </div>
        <div class="card shadow-sm border mb-3">
          <div class="card-header bg-transparent fw-semibold small py-2">Dados do Cliente</div>
          <div class="card-body">
            <table class="table table-sm mb-0">
              <tr><td class="text-muted small" style="width:100px">Nome</td><td class="fw-medium"><?= htmlspecialchars($cliente['nome']) ?></td></tr>
              <tr><td class="text-muted small">Endereço</td><td><?= htmlspecialchars($cliente['endereco']) ?></td></tr>
              <tr><td class="text-muted small">Bairro</td><td><?= htmlspecialchars($cliente['bairro']) ?></td></tr>
              <tr><td class="text-muted small">Telefone</td><td><?= htmlspecialchars($cliente['telefone']) ?></td></tr>
              <tr><td class="text-muted small">Cliente desde</td><td><?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></td></tr>
            </table>
          </div>
        </div>
        <h6 class="fw-semibold mb-2">Ordens de Serviço</h6>
        <?php if (empty($ordens_list)): ?>
          <p class="text-muted small"><i class="bi bi-inbox"></i> Nenhuma OS para este cliente.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
              <thead class="table-light">
                <tr><th>#</th><th>Aparelho</th><th>Status</th><th>Valor</th><th>Data</th><th>Ações</th></tr>
              </thead>
              <tbody>
                <?php foreach ($ordens_list as $o): ?>
                  <tr>
                    <td class="text-muted"><?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['aparelho']) ?></td>
                    <td><span class="badge rounded-pill bg-secondary"><?= htmlspecialchars($o['status'] ?: '—') ?></span></td>
                    <td><?= $o['valor_total'] ? 'R$ '.number_format($o['valor_total'], 2, ',', '.') : '—' ?></td>
                    <td class="small text-muted"><?= date('d/m/Y', strtotime($o['data_entrada'])) ?></td>
                    <td>
                      <a href="visualizar.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                      <a href="editar.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </main>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/theme.js"></script>
    </body>
    </html>
<?php
} elseif ($os_id) {
    $stmt = $pdo->prepare("
        SELECT os.*, c.nome as cliente_nome, c.endereco as cliente_endereco,
               c.bairro as cliente_bairro, c.telefone as cliente_telefone
        FROM ordens_servico os
        JOIN clientes c ON c.id = os.cliente_id
        WHERE os.id = :id
    ");
    $stmt->execute([':id' => $os_id]);
    $os = $stmt->fetch();
    if (!$os) { header('Location: ordem-servico.php'); exit(); }
    ?>
    <!doctype html>
    <html lang="pt-BR" data-bs-theme="light">
    <head>
      <title>OS #<?= $os['id'] ?> - Assist-OS</title>
      <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
      <link href="css/bootstrap.min.css" rel="stylesheet" />
      <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
      <?php require('header.php'); ?>
      <main class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-semibold mb-0">OS #<?= $os['id'] ?></h5>
          <a href="ordem-servico.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card shadow-sm border h-100">
              <div class="card-header bg-transparent fw-semibold small py-2">Dados do Cliente</div>
              <div class="card-body">
                <table class="table table-sm mb-0">
                  <tr><td class="text-muted small" style="width:100px">Nome</td><td class="fw-medium"><?= htmlspecialchars($os['cliente_nome']) ?></td></tr>
                  <tr><td class="text-muted small">Endereço</td><td><?= htmlspecialchars($os['cliente_endereco']) ?></td></tr>
                  <tr><td class="text-muted small">Bairro</td><td><?= htmlspecialchars($os['cliente_bairro']) ?></td></tr>
                  <tr><td class="text-muted small">Telefone</td><td><?= htmlspecialchars($os['cliente_telefone']) ?></td></tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card shadow-sm border h-100">
              <div class="card-header bg-transparent fw-semibold small py-2">Dados do Serviço</div>
              <div class="card-body">
                <table class="table table-sm mb-0">
                  <tr><td class="text-muted small" style="width:110px">Aparelho</td><td><?= htmlspecialchars($os['aparelho']) ?></td></tr>
                  <tr><td class="text-muted small">Marca</td><td><?= htmlspecialchars($os['marca']) ?></td></tr>
                  <tr><td class="text-muted small">Modelo</td><td><?= htmlspecialchars($os['modelo']) ?></td></tr>
                  <tr><td class="text-muted small">Status</td><td><?= htmlspecialchars($os['status'] ?: '—') ?></td></tr>
                  <tr><td class="text-muted small">Defeito</td><td><?= nl2br(htmlspecialchars($os['defeito'])) ?></td></tr>
                  <tr><td class="text-muted small">Serviço</td><td><?= nl2br(htmlspecialchars($os['servico'])) ?></td></tr>
                  <tr><td class="text-muted small">Observações</td><td><?= nl2br(htmlspecialchars($os['observacoes'])) ?></td></tr>
                  <tr><td class="text-muted small">Valor Serviço</td><td>R$ <?= number_format($os['valor_servico'] ?? 0, 2, ',', '.') ?></td></tr>
                  <tr><td class="text-muted small">Desconto</td><td>R$ <?= number_format($os['desconto'] ?? 0, 2, ',', '.') ?></td></tr>
                  <tr><td class="text-muted small">Valor Total</td><td class="fw-bold">R$ <?= number_format($os['valor_total'] ?? 0, 2, ',', '.') ?></td></tr>
                  <tr><td class="text-muted small">Data</td><td><?= date('d/m/Y H:i', strtotime($os['data_entrada'])) ?></td></tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </main>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/theme.js"></script>
    </body>
    </html>
<?php
} else {
    header('Location: ordem-servico.php');
    exit();
}
