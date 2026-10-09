const CACHE_NAME = "baznas-sumbawa-cache-v1";
const urlsToCache = [
  "./",
  "./manifest.json",
  "./asset/images/pwa-icon-192.png",
  "./asset/images/pwa-icon-512.png"
];

// Install Event
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate Event
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event (Offline Fallback capability)
self.addEventListener("fetch", event => {
  // Only handle GET requests to avoid issues with POST/PUT forms
  if (event.request.method !== "GET") return;

  event.respondWith(
    fetch(event.request)
      .catch(() => {
        return caches.match(event.request);
      })
  );
});
