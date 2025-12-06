document.addEventListener('DOMContentLoaded', function () {
  console.log('Admin JS cargado');

  function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    });
    const dateString = now.toLocaleDateString('es-ES', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });

    const timeElement = document.getElementById('current-time');
    const dateElement = document.getElementById('current-date');

    if (timeElement) timeElement.textContent = timeString;
    if (dateElement) dateElement.textContent = dateString;
  }

  setInterval(updateTime, 1000);
  updateTime();

  function formatNumber(num) {
    if (num >= 1000000) {
      return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
      return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
  }

  function animateCounters() {
    const counters = document.querySelectorAll('.stat-info h3');
    counters.forEach((counter) => {
      const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
      if (!isNaN(target)) {
        const formatted = formatNumber(target);
        counter.textContent = formatted;

        counter.style.opacity = '0';
        counter.style.transform = 'translateY(20px)';

        setTimeout(() => {
          counter.style.transition = 'all 0.5s ease';
          counter.style.opacity = '1';
          counter.style.transform = 'translateY(0)';
        }, 100);
      }
    });
  }

  animateCounters();

  const filterButtons = document.querySelectorAll('.filter-btn');
  filterButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const filter = this.getAttribute('data-filter');

      filterButtons.forEach((btn) => btn.classList.remove('active'));
      this.classList.add('active');

      console.log('Filtrar por:', filter);
    });
  });

  const searchInput = document.getElementById('search-products');
  if (searchInput) {
    let searchTimeout;

    searchInput.addEventListener('input', function () {
      clearTimeout(searchTimeout);

      searchTimeout = setTimeout(() => {
        const searchTerm = this.value.trim();

        if (searchTerm.length > 2) {
          console.log('Buscando:', searchTerm);
        }
      }, 500);
    });
  }

  const deleteButtons = document.querySelectorAll('.btn-danger');
  deleteButtons.forEach((button) => {
    button.addEventListener('click', function (e) {
      if (
        !confirm(
          '¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.'
        )
      ) {
        e.preventDefault();
      }
    });
  });

  const exportButtons = document.querySelectorAll('.export-btn');
  exportButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const type = this.getAttribute('data-type');
      alert(`Exportando ${type}... (Funcionalidad en desarrollo)`);
    });
  });

  if (typeof Chart !== 'undefined') {
    initializeCharts();
  }

  function initializeCharts() {
    console.log('Chart.js está disponible');
  }

  function checkNotifications() {
    const notifications = [
      { type: 'new_order', message: 'Nuevo pedido recibido' },
      { type: 'low_stock', message: 'Producto con stock bajo' },
    ];

    const notificationBadge = document.getElementById('notification-badge');
    if (notificationBadge && notifications.length > 0) {
      notificationBadge.textContent = notifications.length;
      notificationBadge.style.display = 'inline-flex';
    }
  }

  setInterval(checkNotifications, 30000);
  checkNotifications();
});
