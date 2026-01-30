// Instant worker activation
self.addEventListener('install', evt => self.skipWaiting());
 
// Claim control instantly
self.addEventListener('activate', evt => self.clients.claim());

// Listen to push notifications
self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const sendNotification = body => {
        // you could refresh a notification badge here with postMessage API
        const title = 'LMS login approval';

        return self.registration.showNotification(title, {
            body,
            data: {
                url: 'https://softworld.selfip.com/lms_pwa/index.php'
            },
            priority: 'max'
        });
    };

    if (event.data) {
        const message = event.data.text();
        event.waitUntil(sendNotification(message));
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
