self.addEventListener("install", (event) => {
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
});

self.addEventListener("fetch", (event) => {
    event.respondWith(fetch(event.request));
});
