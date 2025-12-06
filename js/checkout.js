document.addEventListener('DOMContentLoaded', function () {
  console.log('Checkout JS cargado');

  const tarjetaRadio = document.getElementById('pago-tarjeta');
  const tarjetaDetails = document.getElementById('tarjeta-details');

  function toggleCardDetails() {
    if (tarjetaRadio.checked) {
      tarjetaDetails.classList.add('active');
    } else {
      tarjetaDetails.classList.remove('active');
    }
  }

  toggleCardDetails();

  const paymentMethods = document.querySelectorAll('input[name="metodo_pago"]');
  paymentMethods.forEach((method) => {
    method.addEventListener('change', toggleCardDetails);
  });

  const numeroTarjeta = document.getElementById('numero_tarjeta');
  if (numeroTarjeta) {
    numeroTarjeta.addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      value = value.replace(/(\d{4})/g, '$1 ').trim();
      e.target.value = value.substring(0, 19);
    });
  }

  const vencimiento = document.getElementById('vencimiento');
  if (vencimiento) {
    vencimiento.addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
      }
      e.target.value = value.substring(0, 5);
    });
  }

  const checkoutForm = document.getElementById('checkout-form');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function (e) {
      if (tarjetaRadio.checked) {
        const numero = document
          .getElementById('numero_tarjeta')
          .value.replace(/\D/g, '');
        const nombre = document.getElementById('nombre_tarjeta').value.trim();
        const venc = document.getElementById('vencimiento').value;
        const cvv = document.getElementById('cvv').value;

        if (numero.length !== 16) {
          e.preventDefault();
          alert('Por favor, ingresa un número de tarjeta válido (16 dígitos)');
          return false;
        }

        if (!nombre) {
          e.preventDefault();
          alert('Por favor, ingresa el nombre que aparece en la tarjeta');
          return false;
        }

        if (!vencimientoValido(venc)) {
          e.preventDefault();
          alert('Por favor, ingresa una fecha de vencimiento válida (MM/AA)');
          return false;
        }

        if (cvv.length !== 3) {
          e.preventDefault();
          alert('Por favor, ingresa un CVV válido (3 dígitos)');
          return false;
        }
      }

      const btnPagar = document.querySelector('.btn-pagar');
      if (btnPagar) {
        btnPagar.innerHTML = '<span class="btn-icon">⏳</span> Procesando...';
        btnPagar.disabled = true;
      }
    });
  }

  function vencimientoValido(vencimiento) {
    if (!vencimiento.match(/^\d{2}\/\d{2}$/)) return false;

    const [mes, anio] = vencimiento.split('/').map(Number);
    const ahora = new Date();
    const anioActual = ahora.getFullYear() % 100;
    const mesActual = ahora.getMonth() + 1;

    if (mes < 1 || mes > 12) return false;
    if (anio < anioActual) return false;
    if (anio === anioActual && mes < mesActual) return false;

    return true;
  }
});
