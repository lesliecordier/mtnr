
var CACHE_NAME = 'montaner-dependencies-cache';
// Files required to make this app work offline
var REQUIRED_FILES = [];

self.addEventListener('install', function (event) {
    console.log('[install]');
    // Perform install step: loading each required file into cache
    event.waitUntil(
            caches.open(CACHE_NAME)
            .then(function (cache) {
                // Add all offline dependencies to the cache
                console.log('[install] Caches opened, adding all core components ' +
                        'to cache');
                return cache.addAll(REQUIRED_FILES);
            })
            .then(function () {
                console.log('[install] All required resources have been cached, ' +
                        'we\'re good!');
                return self.skipWaiting(); // Attente d'évènement pour mise à jour client actif
            })
            );
});
self.addEventListener('fetch', function (event) {
    event.respondWith(fromCache(event.request)

//        caches.match(event.request).catch(function () {
//            return fetch(event.request).then(function (response) {
//                return caches.open(CACHE_NAME).then(function (cache) {
//                    cache.put(event.request, response.clone());
//                    return response;
//                });
//            });
//        })

            );
    event.waitUntil(update(event.request));
});

self.addEventListener('activate', function (event) {
    console.log('[activate] Activating ServiceWorker!');
    // Calling claim() to force a “controllerchange” event on navigator.serviceWorker
    console.log('[activate] Claiming this ServiceWorker!');
    event.waitUntil(self.clients.claim());
});


function fromCache(request) {
    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.match(request).then(function (matching) {
            return matching || Promise.reject('no-match');
        });
    });
}

function update(request) {
    return caches.open(CACHE_NAME).then(function (cache) {
        return fetch(request).then(function (response) {
          console.dir('url:'+response.url);
          updateRequiredFiles(response.url);
          return cache.put(request, response);
        });
  });
}

function updateRequiredFiles(url) {
    if(REQUIRED_FILES.indexOf(url) === -1){
        REQUIRED_FILES.push(url);
    }
    console.log(REQUIRED_FILES);
}