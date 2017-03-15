var CACHE_NAME = 'montaner-dependencies-cache';
// Files required to make this app work offline
var REQUIRED_FILES = [
    'index.html',
    'css/style.css',
    'css/jquery.mobile-1.4.5.css',
    'js/jquery.js',
    'js/jquery.mobile-1.4.5.min.js',
    'js/jquery.preload.min.js',
    'js/jquery.tablesorter.min.js',
    'js/fonctions.carte.js',
    'js/indexeddb.js',
    'js/xls.core.min.js',
    'js/alasql.min.js',
    'js/loader.js',
    'docs/MONTANER-PUGET.pdf',
    'docs/domaine-de-la-croix.pdf',
    'docs/M-CHAPOUTIER-BELLERUCHE-Rouge.pdf',
    'docs/M-CHAPOUTIER-LA-PETITE-RUCHE-Rouge.pdf',
    'docs/M-CHAPOUTIER-LA-BERNARDINE-Rouge.pdf',
    'docs/clos-real.pdf',
    'images/cepage.jpg',
    'images/vignoble.jpg',
    'images/verres-vins.jpg',
    'images/up.png',
    'images/home.png',
    'images/fond-accueil.jpg',
    'images/down.png',
    'images/sprites.gif',
    'icones/doc.png',
    'icones/desc.gif',
    'icones/asc.gif',
    'icones/bg.gif',
    'vins.xls'
];

self.addEventListener('install', function (event) {

    // Perform install step: loading each required file into cache
    event.waitUntil(
            caches.open(CACHE_NAME)
            .then(function (cache) {
                // Add all offline dependencies to the cache
                console.log('[install] Caches opened, adding all core components' +
                        'to cache');
                return cache.addAll(REQUIRED_FILES);
            })
            .then(function () {
                console.log('[install] All required resources have been cached, ' +
                        'we\'re good!');
                return self.skipWaiting();
            })
            );
});
self.addEventListener('fetch', function (event) {
    event.respondWith(
            caches.match(event.request)
            .then(function (response) {
                // Cache hit - return the response from the cached version
                if (response) {
                    console.log(
                            '[fetch] Returning from ServiceWorker cache: ',
                            event.request.url
                            );
                    return response;
                }
                // Not in cache - return the result from the live server fetch is essentially a “fallback”
                console.log('[fetch] Returning from server: ', event.request.url);
                return fetch(event.request);
            }
            )
            );
});
self.addEventListener('activate', function (event) {
    console.log('[activate] Activating ServiceWorker!');
    // Calling claim() to force a “controllerchange” event on navigator.serviceWorker
    console.log('[activate] Claiming this ServiceWorker!');
    event.waitUntil(self.clients.claim());
});