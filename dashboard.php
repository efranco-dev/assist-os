<?php
session_start();
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}
require('conexao.php');

// ─── Stats ───
$total_clientes  = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$total_ordens    = $pdo->query("SELECT COUNT(*) FROM ordens_servico")->fetchColumn();
$total_este_mes  = $pdo->query("SELECT COUNT(*) FROM ordens_servico WHERE DATE_FORMAT(data_entrada, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')")->fetchColumn();
$total_pronto    = $pdo->query("SELECT COUNT(*) FROM ordens_servico WHERE status = 'Pronto'")->fetchColumn();

// ─── Chart data ───

// Status
$status_data = $pdo->query("SELECT COALESCE(NULLIF(status,''), 'Sem status') as label, COUNT(*) as qtd FROM ordens_servico GROUP BY label ORDER BY qtd DESC")->fetchAll(PDO::FETCH_ASSOC);
$status_labels = json_encode(array_column($status_data, 'label'));
$status_counts = json_encode(array_map('intval', array_column($status_data, 'qtd')));

// Monthly entries (last 12 months)
$meses = $pdo->query("
  SELECT DATE_FORMAT(data_entrada, '%Y-%m') as mes, COUNT(*) as qtd
  FROM ordens_servico WHERE data_entrada >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
  GROUP BY mes ORDER BY mes
")->fetchAll(PDO::FETCH_ASSOC);
$mes_labels = json_encode(array_column($meses, 'mes'));
$mes_counts = json_encode(array_map('intval', array_column($meses, 'qtd')));

// Top aparelhos
$ap_data = $pdo->query("SELECT COALESCE(NULLIF(aparelho,''), 'Sem aparelho') as label, COUNT(*) as qtd FROM ordens_servico GROUP BY label ORDER BY qtd DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
$ap_labels = json_encode(array_column($ap_data, 'label'));
$ap_counts = json_encode(array_map('intval', array_column($ap_data, 'qtd')));

// Top marcas
$ma_data = $pdo->query("SELECT COALESCE(NULLIF(marca,''), 'Sem marca') as label, COUNT(*) as qtd FROM ordens_servico GROUP BY label ORDER BY qtd DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
$ma_labels = json_encode(array_column($ma_data, 'label'));
$ma_counts = json_encode(array_map('intval', array_column($ma_data, 'qtd')));

// Bairros (from clientes)
$bairro_data = $pdo->query("SELECT COALESCE(NULLIF(bairro,''), 'Sem bairro') as label, COUNT(*) as qtd FROM clientes GROUP BY label ORDER BY qtd DESC")->fetchAll(PDO::FETCH_ASSOC);
$bairro_labels = json_encode(array_column($bairro_data, 'label'));
$bairro_counts = json_encode(array_map('intval', array_column($bairro_data, 'qtd')));
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Dashboard - Assist-OS</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
  <style>
    .stat-card {
      border: 1px solid var(--bs-border-color);
      border-left: 4px solid var(--brand-color);
      border-radius: 10px;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
    .module-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 10px;
      border-top: 3px solid transparent;
      transition: box-shadow 0.2s, transform 0.2s, border-top-color 0.2s;
    }
    .module-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      transform: translateY(-2px);
      border-top-color: var(--brand-color);
    }
    .module-card .card-body {
      padding: 1.25rem 1rem;
    }
    .chart-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 10px;
    }
    .chart-card .card-header {
      background: transparent;
      border-bottom: 1px solid var(--bs-border-color);
      font-weight: 600;
      font-size: 0.9rem;
      padding: 0.75rem 1rem;
    }
    .chart-card .card-body {
      padding: 0.75rem;
      min-height: 220px;
    }
    .chart-card canvas {
      max-height: 220px;
    }
  </style>
</head>
<body>
  <?php require('header.php'); ?>
  <main class="container py-3">
    <!-- Stats row -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card card p-3 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-2" style="background:rgba(26,58,92,0.1)">
              <i class="bi bi-people-fill fs-4" style="color:var(--brand-color)"></i>
            </div>
            <div class="min-w-0">
              <div class="fs-4 fw-bold"><?= $total_clientes ?></div>
              <small class="text-muted text-nowrap">Total de Clientes</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card p-3 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-2" style="background:rgba(25,135,84,0.1)">
              <i class="bi bi-calendar-check fs-4 text-success"></i>
            </div>
            <div class="min-w-0">
              <div class="fs-4 fw-bold"><?= $total_este_mes ?></div>
              <small class="text-muted text-nowrap">Ordens este Mês</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card p-3 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-2" style="background:rgba(13,110,253,0.1)">
              <i class="bi bi-clock-history fs-4 text-primary"></i>
            </div>
            <div class="min-w-0">
              <div class="fs-4 fw-bold"><?= $total_ordens ?></div>
              <small class="text-muted text-nowrap">Total de OS</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card p-3 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-2" style="background:rgba(13,110,253,0.1)">
              <i class="bi bi-check2-circle fs-4 text-primary"></i>
            </div>
            <div class="min-w-0">
              <div class="fs-4 fw-bold"><?= $total_pronto ?></div>
              <small class="text-muted text-nowrap">Pronto</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Module cards -->
    <h5 class="fw-semibold mb-3">Módulos</h5>
    <div class="row g-3 mb-4">
      <?php
      $modulos = [
        ['link' => 'clientes.php',      'icon' => 'bi-people-fill',    'label' => 'Clientes',         'color' => '#1a3a5c'],
        ['link' => 'ordem-servico.php', 'icon' => 'bi-clock-history',  'label' => 'Ordem de Serviço', 'color' => '#0d6efd'],
        ['link' => 'vendas.php',        'icon' => 'bi-cart3',          'label' => 'Vendas',           'color' => '#198754'],
        ['link' => 'financeiro.php',    'icon' => 'bi-wallet2',        'label' => 'Financeiro',       'color' => '#6f42c1'],
        ['link' => 'estoque.php',       'icon' => 'bi-boxes',          'label' => 'Estoque',          'color' => '#fd7e14'],
        ['link' => 'relatorios.php',    'icon' => 'bi-bar-chart-fill', 'label' => 'Relatórios',       'color' => '#dc3545'],
        ['link' => 'suporte.php',       'icon' => 'bi-headset',        'label' => 'Suporte',          'color' => '#20c997'],
        ['link' => 'configs.php',       'icon' => 'bi-gear',           'label' => 'Configs.',         'color' => '#6c757d'],
      ];
      foreach ($modulos as $m):
      ?>
      <div class="col-6 col-md-4 col-lg-3">
        <a href="<?= $m['link'] ?>" class="text-decoration-none">
          <div class="module-card card h-100 text-center">
            <div class="card-body">
              <i class="bi <?= $m['icon'] ?> fs-2" style="color:<?= $m['color'] ?>"></i>
              <h6 class="mt-2 mb-0 small fw-semibold text-body"><?= $m['label'] ?></h6>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Charts -->
    <h5 class="fw-semibold mb-3">Gráficos</h5>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="chart-card card h-100">
          <div class="card-header">Status dos Aparelhos</div>
          <div class="card-body"><canvas id="chartStatus"></canvas></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="chart-card card h-100">
          <div class="card-header">Cadastros por Mês</div>
          <div class="card-body"><canvas id="chartMeses"></canvas></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="chart-card card h-100">
          <div class="card-header">Aparelhos</div>
          <div class="card-body"><canvas id="chartAparelhos"></canvas></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="chart-card card h-100">
          <div class="card-header">Marcas</div>
          <div class="card-body"><canvas id="chartMarcas"></canvas></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="chart-card card h-100">
          <div class="card-header">Bairros</div>
          <div class="card-body"><canvas id="chartBairros"></canvas></div>
        </div>
      </div>
    </div>
  </main>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script>
  var isDark = (localStorage.getItem('assist-os-theme') || 'light') === 'dark';
  var gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
  var textColor = isDark ? '#adb5bd' : '#6c757d';

  function brandColor(alpha) { return isDark ? 'rgba(88,144,192,'+alpha+')' : 'rgba(26,58,92,'+alpha+')'; }

  var chartDefaults = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, color: textColor } } }
  };

  // Status (doughnut)
  new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
      labels: <?= $status_labels ?>,
      datasets: [{
        data: <?= $status_counts ?>,
        backgroundColor: ['#0d6efd','#6f42c1','#20c997','#fd7e14','#198754','#dc3545','#adb5bd']
      }]
    },
    options: chartDefaults
  });

  // Meses (bar)
  new Chart(document.getElementById('chartMeses'), {
    type: 'bar',
    data: {
      labels: <?= $mes_labels ?>,
      datasets: [{
        label: 'Cadastros',
        data: <?= $mes_counts ?>,
        backgroundColor: brandColor(0.7),
        borderColor: brandColor(1),
        borderWidth: 1,
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: textColor }, grid: { color: gridColor } },
        y: { ticks: { color: textColor, stepSize: 1 }, grid: { color: gridColor } }
      }
    }
  });

  // Aparelhos (bar horizontal)
  new Chart(document.getElementById('chartAparelhos'), {
    type: 'bar',
    data: {
      labels: <?= $ap_labels ?>,
      datasets: [{
        data: <?= $ap_counts ?>,
        backgroundColor: ['#0d6efd','#6f42c1','#20c997','#fd7e14','#198754','#dc3545','#adb5bd','#ffc107']
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: textColor, stepSize: 1 }, grid: { color: gridColor } },
        y: { ticks: { color: textColor }, grid: { color: gridColor } }
      }
    }
  });

  // Marcas (bar horizontal)
  new Chart(document.getElementById('chartMarcas'), {
    type: 'bar',
    data: {
      labels: <?= $ma_labels ?>,
      datasets: [{
        data: <?= $ma_counts ?>,
        backgroundColor: ['#0d6efd','#6f42c1','#20c997','#fd7e14','#198754','#dc3545','#adb5bd','#ffc107']
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: textColor, stepSize: 1 }, grid: { color: gridColor } },
        y: { ticks: { color: textColor }, grid: { color: gridColor } }
      }
    }
  });

  // Bairros (doughnut)
  new Chart(document.getElementById('chartBairros'), {
    type: 'doughnut',
    data: {
      labels: <?= $bairro_labels ?>,
      datasets: [{
        data: <?= $bairro_counts ?>,
        backgroundColor: ['#0d6efd','#6f42c1','#20c997','#fd7e14','#198754','#dc3545','#adb5bd','#ffc107']
      }]
    },
    options: chartDefaults
  });
  </script>
</body>
</html>
