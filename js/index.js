//Register the ServiceWorker

if ('serviceWorker' in navigator) { 
  console.log('registration...');
  navigator.serviceWorker.register('service-worker.js', {
    scope: './'
  }).then(function(registration) {
    console.log('The service worker has been registered ', registration);
  });
}

//Listen for claiming of our ServiceWorker

 
navigator.serviceWorker.addEventListener('controllerchange', function(event) {
  console.log(
    '[controllerchange] A "controllerchange" event has happened ' +
    'within navigator.serviceWorker: ', event
  );

//Listen for changes in the state of our ServiceWorker

 
  navigator.serviceWorker.controller.addEventListener('statechange',
    function() {
      console.log('[controllerchange][statechange] ' +
        'A "statechange" has occured: ', this.state
      );
      

//If the ServiceWorker becomes “activated”, let the user know they can go offline!

 
      if (this.state === 'activated') {

        alert("Le mode hors ligne peut être activé !");
      }
    }
  );
});

function sendMessage(message) {
  // This wraps the message posting/response in a promise, which will
  // resolve if the response doesn't contain an error, and reject with
  // the error if it does. If you'd prefer, it's possible to call
  // controller.postMessage() and set up the onmessage handler
  // independently of a promise, but this is a convenient wrapper.
  return new Promise(function(resolve, reject) {
    var messageChannel = new MessageChannel();
    messageChannel.port1.onmessage = function(event) {
      if (event.data.error) {
        reject(event.data.error);
      } else {
        resolve(event.data);
      }
    };

    // This sends the message data as well as transferring
    // messageChannel.port2 to the service worker.
    // The service worker can then use the transferred port to reply
    // via postMessage(), which will in turn trigger the onmessage
    // handler on messageChannel.port1.
    // See
    // https://html.spec.whatwg.org/multipage/workers.html#dom-worker-postmessage
    navigator.serviceWorker.controller.postMessage(message, [messageChannel.port2]);
  });
}

worker.postMessage(data);