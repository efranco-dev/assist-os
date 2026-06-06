document.addEventListener('DOMContentLoaded', function () {
  // ─── Theme ───
  var html = document.documentElement;
  var toggleBtn = document.getElementById('themeToggle');
  if (toggleBtn) {
    var icon = toggleBtn.querySelector('i');
    function setTheme(theme) {
      html.setAttribute('data-bs-theme', theme);
      localStorage.setItem('assist-os-theme', theme);
      icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
    var saved = localStorage.getItem('assist-os-theme') || 'light';
    setTheme(saved);
    toggleBtn.addEventListener('click', function () {
      setTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
    });
  }

  // ─── Sidebar ───
  var sidebar = document.getElementById('sidebar');
  var body = document.body;
  var overlay = document.getElementById('sidebarOverlay');
  var toggleMobile = document.getElementById('sidebarToggle');
  var toggleDesktop = document.getElementById('sidebarToggleDesktop');
  var closeBtn = document.getElementById('sidebarClose');

  function isDesktop() { return window.innerWidth >= 992; }

  function setDesktopState(closed) {
    body.classList.toggle('sidebar-closed', closed);
    sidebar.classList.toggle('collapsed', closed);
    localStorage.setItem('assist-os-sidebar', closed ? 'closed' : 'open');
  }

  function openMobile() {
    sidebar.classList.add('mobile-show');
    if (overlay) overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeMobile() {
    sidebar.classList.remove('mobile-show');
    if (overlay) overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  if (isDesktop()) {
    var saved = localStorage.getItem('assist-os-sidebar');
    if (saved === 'closed') setDesktopState(true);
  }

  if (toggleDesktop) {
    toggleDesktop.addEventListener('click', function () {
      setDesktopState(!body.classList.contains('sidebar-closed'));
    });
  }

  if (toggleMobile) {
    toggleMobile.addEventListener('click', function () {
      if (sidebar.classList.contains('mobile-show')) closeMobile();
      else openMobile();
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMobile);
  }

  if (overlay) {
    overlay.addEventListener('click', closeMobile);
  }

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      if (isDesktop()) {
        closeMobile();
        var saved = localStorage.getItem('assist-os-sidebar');
        body.classList.toggle('sidebar-closed', saved === 'closed');
        sidebar.classList.toggle('collapsed', saved === 'closed');
      }
    }, 200);
  });
});
