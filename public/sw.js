// Activar inmediatamente sin esperar a que se cierren pestañas anteriores
self.addEventListener('install', function(event) {
    event.waitUntil(self.skipWaiting());
});

// Tomar control de todas las pestañas abiertas inmediatamente
self.addEventListener('activate', function(event) {
    event.waitUntil(self.clients.claim());
});

// Push: funciona con pantalla apagada y app en segundo plano
self.addEventListener('push', function(event) {
    if (!event.data) return;

    const data = event.data.json();
    const title = data.title || 'Nueva notificación';
    const options = {
        body: data.body || '',
        icon: data.icon || '/images/logo_arixna1024512_min.webp',
        badge: '/images/logo_arixna1024512_min.webp',
        data: data.data || {},
        vibrate: [200, 100, 200],
        requireInteraction: true,
        tag: data.tag || 'arixna-' + Date.now(),
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const url = event.notification.data && event.notification.data.url
        ? event.notification.data.url
        : '/admin';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (const client of clientList) {
                if (client.url.includes('/admin') && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});
