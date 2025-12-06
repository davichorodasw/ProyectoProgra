document.addEventListener('DOMContentLoaded', function () {
  console.log('Mi Cuenta JS cargado');

  // Navegación entre secciones
  const navItems = document.querySelectorAll('.nav-item');
  const contentSections = document.querySelectorAll('.content-section');

  navItems.forEach((item) => {
    item.addEventListener('click', function (e) {
      if (this.classList.contains('logout')) return;

      e.preventDefault();

      const targetId = this.getAttribute('data-target');

      // Actualizar navegación
      navItems.forEach((nav) => nav.classList.remove('active'));
      this.classList.add('active');

      // Mostrar sección correspondiente
      contentSections.forEach((section) => {
        section.classList.remove('active');
        if (section.id === targetId) {
          section.classList.add('active');
        }
      });
    });
  });

  // Formatear fechas de pedidos
  const orderDates = document.querySelectorAll('.order-date');
  orderDates.forEach((dateElement) => {
    const dateText = dateElement.textContent;
    if (dateText) {
      const date = new Date(dateText);
      if (!isNaN(date)) {
        dateElement.textContent = date.toLocaleDateString('es-ES', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        });
      }
    }
  });

  // Confirmación para cancelar pedido
  /*const cancelButtons = document.querySelectorAll(
    'button[onclick*="Cancelar Pedido"]'
  );
  cancelButtons.forEach((button) => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const orderCard = this.closest('.order-card');
      const orderNumber = orderCard.querySelector('h4').textContent;

      if (confirm(`¿Estás seguro de que deseas cancelar ${orderNumber}?`)) {
        // Aquí iría la llamada AJAX para cancelar el pedido
        alert('Funcionalidad de cancelación en desarrollo');
      }
    });
  });*/

  // Efecto hover para tarjetas de admin
  const adminCards = document.querySelectorAll('.admin-card');
  adminCards.forEach((card) => {
    card.addEventListener('mouseenter', function () {
      this.style.transform = 'translateY(-3px)';
    });

    card.addEventListener('mouseleave', function () {
      this.style.transform = 'translateY(0)';
    });
  });

  // Validar formularios (si los hay)
  const forms = document.querySelectorAll('form');
  forms.forEach((form) => {
    form.addEventListener('submit', function (e) {
      const requiredFields = this.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = '#e74c3c';
          isValid = false;

          // Resetear color después de 2 segundos
          setTimeout(() => {
            field.style.borderColor = '';
          }, 2000);
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Por favor, completa todos los campos obligatorios');
      }
    });
  });
});
