<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <title>Ordens de Serviço</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <!-- Barra da marca -->
        <div class="container d-flex align-items-center gap-3 py-3">
            <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
                style="width:48px; height:48px; background:#1a3a5c; flex-shrink:0;">
                <i class="bi bi-tools fs-4"></i>
            </div>
            <div>
                <h1 class="h5 mb-0 fw-semibold">Assist-OS</h1>
                <small class="text-muted fs-6">Assistência Técnica — Conserto de Microondas e TV em Geral - Rua 10
                    chácara 61 lote 9 loja 4 - Vicente Pires - DF</small>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="lista-clientes.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                    <i class="bi bi-people-fill"></i> Clientes
                </a>
                <a href="index.php" class="btn btn-sm text-white d-flex align-items-center gap-1"
                    style="background:#1a3a5c;">
                    <i class="bi bi-person-fill-add"></i> Novo Cadastro
                </a>
            </div>
        </div>
        <!-- Navegação -->
        <div class="border-top bg-light">
            <div class="container mb-3">
                <nav class="nav">
                    <a href="index.php" class="nav-link active d-flex align-items-center gap-1 fw-medium"
                        style="border-bottom: 2px solid #1a3a5c; color:#1a3a5c;">
                        <i class="bi bi-clipboard2-fill"></i> Cadastro de Clientes e Serviço
                    </a>
                    <a href="ordem-servico.php" class="nav-link text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-clock-history"></i> Ordens de Serviço
                        <span class="badge rounded-pill text-white ms-1"
                            style="background:#1a3a5c; font-size:10px;">12</span>
                    </a>
                    <a href="relatorios.php" class="nav-link text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-bar-chart-fill"></i> Relatórios
                    </a>
                </nav>
            </div>
        </div>
    </header>
    <main class="container">
        <h1 class="h4 mb-3 fw-semibold">Ordens de Serviço</h1>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>