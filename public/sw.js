/**
 * SIMPEG-SMART Service Worker
 * Handles push notifications & offline caching
 */

const CACHE_NAME = 'simpeg-smart-v1';

// Install
self.addEventListener('install', event => {
    self.skipWaiting();
});

// Activate
self.addEventListener('activate', event => {
    event.waitUntil(clients.claim());
});

// Push Notification
self.addEventListener('push', event => {
    let data = { title: 'SIMPEG-SMART', body: 'Ada pemberitahuan baru', icon: '/images/logo-icon.png' };

    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body || data.pesan || 'Ada pemberitahuan baru',
        icon: data.icon || '/images/logo-icon.png',
        badge: '/images/logo-icon.png',
        vibrate: [200, 100, 200],
        tag: data.tag || 'simpeg-notif-' + Date.now(),
        data: { url: data.url || data.tautan || '/' },
        actions: [
            { action: 'open', title: 'Buka' },
            { action: 'close', title: 'Tutup' }
        ],
        requireInteraction: data.penting || false,
    };

    event.waitUntil(
        self.registration.showNotification(data.title || data.judul || 'SIMPEG-SMART', options)
    );
});

// Notification click
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'close') return;

    const url = event.notification.data?.url || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
            for (const client of clientList) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});
