document.addEventListener('DOMContentLoaded', function () {
  console.log('Carrito JS cargado');

  const cantidadForms = document.querySelectorAll('.cantidad-form');

  cantidadForms.forEach((form) => {
    const input = form.querySelector('.cantidad-input');
    const menosBtn = form.querySelector('button[value="menos"]');
    const masBtn = form.querySelector('button[value="mas"]');

    menosBtn.addEventListener('click', function (e) {
      let value = parseInt(input.value);
      if (value > 1) {
        input.value = value - 1;
      }
    });

    masBtn.addEventListener('click', function (e) {
      let value = parseInt(input.value);
      if (value < 99) {
        input.value = value + 1;
      }
    });
  });

  const eliminarForms = document.querySelectorAll('.eliminar-form');
  eliminarForms.forEach((form) => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const item = this.closest('.carrito-item');
      const productoNombre = item.querySelector('h3').textContent;

      if (confirm(`¿Eliminar "${productoNombre}" del carrito?`)) {
        item.classList.add('removing');

        setTimeout(() => {
          this.submit();
        }, 300);
      }
    });
  });

  const vaciarForm = document.querySelector('.vaciar-form');
  if (vaciarForm) {
    vaciarForm.addEventListener('submit', function (e) {
      if (
        !confirm(
          '¿Estás seguro de vaciar todo el carrito? Esta acción no se puede deshacer.'
        )
      ) {
        e.preventDefault();
      }
    });
  }

  function updateItemSubtotal(item) {
    const cantidad = parseInt(item.querySelector('.cantidad-input').value);
    const precioUnitario = parseFloat(
      item
        .querySelector('.precio-unitario')
        .textContent.replace('$', '')
        .replace(' c/u', '')
    );
    const subtotalElement = item.querySelector('.subtotal');

    if (cantidad && precioUnitario && subtotalElement) {
      const subtotal = cantidad * precioUnitario;
      subtotalElement.textContent = '$' + subtotal.toFixed(2);
      updateCartTotal();
    }
  }

  function updateCartTotal() {
    console.log('Actualizando total del carrito...');
  }

  if (document.getElementById('php-notification-toast')) {
    setTimeout(() => {
      const toast = document.getElementById('php-notification-toast');
      const type = toast.getAttribute('data-type');
      const title = toast.getAttribute('data-title');
      const message = toast.getAttribute('data-message');

      if (typeof showNotification === 'function') {
        showNotification(type, title, message);
      }
    }, 100);
  }
});
