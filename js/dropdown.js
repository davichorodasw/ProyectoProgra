// dropdown.js - VERSIÓN SIMPLIFICADA Y FUNCIONAL
console.log('Dropdown JS loading...');

document.addEventListener('DOMContentLoaded', function () {
  console.log('Initializing dropdowns...');

  const dropdowns = document.querySelectorAll('.dropdown');
  console.log(`Found ${dropdowns.length} dropdown(s)`);

  if (dropdowns.length === 0) {
    console.warn('No dropdown elements found');
    return;
  }

  // Función para cerrar todos los dropdowns
  function closeAllDropdowns(excludeDropdown = null) {
    dropdowns.forEach((dropdown) => {
      if (dropdown === excludeDropdown) return;

      dropdown.classList.remove('active');
      const content = dropdown.querySelector('.dropdown-content');
      if (content) {
        content.style.display = 'none';
      }
    });
  }

  // Configurar cada dropdown
  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector('.dropbtn');
    const content = dropdown.querySelector('.dropdown-content');

    if (!button || !content) {
      console.warn('Dropdown missing required elements');
      return;
    }

    // Asegurar que el contenido esté oculto inicialmente
    content.style.display = 'none';

    // Manejador de clic único
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      console.log('Dropdown button clicked');

      const isActive = dropdown.classList.contains('active');

      // Cerrar todos los demás dropdowns primero
      closeAllDropdowns(isActive ? null : dropdown);

      // Alternar estado del dropdown actual
      if (!isActive) {
        // Abrir este dropdown
        dropdown.classList.add('active');
        content.style.display = 'block';
        console.log('Dropdown opened');
      } else {
        // Cerrar este dropdown
        dropdown.classList.remove('active');
        content.style.display = 'none';
        console.log('Dropdown closed');
      }
    });

    // Cerrar al hacer clic en enlaces dentro del dropdown
    const links = content.querySelectorAll('a');
    links.forEach((link) => {
      link.addEventListener('click', function () {
        setTimeout(() => {
          dropdown.classList.remove('active');
          content.style.display = 'none';
        }, 100);
      });
    });
  });

  // Cerrar dropdowns al hacer clic fuera
  document.addEventListener('click', function (e) {
    const clickedDropdown = e.target.closest('.dropdown');
    if (!clickedDropdown) {
      closeAllDropdowns();
    }
  });

  // Cerrar con tecla Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAllDropdowns();
    }
  });

  console.log('Dropdown initialization complete');
});
