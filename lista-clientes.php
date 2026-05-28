<?php
session_start();
require('conexao.php');

$sql = "SELECT * FROM `cadastro`";
$statement = $pdo->query($sql);
$result = $statement->fetchAll((PDO::FETCH_ASSOC));

?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Assist-OS</title>
  <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
  <header>
  </header>
  <main class="container">
    <?php if(isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
    <div id="mensagemSucesso" class="alert alert-success alert-dismissible fade show mt-3" role="alert" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
      <i class="bi bi-check-circle-fill"></i> Contato criado com sucesso!
    </div>
    <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>
    <div class="card my-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Editar cadastro</h2>
        <a class="btn btn-sm btn-danger" href="/assist-os"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
        <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Telefone</th>
          <th>Data de Entrada</th>
          <th>Opções</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row): ?>
          <tr>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['telefone'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['data_entrada'])) ?></td>
            <td class="d-flex justify-content-end gap-2">
              <a class="btn btn-sm btn-primary" href="visualizar.php?id=<?= $row['id'] ?>"><i class="bi bi-eye-fill"></i>
                Ver</a>
              <a class="btn btn-sm btn-warning" href="editar.php?id=<?= $row['id'] ?>"><i class="bi bi-pencil-square"></i>
                Editar</a>
              <a class="btn btn-sm btn-danger" href="deletar.php?id=<?= $row['id'] ?>"><i class="bi bi-trash3-fill"></i>
                Excluir</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
      </div>
    </div>
    
    
  </main>
  <footer>

  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script src="js/total-calculation.js"></script>
  <script src="js/mask-phone.js"></script>
  <script>
    // Fazer a mensagem de sucesso desaparecer após 4 segundos
    const mensagem = document.getElementById('mensagemSucesso');
    if (mensagem) {
      setTimeout(() => {
        mensagem.classList.remove('show');
        setTimeout(() => {
          mensagem.remove();
        }, 150);
      }, 4000);
    }
  </script>
</body>

</html>