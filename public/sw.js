self.addEventListener('push', function(event) {
    if (!event.data) return;

    const data = event.data.json();
    const title = data.title || 'Nueva notificación';
    const options = {
        body: data.body || '',
        icon: data.icon || '/images/logo_arixna.png',
        badge: '/images/logo_arixna.png',
        data: data.data || {},
        vibrate: [200, 100, 200],
        requireInteraction: true,
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
