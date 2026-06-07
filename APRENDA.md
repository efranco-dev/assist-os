# 📚 Aprenda a construir o Assist-OS do zero

> Tutorial passo a passo para refazer este sistema de Ordem de Serviço (OS).
> Cada etapa é um degrau — termine uma antes de ir para a próxima.

---
---
## Antes de começar

### O que você precisa instalar

| Ferramenta | Para quê |
|------------|----------|
| [XAMPP](https://www.apachefriends.org/) | Servidor Apache + PHP + MySQL |
| [VS Code](https://code.visualstudio.com/) | Editor de código |
| [Git](https://git-scm.com/) | Versionamento (opcional) |

### Como testar cada etapa

1. Ligue o **Apache** e **MySQL** no painel do XAMPP
2. Salve os arquivos em `C:\xampp\htdocs\assist-os\`
3. Acesse `http://localhost/assist-os/` no navegador

---

## 🧱 Etapa 1 — Setup do banco de dados

**Arquivo:** schema.sql (rode no phpMyAdmin ou MySQL Workbench)

```sql
CREATE DATABASE IF NOT EXISTS `ordem`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `ordem`;

-- Tabela de usuários (login)
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `nome`    VARCHAR(100) NOT NULL,
  `usuario` VARCHAR(50)  NOT NULL UNIQUE,
  `senha`   VARCHAR(255) NOT NULL,       -- bcrypt hash
  `nivel`   VARCHAR(20)  DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `nome`          VARCHAR(150) NOT NULL,
  `endereco`      VARCHAR(255) DEFAULT NULL,
  `bairro`        VARCHAR(100) DEFAULT NULL,
  `telefone`      VARCHAR(20)  DEFAULT NULL,
  `data_cadastro` DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de ordens de serviço
CREATE TABLE IF NOT EXISTS `ordens_servico` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id`    INT NOT NULL,
  `aparelho`      VARCHAR(100) DEFAULT NULL,
  `marca`         VARCHAR(100) DEFAULT NULL,
  `modelo`        VARCHAR(100) DEFAULT NULL,
  `defeito`       TEXT         DEFAULT NULL,
  `servico`       TEXT         DEFAULT NULL,
  `observacoes`   TEXT         DEFAULT NULL,
  `status`        VARCHAR(50)  DEFAULT 'Aguardando',
  `valor_servico` DECIMAL(10,2) DEFAULT 0.00,
  `desconto`      DECIMAL(10,2) DEFAULT 0.00,
  `valor_total`   DECIMAL(10,2) DEFAULT 0.00,
  `data_entrada`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelas de opções (dropdowns gerenciáveis)
CREATE TABLE IF NOT EXISTS `aparelhos` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `marcas` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `status_opcoes` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `bairros` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar usuário admin padrão (senha: admin123)
INSERT INTO `usuarios` (`nome`, `usuario`, `senha`, `nivel`)
VALUES ('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Opções iniciais
INSERT INTO `aparelhos` (`nome`) VALUES
  ('Smartphone'), ('Tablet'), ('Notebook'), ('Desktop'), ('TV'), ('Monitor');
INSERT INTO `marcas` (`nome`) VALUES
  ('Samsung'), ('Apple'), ('LG'), ('Sony'), ('Dell'), ('Positivo');
INSERT INTO `status_opcoes` (`nome`) VALUES
  ('Aguardando'), ('Em Andamento'), ('Aguardando Peças'), ('Pronto'), ('Entregue');
INSERT INTO `bairros` (`nome`) VALUES
  ('Centro'), ('Jardim América'), ('Vila Nova'), ('Santa Tereza');
```

**O que você aprende aqui:**
- Criar banco de dados e tabelas
- Chave estrangeira (`FOREIGN KEY`) e `ON DELETE CASCADE`
- Inserir registros iniciais
- `bcrypt` hash (já vem pronto — use o PHP depois)

---

## 🧱 Etapa 2 — Conexão com o banco

**Arquivo:** `conexao.php`

```php
<?php

date_default_timezone_set('America/Sao_Paulo');

$pdo = new PDO(
    'mysql:host=localhost;dbname=ordem;charset=utf8',
    'root',
    ''  // sua senha do MySQL, se tiver
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```

**O que você aprende:**
- `PDO` — conexão segura com MySQL
- `ERRMODE_EXCEPTION` — lançar exceções em erros
- `FETCH_ASSOC` — retornar resultados como array associativo

---

## 🧱 Etapa 3 — Tela de login

**Arquivo:** `login.php`

### Lógica PHP (topo do arquivo)

```php
<?php
session_start();
if (isset($_SESSION['logado']) && $_SESSION['logado']) {
    header('Location: dashboard.php');
    exit();
}

require('conexao.php');
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario && $senha) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['logado']    = true;
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            header('Location: dashboard.php');
            exit();
        }
        $erro = 'Usuário ou senha inválidos.';
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
```

### HTML + CSS (corpo)

```html
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Login - Assist-OS</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f2f5;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      border: 1px solid #dee2e6;
      border-radius: 12px;
    }
    [data-bs-theme="dark"] body {
      background: #1a1d21;
    }
  </style>
</head>
<body>
  <div class="login-card card shadow-sm p-4">
    <div class="text-center mb-4">
      <div class="d-flex align-items-center justify-content-center rounded-3 text-white mx-auto mb-2"
           style="width:52px;height:52px;background:var(--brand-color,#1a3a5c)">
        <i class="bi bi-tools fs-4"></i>
      </div>
      <h5 class="mb-0 fw-semibold">Assist-OS</h5>
      <small class="text-muted">Faça login para continuar</small>
    </div>

    <?php if ($erro): ?>
      <div class="alert alert-danger py-2 small"><?= $erro ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label small">Usuário</label>
        <input type="text" name="usuario" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label small">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <button type="submit" class="btn w-100 text-white"
              style="background:var(--brand-color,#1a3a5c)">Entrar</button>
    </form>
    <small class="text-muted text-center mt-3">admin / admin123</small>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

**O que você aprende:**
- `session_start()` para manter o usuário logado
- `password_verify()` para comparar senha com hash bcrypt
- Prepared statements com PDO (segurança contra SQL injection)
- `header('Location: ...')` para redirecionar

---

## 🧱 Etapa 4 — Header e Sidebar (layout compartilhado)

**Arquivo:** `header.php`

```php
<?php
session_start();
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}

// Mapeamento: página atual → item do menu que deve ficar ativo
$parent_map = [
    'index.php'           => 'clientes',
    'clientes.php'        => 'clientes',
    'cliente-editar.php'  => 'clientes',
    'visualizar.php'      => 'clientes',
    'ordem-servico.php'   => 'os',
    'os-nova.php'         => 'os',
    'editar.php'          => 'os',
    'dashboard.php'       => 'dashboard',
    'aparelhos.php'       => 'aparelhos',
];
$current_page = basename($_SERVER['SCRIPT_NAME']);
$active_parent = $parent_map[$current_page] ?? '';
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Assist-OS</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>
<body>

<!-- TOPBAR -->
<nav class="topbar d-flex align-items-center justify-content-between px-3">
  <div class="d-flex align-items-center gap-2">
    <button class="btn btn-link text-white p-0 fs-5" id="sidebarToggle">
      <i class="bi bi-list"></i>
    </button>
    <button class="btn btn-link text-white p-0 fs-5 d-lg-none" id="mobileMenuOpen">
      <i class="bi bi-list"></i>
    </button>
    <span class="fw-semibold text-white ms-2">Assist-OS</span>
  </div>
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-link text-white p-0" id="themeToggle">
      <i class="bi bi-moon-fill"></i>
    </button>
    <div class="dropdown">
      <button class="btn btn-link text-white text-decoration-none dropdown-toggle p-0"
              data-bs-toggle="dropdown">
        <i class="bi bi-person-circle"></i> <?= $_SESSION['user_nome'] ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="logout.php">Sair</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- WRAPPER -->
<div class="d-flex">

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebar">
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link px-3 py-2 <?= $active_parent === 'dashboard' ? 'active' : '' ?>"
         href="dashboard.php">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link px-3 py-2 <?= $active_parent === 'clientes' ? 'active' : '' ?>"
         href="clientes.php">
        <i class="bi bi-people me-2"></i> Clientes
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link px-3 py-2 <?= $active_parent === 'os' ? 'active' : '' ?>"
         href="ordem-servico.php">
        <i class="bi bi-file-earmark-text me-2"></i> Ordens de Serviço
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link px-3 py-2 <?= $active_parent === 'aparelhos' ? 'active' : '' ?>"
         href="aparelhos.php">
        <i class="bi bi-phone me-2"></i> Aparelhos
      </a>
    </li>
  </ul>
</nav>

<!-- MOBILE OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- CONTEÚDO -->
<main class="main-content flex-grow-1 p-3">
```

**O que você aprende:**
- Layout compartilhado via `include()`
- `basename($_SERVER['SCRIPT_NAME'])` para detectar página atual
- Sidebar responsiva com Bootstrap
- Dropdown do usuário e botão de tema

---

## 🧱 Etapa 5 — CSS personalizado

**Arquivo:** `css/styles.css`

```css
:root {
  --brand-color: #1a3a5c;
  --sidebar-width: 230px;
  --topbar-height: 52px;
}

/* TOPBAR */
.topbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: var(--topbar-height);
  background: var(--brand-color);
  z-index: 1030;
}

/* SIDEBAR */
.sidebar {
  position: fixed;
  top: var(--topbar-height);
  left: 0;
  width: var(--sidebar-width);
  height: calc(100vh - var(--topbar-height));
  background: #f8f9fa;
  border-right: 1px solid #dee2e6;
  overflow-y: auto;
  transition: transform .25s ease;
  z-index: 1020;
}
.sidebar .nav-link {
  color: #333;
  border-radius: 0;
  border-left: 3px solid transparent;
}
.sidebar .nav-link:hover {
  background: #e9ecef;
}
.sidebar .nav-link.active {
  background: #e9ecef;
  border-left-color: var(--brand-color);
  font-weight: 600;
}
.sidebar.collapsed {
  transform: translateX(-100%);
}

/* CONTEÚDO */
.main-content {
  margin-top: var(--topbar-height);
  margin-left: var(--sidebar-width);
  min-height: calc(100vh - var(--topbar-height));
  transition: margin-left .25s ease;
}
.main-content.expanded {
  margin-left: 0;
}

/* OVERLAY (mobile) */
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.4);
  z-index: 1015;
}
.sidebar-overlay.show {
  display: block;
}

/* RESPONSIVO */
@media (max-width: 991.98px) {
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar.mobile-open {
    transform: translateX(0);
  }
  .main-content {
    margin-left: 0;
  }
}

/* DARK MODE */
[data-bs-theme="dark"] .sidebar {
  background: #212529;
  border-right-color: #2b3035;
}
[data-bs-theme="dark"] .sidebar .nav-link {
  color: #dee2e6;
}
[data-bs-theme="dark"] .sidebar .nav-link:hover,
[data-bs-theme="dark"] .sidebar .nav-link.active {
  background: #2b3035;
}
```

**O que você aprende:**
- CSS custom properties (`--brand-color`)
- Layout fixo com topbar + sidebar
- Transições suaves com `transition`
- Media queries para responsividade
- Dark mode com `[data-bs-theme="dark"]`

---

## 🧱 Etapa 6 — JavaScript (tema, sidebar, máscaras)

### `js/theme.js` — Toggle dark/light + sidebar

```js
document.addEventListener('DOMContentLoaded', () => {
  const html = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = themeToggle?.querySelector('i');

  // Recuperar tema salvo
  const savedTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-bs-theme', savedTheme);
  if (themeIcon) {
    themeIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
  }

  themeToggle?.addEventListener('click', () => {
    const current = html.getAttribute('data-bs-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-bs-theme', next);
    localStorage.setItem('theme', next);
    if (themeIcon) {
      themeIcon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
  });

  // Sidebar desktop toggle
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const mainContent = document.querySelector('.main-content');
  const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

  if (sidebarCollapsed && window.innerWidth >= 992) {
    sidebar?.classList.add('collapsed');
    mainContent?.classList.add('expanded');
  }

  sidebarToggle?.addEventListener('click', () => {
    sidebar?.classList.toggle('collapsed');
    mainContent?.classList.toggle('expanded');
    const isCollapsed = sidebar?.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
  });

  // Sidebar mobile toggle
  const mobileOpen = document.getElementById('mobileMenuOpen');
  const overlay = document.getElementById('sidebarOverlay');

  mobileOpen?.addEventListener('click', () => {
    sidebar?.classList.add('mobile-open');
    overlay?.classList.add('show');
  });

  overlay?.addEventListener('click', () => {
    sidebar?.classList.remove('mobile-open');
    overlay?.classList.remove('show');
  });
});
```

### `js/mask-phone.js` — Máscara de telefone

```js
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.phone-mask').forEach(input => {
    input.addEventListener('input', () => {
      let v = input.value.replace(/\D/g, '').slice(0, 11);
      if (v.length <= 10) {
        v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
      } else {
        v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
      }
      input.value = v;
    });
  });
});
```

### `js/total-calculation.js` — Cálculo automático de valor

```js
document.addEventListener('DOMContentLoaded', () => {
  const valorInput = document.getElementById('valor_servico');
  const descontoInput = document.getElementById('desconto');
  const totalInput = document.getElementById('valor_total');

  function parseCurrency(str) {
    return parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;
  }
  function formatCurrency(val) {
    return 'R$ ' + val.toFixed(2).replace('.', ',');
  }

  function calcular() {
    const valor = parseCurrency(valorInput?.value || '0');
    const desc = parseCurrency(descontoInput?.value || '0');
    const total = Math.max(0, valor - desc);
    if (totalInput) totalInput.value = formatCurrency(total);
  }

  valorInput?.addEventListener('input', calcular);
  descontoInput?.addEventListener('input', calcular);
});
```

### `js/delete-link.js` — Confirmação de exclusão

```js
document.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal('#confirmDeleteModal');
  let confirmUrl = '';

  document.querySelectorAll('.delete-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      confirmUrl = link.getAttribute('href');
      modal.show();
    });
  });

  document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
    if (confirmUrl) window.location.href = confirmUrl;
  });
});
```

### `js/mensagem-sucesso.js` — Auto-dismiss de alertas

```js
document.addEventListener('DOMContentLoaded', () => {
  const alert = document.querySelector('.alert-success');
  if (alert) {
    setTimeout(() => {
      alert.style.transition = 'opacity .5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  }
});
```

**O que você aprende:**
- `localStorage` para persistir preferências (tema, sidebar)
- `addEventListener` vs `onclick`
- Regex para máscara de telefone
- Formatação de moeda brasileira
- Bootstrap Modal via JavaScript

---

## 🧱 Etapa 7 — Dashboard

**Arquivo:** `dashboard.php`

```php
<?php
require('header.php');
require('conexao.php');

// -- Contadores --
$totalClientes  = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$totalOS        = $pdo->query("SELECT COUNT(*) FROM ordens_servico")->fetchColumn();
$osEsteMes      = $pdo->query("SELECT COUNT(*) FROM ordens_servico WHERE MONTH(data_entrada) = MONTH(CURRENT_DATE) AND YEAR(data_entrada) = YEAR(CURRENT_DATE)")->fetchColumn();
$prontos        = $pdo->query("SELECT COUNT(*) FROM ordens_servico WHERE status = 'Pronto'")->fetchColumn();

// -- Dados para gráficos --
$statusRows = $pdo->query("SELECT status, COUNT(*) AS total FROM ordens_servico GROUP BY status")->fetchAll();
$mesesRows  = $pdo->query("SELECT DATE_FORMAT(data_entrada, '%Y-%m') AS mes, COUNT(*) AS total FROM ordens_servico GROUP BY mes ORDER BY mes LIMIT 12")->fetchAll();
$aparelhoRows = $pdo->query("SELECT aparelho, COUNT(*) AS total FROM ordens_servico GROUP BY aparelho ORDER BY total DESC LIMIT 8")->fetchAll();
$marcaRows  = $pdo->query("SELECT marca, COUNT(*) AS total FROM ordens_servico GROUP BY marca ORDER BY total DESC LIMIT 8")->fetchAll();
$bairroRows = $pdo->query("SELECT bairro, COUNT(*) AS total FROM clientes GROUP BY bairro ORDER BY total DESC LIMIT 8")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-semibold mb-0">Dashboard</h5>
  <span class="text-muted small"><?= date('d/m/Y') ?></span>
</div>

<!-- CARDS DE ESTATÍSTICAS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 text-center">
      <div class="fs-4 fw-bold text-primary"><?= $totalClientes ?></div>
      <small class="text-muted">Total Clientes</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 text-center">
      <div class="fs-4 fw-bold text-success"><?= $totalOS ?></div>
      <small class="text-muted">Total OS</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 text-center">
      <div class="fs-4 fw-bold text-warning"><?= $osEsteMes ?></div>
      <small class="text-muted">OS este mês</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 text-center">
      <div class="fs-4 fw-bold text-info"><?= $prontos ?></div>
      <small class="text-muted">Prontos</small>
    </div>
  </div>
</div>

<!-- GRÁFICOS (Chart.js) -->
<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <small class="fw-semibold mb-2">OS por Status</small>
      <canvas id="chartStatus" height="180"></canvas>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <small class="fw-semibold mb-2">OS por Mês</small>
      <canvas id="chartMeses" height="180"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card shadow-sm p-3">
      <small class="fw-semibold mb-2">Por Aparelho</small>
      <canvas id="chartAparelho" height="160"></canvas>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm p-3">
      <small class="fw-semibold mb-2">Por Marca</small>
      <canvas id="chartMarca" height="160"></canvas>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm p-3">
      <small class="fw-semibold mb-2">Por Bairro</small>
      <canvas id="chartBairro" height="160"></canvas>
    </div>
  </div>
</div>

<!-- Scripts dos gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const statusData = <?= json_encode($statusRows) ?>;
const mesesData  = <?= json_encode($mesesRows) ?>;
const aparelhoData = <?= json_encode($aparelhoRows) ?>;
const marcaData  = <?= json_encode($marcaRows) ?>;
const bairroData = <?= json_encode($bairroRows) ?>;

const cores = ['#1a3a5c','#2d6a9f','#4a9eff','#7ab8ff','#a8d0ff','#d4e6ff'];

new Chart(document.getElementById('chartStatus'), {
  type: 'doughnut',
  data: {
    labels: statusData.map(r => r.status),
    datasets: [{
      data: statusData.map(r => r.total),
      backgroundColor: cores
    }]
  }
});

new Chart(document.getElementById('chartMeses'), {
  type: 'bar',
  data: {
    labels: mesesData.map(r => r.mes),
    datasets: [{
      label: 'OS',
      data: mesesData.map(r => r.total),
      backgroundColor: '#1a3a5c'
    }]
  },
  options: { responsive: true }
});

function makeBar(canvasId, data, label) {
  new Chart(document.getElementById(canvasId), {
    type: 'bar',
    data: {
      labels: data.map(r => r[label]),
      datasets: [{
        label: label,
        data: data.map(r => r.total),
        backgroundColor: cores
      }]
    },
    options: { indexAxis: 'y', responsive: true }
  });
}
makeBar('chartAparelho', aparelhoData, 'aparelho');
makeBar('chartMarca', marcaData, 'marca');
makeBar('chartBairro', bairroData, 'bairro');
</script>

<?php require('footer.php'); // apenas </main></div></body></html> ?>
```

**(Crie também um `footer.php` simples para fechar o HTML)**

**O que você aprende:**
- SQL Aggregate Functions: `COUNT()`, `GROUP BY`, `MONTH()`, `DATE_FORMAT()`
- `fetchColumn()` para valores únicos
- `json_encode()` para passar dados PHP para JavaScript
- Chart.js: gráficos doughnut e bar

---

## 🧱 Etapa 8 — CRUD de Clientes

### Cadastro (`index.php`)

Arquivo com formulário de cadastro + busca + listagem. Estrutura:

1. Formulário com campos: nome, endereço, bairro, telefone
2. POST para `cadastrar.php`
3. Tabela listando clientes cadastrados
4. Link para editar (`cliente-editar.php?id=X`)
5. Link para excluir (`deletar.php?id=X&tabela=clientes`)

### Handler (`cadastrar.php`)

```php
<?php
session_start();
require('conexao.php');

$redirect = $_POST['redirect'] ?? 'index.php';
$nome     = trim($_POST['nome'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$bairro   = trim($_POST['bairro'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');

if ($nome) {
    $stmt = $pdo->prepare("INSERT INTO clientes (nome, endereco, bairro, telefone) VALUES (:nome, :endereco, :bairro, :telefone)");
    $stmt->execute([':nome' => $nome, ':endereco' => $endereco, ':bairro' => $bairro, ':telefone' => $telefone]);
    $_SESSION['sucesso'] = 'Cliente cadastrado com sucesso!';
}

if ($redirect === 'os-nova.php' && isset($_POST['redirect'])) {
    $lastId = $pdo->lastInsertId();
    header("Location: $redirect?cliente_id=$lastId");
} else {
    header("Location: $redirect");
}
exit();
```

### Editar (`cliente-editar.php`)

Carrega os dados do cliente pelo ID, exibe o formulário preenchido, salva no POST.

```php
// Carregar cliente
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch();

// No POST, atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE clientes SET nome=:nome, endereco=:endereco, bairro=:bairro, telefone=:telefone WHERE id=:id");
    $stmt->execute([...]);
    $_SESSION['sucesso'] = 'Cliente atualizado!';
    header('Location: clientes.php');
    exit();
}
```

### Listagem (`clientes.php`)

```php
$busca = $_GET['busca'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE :busca OR telefone LIKE :busca2 ORDER BY data_cadastro DESC");
$stmt->execute([':busca' => "%$busca%", ':busca2' => "%$busca%"]);
$clientes = $stmt->fetchAll();
// Renderizar tabela com foreach
```

**O que você aprende:**
- CRUD completo: INSERT, SELECT, UPDATE, DELETE
- `LIKE` para busca textual
- `lastInsertId()` para pegar o ID recém-criado

---

## 🧱 Etapa 9 — CRUD de Ordens de Serviço

### Nova OS (`os-nova.php`)

Formulário complexo com:
- Select de cliente (com opção de cadastro rápido via modal)
- Selects de aparelho, marca, status (puxados das tabelas de opções)
- Campos de preço com máscara de moeda
- Modal para gerenciar opções inline (requer `gerenciar_opcoes.php`)

### Salvar OS (`os-salvar.php`)

```php
<?php
session_start();
require('conexao.php');

function parseCurrency($value) {
    return str_replace(',', '.', str_replace('.', '', $value));
}

$cliente_id    = $_POST['cliente_id'] ?? 0;
$aparelho      = trim($_POST['aparelho'] ?? '');
$marca         = trim($_POST['marca'] ?? '');
$modelo        = trim($_POST['modelo'] ?? '');
$defeito       = trim($_POST['defeito'] ?? '');
$servico       = trim($_POST['servico'] ?? '');
$observacoes   = trim($_POST['observacoes'] ?? '');
$status        = trim($_POST['status'] ?? 'Aguardando');
$valor_servico = parseCurrency($_POST['valor_servico'] ?? '0');
$desconto      = parseCurrency($_POST['desconto'] ?? '0');
$valor_total   = parseCurrency($_POST['valor_total'] ?? '0');

$stmt = $pdo->prepare("INSERT INTO ordens_servico (cliente_id, aparelho, marca, modelo, defeito, servico, observacoes, status, valor_servico, desconto, valor_total) VALUES (:cliente_id, :aparelho, :marca, :modelo, :defeito, :servico, :observacoes, :status, :valor_servico, :desconto, :valor_total)");
$stmt->execute([...]);

$_SESSION['sucesso'] = 'Ordem de serviço criada!';
header('Location: ordem-servico.php');
exit();
```

### Listagem (`ordem-servico.php`)

Tabela com JOIN em clientes, busca por nome/aparelho/status, badges coloridos.

### Editar (`editar.php`)

Carrega OS por ID, preenche formulário, POST para `atualizar.php`.

### Visualizar (`visualizar.php`)

Exibe detalhes completos: dados do cliente + dados da OS.

**O que você aprende:**
- `JOIN` entre tabelas
- Moeda no padrão brasileiro (R$ 1.234,56)
- Modais Bootstrap para criação inline

---

## 🧱 Etapa 10 — Gerenciamento de Opções

**Arquivo:** `gerenciar_opcoes.php`

Gerencia 4 tabelas: `aparelhos`, `marcas`, `status_opcoes`, `bairros`.

```php
<?php
// Incluir este arquivo em páginas que precisam dos modais

function renderModal($id, $label, $tabela, $campos) {
  // Gera modal Bootstrap com formulário para add/edit/delete
}

// Tratar POST: adicionar, editar, excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_opcao'])) {
    $tabela = $_POST['tabela_opcao'] ?? '';
    $nome   = trim($_POST['nome_opcao'] ?? '');
    $id     = $_POST['id_opcao'] ?? null;

    if ($_POST['acao_opcao'] === 'add' && $nome) {
        $pdo->prepare("INSERT INTO $tabela (nome) VALUES (:nome)")->execute([':nome' => $nome]);
    } elseif ($_POST['acao_opcao'] === 'edit' && $id && $nome) {
        $pdo->prepare("UPDATE $tabela SET nome = :nome WHERE id = :id")->execute([':nome' => $nome, ':id' => $id]);
    } elseif ($_POST['acao_opcao'] === 'delete' && $id) {
        $pdo->prepare("DELETE FROM $tabela WHERE id = :id")->execute([':id' => $id]);
    }
    $_SESSION['sel_' . $tabela] = $nome;
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
```

**O que você aprende:**
- Reutilização de código com funções
- Uma única página para gerenciar múltiplas tabelas
- Sessão para preservar seleção do usuário

---

## 🧱 Etapa 11 — Deletar registros

**Arquivo:** `deletar.php`

```php
<?php
session_start();
require('conexao.php');

$id     = $_GET['id'] ?? 0;
$tabela = $_GET['tabela'] ?? 'ordens_servico';

if ($tabela === 'clientes') {
    $pdo->prepare("DELETE FROM clientes WHERE id = :id")->execute([':id' => $id]);
    // CASCADE já deleta as OS relacionadas
} else {
    $pdo->prepare("DELETE FROM ordens_servico WHERE id = :id")->execute([':id' => $id]);
}

$_SESSION['sucesso'] = 'Registro excluído!';
header('Location: ' . ($tabela === 'clientes' ? 'clientes.php' : 'ordem-servico.php'));
exit();
```

**O que você aprende:**
- `DELETE` com parâmetro de tabela dinâmica
- `ON DELETE CASCADE` na FK
- CSRF (note que GET não é seguro — em produção use POST)

---

## 🧱 Etapa 12 — Logout

**Arquivo:** `logout.php`

```php
<?php
session_start();
session_destroy();
header('Location: login.php');
exit();
```

**O que você aprende:**
- `session_destroy()` para encerrar sessão

---

## 🧱 Etapa 13 — Placeholders (módulos em branco)

Arquivos como `estoque.php`, `vendas.php`, `financeiro.php`, `relatorios.php`, `configs.php`, `suporte.php` seguem o mesmo padrão:

```php
<?php require('header.php'); ?>
<div class="text-center py-5">
  <i class="bi bi-cone-striped fs-1 text-muted"></i>
  <p class="text-muted mt-2">Módulo em desenvolvimento</p>
</div>
<?php require('footer.php'); ?>
```

---

## 📦 Resumo da ordem recomendada

| # | O que construir | Arquivos |
|---|----------------|----------|
| 1 | Banco de dados | `schema.sql` |
| 2 | Conexão | `conexao.php` |
| 3 | Login | `login.php` |
| 4 | Layout | `header.php`, `footer.php`, `css/styles.css` |
| 5 | JS | `theme.js`, `mask-phone.js`, `total-calculation.js`, `delete-link.js`, `mensagem-sucesso.js` |
| 6 | Dashboard | `dashboard.php` |
| 7 | Clientes | `index.php`, `cadastrar.php`, `clientes.php`, `cliente-editar.php` |
| 8 | Ordens de Serviço | `os-nova.php`, `os-salvar.php`, `ordem-servico.php`, `editar.php`, `atualizar.php`, `visualizar.php` |
| 9 | Opções | `gerenciar_opcoes.php`, `aparelhos.php` |
| 10 | Utilitários | `deletar.php`, `logout.php`, `criar-admin.php` |
| 11 | Placeholders | `estoque.php`, `vendas.php`, `financeiro.php`, `relatorios.php`, `configs.php`, `suporte.php` |

---

## 💡 Dicas finais

1. **Teste a cada etapa** — não escreva tudo de uma vez
2. **Comece pelo banco** — sem ele nada funciona
3. **Depois o login** — é a porta de entrada
4. **Depois o layout** — header + sidebar + footer
5. **Depois o CRUD mais simples** (clientes)
6. **Depois o CRUD mais complexo** (OS)
7. **Por último** os gráficos e módulos extras
8. **Use `var_dump()` e `print_r()`** para debugar queries
9. **Sempre use prepared statements** (nunca concatene SQL)
10. **Mantenha o XAMPP rodando** Apache + MySQL

Bom aprendizado! 🚀
