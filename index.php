<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
    <head>
        <title>Cadastros</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
           
        </header>
        <main class="container">
            <form class="form d-flex gap-3 mt-3" action="cadastrar.php" method="post">
                <input autocomplete="off" placeholder="Nome" class="form-control" type="text" name="nome">
                <input autocomplete="off" placeholder="Sobrenome" class="form-control" type="text" name="sobrenome">
                <input autocomplete="off" placeholder="Data" class="form-control" type="date" name="datanasc">
                <input type="submit" value="Cadastrar" class="btn btn-sm btn-success">
            </form>
        </main>
        <footer>
           
        </footer>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
