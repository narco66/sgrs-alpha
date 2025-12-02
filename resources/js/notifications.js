import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const userIdElement = document.querySelector('meta[name="user-id"]');
    if (!userIdElement) {
        return;
    }

    const userId = userIdElement.getAttribute('content');
    if (!userId || !window.Echo) {
        return;
    }

    const badgeEl = document.querySelector('#notification-badge-count');
    const listEl  = document.querySelector('#notification-dropdown-list');
    const toastContainer = document.querySelector('#notification-toast-container');

    function incrementBadge() {
        if (!badgeEl) return;

        let current = parseInt(badgeEl.innerText || '0', 10);
        if (isNaN(current)) current = 0;
        current += 1;

        badgeEl.innerText = current > 9 ? '9+' : current.toString();
        badgeEl.classList.remove('d-none');
    }

    function addNotificationToList(data) {
        if (!listEl) return;

        const li = document.createElement('li');
        li.innerHTML = `
            <a href="/meetings/${data.meeting_id}" class="dropdown-item small">
                <div class="fw-semibold">${data.title}</div>
                <div class="text-muted">
                    ${data.meeting_type ?? 'Réunion statutaire'}
                    ${data.room ? ' • ' + data.room : ''}
                </div>
            </a>
        `;
        listEl.prepend(li);
    }

    function showToast(data) {
        if (!toastContainer) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'toast align-items-center text-bg-primary border-0 mb-2';
        wrapper.setAttribute('role', 'alert');
        wrapper.setAttribute('aria-live', 'assertive');
        wrapper.setAttribute('aria-atomic', 'true');

        wrapper.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>Rappel de réunion :</strong> ${data.title}<br>
                    ${data.meeting_type ?? 'Réunion statutaire'}
                    ${data.start_at ? ' – ' + data.start_at : ''}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        `;

        toastContainer.appendChild(wrapper);

        const toast = new bootstrap.Toast(wrapper, { delay: 8000 });
        toast.show();
    }

    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            // Notification broadcastée par MeetingReminderNotification
            incrementBadge();
            addNotificationToList(notification);
            showToast(notification);
        });
});
