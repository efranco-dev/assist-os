<?php
require('conexao.php');
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
        FROM ordens_servico os
        JOIN clientes c ON c.id = os.cliente_id";

$params = [];
if ($busca) {
    $sql .= " WHERE c.nome LIKE :busca OR os.aparelho LIKE :busca2 OR os.status LIKE :busca3";
    $params = [':busca' => "%$busca%", ':busca2' => "%$busca%", ':busca3' => "%$busca%"];
}
$sql .= " ORDER BY os.data_entrada DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Ordem de Serviço - Assist-OS</title>
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
      <h5 class="fw-semibold mb-0">Ordens de Serviço</h5>
      <a href="os-nova.php" class="btn btn-sm text-white d-flex align-items-center gap-1" style="background:var(--brand-color)">
        <i class="bi bi-plus-lg"></i> Nova OS
      </a>
    </div>

    <form method="GET" class="mb-3">
      <div class="input-group input-group-sm">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por cliente, aparelho ou status..." value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        <?php if ($busca): ?>
          <a href="?" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        <?php endif; ?>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Aparelho</th>
            <th>Marca</th>
            <th>Status</th>
            <th>Valor</th>
            <th>Data</th>
            <th style="width:140px">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($ordens)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-inbox"></i> Nenhuma ordem de serviço encontrada.</td></tr>
          <?php else: ?>
            <?php foreach ($ordens as $o): ?>
              <tr>
                <td class="text-muted"><?= $o['id'] ?></td>
                <td class="fw-medium"><?= htmlspecialchars($o['cliente_nome']) ?></td>
                <td><?= htmlspecialchars($o['aparelho']) ?></td>
                <td><?= htmlspecialchars($o['marca']) ?></td>
                <td>
                  <span class="badge rounded-pill
                    <?= match($o['status']) {
                      'Pronto' => 'bg-success',
                      'Orçamento', 'Aguardando Autorização', 'Aguardando Peças' => 'bg-warning text-dark',
                      'Recusado' => 'bg-danger',
                      'Autorizado' => 'bg-info text-dark',
                      default => 'bg-secondary'
                    } ?>">
                    <?= htmlspecialchars($o['status'] ?: '—') ?>
                  </span>
                </td>
                <td><?= $o['valor_total'] ? 'R$ '.number_format($o['valor_total'], 2, ',', '.') : '—' ?></td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($o['data_entrada'])) ?></td>
                <td>
                  <a href="visualizar.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                  <a href="editar.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-os" title="Excluir"
                    data-id="<?= $o['id'] ?>" data-label="OS #<?= $o['id'] ?>">
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Confirmar Exclusão</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="delete-message">Tem certeza que deseja excluir?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="delete-confirm-btn" class="btn btn-danger btn-sm">Excluir</a>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script>
    document.querySelectorAll('.delete-os').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var label = this.dataset.label;
        document.getElementById('delete-message').innerHTML =
          'Excluir ' + label + '?';
        document.getElementById('delete-confirm-btn').href =
          'deletar.php?id=' + id + '&tabela=ordens_servico';
        new bootstrap.Modal(document.getElementById('modalConfirmarExclusao')).show();
      });
    });
  </script>
</body>
</html>
