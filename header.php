<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);

$nav_items = [
    'dashboard.php'     => ['label' => 'Dashboard',       'icon' => 'bi-speedometer2'],
    'clientes.php'      => ['label' => 'Clientes',         'icon' => 'bi-people-fill'],
    'ordem-servico.php' => ['label' => 'Ordem de Serviço', 'icon' => 'bi-clock-history'],
    'vendas.php'        => ['label' => 'Vendas',           'icon' => 'bi-cart3'],
    'financeiro.php'    => ['label' => 'Financeiro',       'icon' => 'bi-wallet2'],
    'estoque.php'       => ['label' => 'Estoque',          'icon' => 'bi-boxes'],
    'relatorios.php'    => ['label' => 'Relatórios',       'icon' => 'bi-bar-chart-fill'],
    'suporte.php'       => ['label' => 'Suporte',          'icon' => 'bi-headset'],
    'configs.php'       => ['label' => 'Configs.',         'icon' => 'bi-gear'],
];

$parent_map = [
    'index.php'        => 'clientes.php',
    'cliente-novo.php' => 'clientes.php',
    'cliente-editar.php' => 'clientes.php',
    'visualizar.php'   => 'ordem-servico.php',
    'editar.php'       => 'ordem-servico.php',
    'os-nova.php'      => 'ordem-servico.php',
    'os-editar.php'    => 'ordem-servico.php',
    'cadastrar.php'    => 'clientes.php',
    'atualizar.php'    => 'clientes.php',
    'deletar.php'      => 'clientes.php',
];

$active_key = $parent_map[$current_page] ?? $current_page;
$user_nome = $_SESSION['user_nome'] ?? 'Usuário';
?>
<!-- Top Bar -->
<header id="topbar" class="d-flex align-items-center px-3 gap-2 border-bottom">
  <button id="sidebarToggle" class="btn btn-sm btn-outline-secondary d-lg-none" title="Menu">
    <i class="bi bi-list fs-5"></i>
  </button>
  <button id="sidebarToggleDesktop" class="btn btn-sm btn-outline-secondary d-none d-lg-flex" title="Alternar menu">
    <i class="bi bi-layout-sidebar"></i>
  </button>
  <div class="d-flex align-items-center gap-2">
    <div class="d-flex align-items-center justify-content-center rounded-3 text-white brand-icon-sm d-none d-sm-flex">
      <i class="bi bi-tools"></i>
    </div>
    <span class="fw-semibold fs-6">Assist-OS</span>
  </div>
  <div class="ms-auto d-flex align-items-center gap-2">
    <button id="themeToggle" class="btn btn-sm btn-outline-secondary d-flex align-items-center" title="Alternar tema">
      <i class="bi bi-moon-fill"></i>
    </button>
    <div class="dropdown">
      <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" data-bs-toggle="dropdown">
        <i class="bi bi-person-circle"></i>
        <span class="d-none d-md-inline small"><?= htmlspecialchars($user_nome) ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li><a class="dropdown-item small" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
      </ul>
    </div>
  </div>
</header>

<!-- Sidebar -->
<aside id="sidebar">
  <div class="sidebar-header d-flex align-items-center gap-2 px-3 py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-center rounded-3 text-white brand-icon-sm">
      <i class="bi bi-tools"></i>
    </div>
    <span class="fw-semibold">Assist-OS</span>
    <button id="sidebarClose" class="btn-close ms-auto d-lg-none"></button>
  </div>
  <nav class="sidebar-nav py-2">
    <?php foreach ($nav_items as $page => $item): ?>
      <a href="<?= $page ?>"
        class="sidebar-link d-flex align-items-center gap-3 px-3 py-2 <?= $active_key === $page ? 'active' : '' ?>">
        <i class="bi <?= $item['icon'] ?> sidebar-icon"></i>
        <span class="sidebar-label"><?= $item['label'] ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>

<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="d-lg-none"></div>
