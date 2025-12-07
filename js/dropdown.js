document.addEventListener('DOMContentLoaded', function () {
  const dropdowns = document.querySelectorAll('.dropdown');

  dropdowns.forEach((dropdown) => {
    const dropbtn = dropdown.querySelector('.dropbtn');
    const dropdownContent = dropdown.querySelector('.dropdown-content');

    dropbtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const isTouchDevice =
        'ontouchstart' in window || navigator.maxTouchPoints > 0;

      if (isTouchDevice) {
        dropdown.classList.toggle('active');

        document.querySelectorAll('.dropdown').forEach((otherDropdown) => {
          if (otherDropdown !== dropdown) {
            otherDropdown.classList.remove('active');
          }
        });
      } else {
        const isVisible = dropdownContent.style.display === 'block';

        document.querySelectorAll('.dropdown-content').forEach((content) => {
          if (content !== dropdownContent) {
            content.style.display = 'none';
          }
        });

        dropdownContent.style.display = isVisible ? 'none' : 'block';
      }
    });

    document.addEventListener('click', function (e) {
      if (!dropdown.contains(e.target)) {
        const isTouchDevice =
          'ontouchstart' in window || navigator.maxTouchPoints > 0;

        if (isTouchDevice) {
          dropdown.classList.remove('active');
        } else {
          dropdownContent.style.display = 'none';
        }
      }
    });

    dropdownContent.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', function () {
        const isTouchDevice =
          'ontouchstart' in window || navigator.maxTouchPoints > 0;

        if (isTouchDevice) {
          dropdown.classList.remove('active');
        } else {
          dropdownContent.style.display = 'none';
        }
      });
    });

    if ('ontouchstart' in window) {
      window.addEventListener('scroll', function () {
        dropdown.classList.remove('active');
        dropdownContent.style.display = 'none';
      });
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.dropdown-content').forEach((content) => {
        content.style.display = 'none';
      });
      document.querySelectorAll('.dropdown').forEach((dropdown) => {
        dropdown.classList.remove('active');
      });
    }
  });
});
