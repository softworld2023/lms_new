// Instant worker activation
self.addEventListener('install', evt => self.skipWaiting());
 
// Claim control instantly
self.addEventListener('activate', evt => self.clients.claim());