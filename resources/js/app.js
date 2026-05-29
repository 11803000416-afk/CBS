import './bootstrap';
import './password-toggle.js';

// Application initialization
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('[data-theme-toggle]');
    const root = document.documentElement;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const applyTheme = (theme) => {
        root.setAttribute('data-theme', theme);
        root.classList.toggle('dark', theme === 'dark');

        toggles.forEach((toggle) => {
            const sun = toggle.querySelector('[data-theme-icon-sun]');
            const moon = toggle.querySelector('[data-theme-icon-moon]');
            const label = toggle.querySelector('[data-theme-label]');

            if (sun) sun.classList.toggle('hidden', theme !== 'dark');
            if (moon) moon.classList.toggle('hidden', theme === 'dark');
            if (label) label.textContent = theme === 'dark' ? 'Light' : 'Dark';
        });
    };

    const currentTheme = root.getAttribute('data-theme') || 'light';
    applyTheme(currentTheme);

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const nextTheme = (root.getAttribute('data-theme') || 'light') === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme);
            try {
                localStorage.setItem('cbs-theme', nextTheme);
            } catch (error) {
                // Ignore storage failures gracefully.
            }
        });
    });

    // Accessible notification bell with polling and read-state updates.
    const bellBtn = document.getElementById('notificationBellBtn');
    const bellDropdown = document.getElementById('notificationDropdown');
    const bellList = document.getElementById('notificationList');
    const bellBadge = document.getElementById('notificationUnreadBadge');
    const markAllBtn = document.getElementById('notificationsMarkAllRead');

    if (bellBtn && bellDropdown && bellList && bellBadge) {
        const setBadge = (count) => {
            bellBadge.textContent = String(count);
            bellBadge.classList.toggle('hidden', count <= 0);
            bellBtn.setAttribute('aria-label', count > 0 ? `Open notifications (${count} unread)` : 'Open notifications');
        };

        const closeDropdown = () => {
            bellDropdown.classList.add('hidden');
            bellBtn.setAttribute('aria-expanded', 'false');
        };

        const openDropdown = () => {
            bellDropdown.classList.remove('hidden');
            bellBtn.setAttribute('aria-expanded', 'true');
        };

        const markRead = async (id) => {
            await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
        };

        const renderNotifications = (items) => {
            if (!items.length) {
                bellList.innerHTML = '<li class="px-4 py-3 text-sm text-gray-500">No notifications yet.</li>';
                return;
            }

            bellList.innerHTML = items.map((n) => {
                const unreadClass = n.unread ? 'bg-blue-50' : 'bg-white';
                const dot = n.unread
                    ? '<span class="mt-1 inline-block h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>'
                    : '<span class="mt-1 inline-block h-2 w-2 rounded-full bg-transparent" aria-hidden="true"></span>';

                return `
                    <li class="border-b border-gray-100 last:border-b-0 ${unreadClass}">
                        <a href="${n.url}" data-notification-id="${n.id}" class="notification-item block px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 focus:outline-none">
                            <div class="flex items-start gap-2">
                                ${dot}
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900">${n.title}</p>
                                    <p class="mt-0.5 text-xs text-gray-700">${n.message}</p>
                                    <p class="mt-1 text-[11px] text-gray-500">${n.time}</p>
                                </div>
                            </div>
                        </a>
                    </li>
                `;
            }).join('');

            bellList.querySelectorAll('.notification-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const id = item.getAttribute('data-notification-id');
                    if (id) {
                        markRead(id).catch(() => {});
                    }
                });
            });
        };

        const fetchNotifications = async () => {
            try {
                const res = await fetch('/notifications', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) return;

                const payload = await res.json();
                setBadge(payload.unread_count || 0);
                renderNotifications(payload.notifications || []);
            } catch (error) {
                // Ignore temporary network failures for polling.
            }
        };

        bellBtn.addEventListener('click', () => {
            if (bellDropdown.classList.contains('hidden')) {
                openDropdown();
                fetchNotifications();
            } else {
                closeDropdown();
            }
        });

        if (markAllBtn) {
            markAllBtn.addEventListener('click', async () => {
                try {
                    await fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });
                    fetchNotifications();
                } catch (error) {
                    // Ignore temporary network failures for action.
                }
            });
        }

        document.addEventListener('click', (event) => {
            if (!bellDropdown.contains(event.target) && !bellBtn.contains(event.target)) {
                closeDropdown();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeDropdown();
            }
        });

        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    }
});
