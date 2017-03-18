var CACHE_NAME = 'montaner-dependencies-cache';
// Files required to make this app work offline
var REQUIRED_FILES = [
    "https://lesliecordier.github.io/mtnr/index.html", 
    "https://lesliecordier.github.io/mtnr/css/jquery.mobile-1.4.5.css", 
    "https://lesliecordier.github.io/mtnr/css/style.css", 
    "https://lesliecordier.github.io/mtnr/js/jquery.js", 
    "https://lesliecordier.github.io/mtnr/js/xls.core.min.js", 
    "https://lesliecordier.github.io/mtnr/js/alasql.min.js", 
    "https://lesliecordier.github.io/mtnr/js/jquery.preload.min.js", 
    "https://lesliecordier.github.io/mtnr/js/jquery.mobile-1.4.5.min.js", 
    "https://lesliecordier.github.io/mtnr/js/jquery.tablesorter.min.js", 
    "https://lesliecordier.github.io/mtnr/js/index.js", 
    "https://lesliecordier.github.io/mtnr/js/loader.js", 
    "https://lesliecordier.github.io/mtnr/js/indexeddb.js", 
    "https://lesliecordier.github.io/mtnr/js/fonctions.carte.js", 
    "https://lesliecordier.github.io/mtnr/images/home.png", 
    "https://lesliecordier.github.io/mtnr/images/vignoble.jpg", 
    "https://lesliecordier.github.io/mtnr/images/verres-vins.jpg", 
    "https://lesliecordier.github.io/mtnr/images/sprites.gif", 
    "https://lesliecordier.github.io/mtnr/images/cepage.jpg",   
    "https://lesliecordier.github.io/mtnr/images/fond-accueil.jpg", 
    "https://lesliecordier.github.io/mtnr/icones/doc.png", 
    "https://lesliecordier.github.io/mtnr/icones/desc.gif", 
    "https://lesliecordier.github.io/mtnr/icones/bg.gif"]

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
    event.respondWith(fromCache(event.request));
    event.waitUntil(update(event.request));
});

self.addEventListener('activate', function (event) {
    console.log('[activate] Activating ServiceWorker!');
    // Calling claim() to force a “controllerchange” event on navigator.serviceWorker
    console.log('[activate] Claiming this ServiceWorker!');
    event.waitUntil(self.clients.claim());
});

self.addEventListener('message', function(event) {
    var data = event.data;
    console.dir(data);
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
          updateRequiredFiles(response.url);
          return cache.put(request, response);
        });
  });
}

function updateRequiredFiles(url) {
    if(REQUIRED_FILES.indexOf(url) === -1){
        REQUIRED_FILES.push(url);
    }
}