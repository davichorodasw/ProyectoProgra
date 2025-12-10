function showNotification(
  type,
  title,
  message,
  duration = 5000,
  redirectUrl = null
) {
  const notification = document.createElement('div');
  notification.id = 'dynamic-notification';
  notification.className = `notification ${type}`;

  let icon = '✓';
  if (type === 'error') icon = '✗';
  if (type === 'warning') icon = '⚠';
  if (type === 'info') icon = 'ℹ';

  notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${icon}</span>
            <div class="notification-text">
                <strong>${title}</strong>
                <p>${message}</p>
            </div>
        </div>
        <div class="notification-progress"></div>
    `;

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.display = 'block';
  }, 10);

  setTimeout(() => {
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(-100%)';

    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }

      if (redirectUrl) {
        window.location.href = redirectUrl;
      }
    }, 500);
  }, duration);
}

function showSuccessAndRedirect(title, message, redirectUrl) {
  showNotification('success', title, message, 5000, redirectUrl);
}

function showError(title, message) {
  showNotification('error', title, message, 5000);
}

function checkForPHPNotification() {
  const notificationEl = document.getElementById('php-notification');
  if (notificationEl) {
    const type = notificationEl.dataset.type || 'success';
    const title = notificationEl.dataset.title || 'Notificación';
    const message = notificationEl.dataset.message || '';
    const redirectUrl = notificationEl.dataset.redirect || null;
    const duration = parseInt(notificationEl.dataset.duration) || 5000;

    showNotification(type, title, message, duration, redirectUrl);

    notificationEl.parentNode.removeChild(notificationEl);
  }
}

document.addEventListener('DOMContentLoaded', checkForPHPNotification);
