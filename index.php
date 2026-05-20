<?php
require('conexao.php');

$sql = "SELECT * FROM `cadastro`";
$statement = $pdo->query($sql);
$result = $statement->fetchAll((PDO::FETCH_ASSOC));

?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Cadastros</title>
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
    <div class="card my-4 shadow-sm">
      <div class="card-body">
        <h2 class="h5 mb-3">Cadastro de Serviço</h2>
        <form action="cadastrar.php" method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="nome">Nome</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="nome" autocomplete="off" class="form-control" type="text" name="nome"style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="endereco">Endereço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <input id="endereco" autocomplete="off" class="form-control" type="text" name="endereco"style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="telefone">Telefone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input id="telefone" autocomplete="off" class="form-control" type="text" name="telefone" maxlength="15"
                  oninput="maskPhone(event)">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="aparelho">Aparelho</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <input id="aparelho" autocomplete="off" class="form-control" type="text" name="aparelho"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="marca">Marca</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                <input id="marca" autocomplete="off" class="form-control" type="text" name="marca"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="modelo">Modelo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                <input id="modelo" autocomplete="off" class="form-control" type="text" name="modelo"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="defeito">Defeito</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-exclamation-triangle-fill"></i></span>
                <input id="defeito" autocomplete="off" class="form-control" type="text" name="defeito" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="servico">Serviço Executado
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-gear"></i></span>
                <input id="servico" autocomplete="off" class="form-control" type="text" name="servico"style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes"
                  rows="2"></textarea>
              </div>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-person-fill-add"></i>
              Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
    <hr>
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
  </main>
  <footer>

  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script>
    function maskPhone(e) {
      var v = e.target.value.replace(/\D/g, '');
      if (v.length > 10) {
        v = v.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      } else if (v.length > 5) {
        v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else if (v.length > 2) {
        v = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      }
      e.target.value = v;
    }
  </script>
</body>

</html>