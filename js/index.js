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

function message() {
    // ONE WAY COMMUNICATION
    if (navigator.serviceWorker.controller) {
        console.log("Sendingage to service worker");
        navigator.serviceWorker.controller.postMessage({
            "command": "oneWayCommunication",
            "message": "Hi, SW"
        });
    } else {
        console.log("No ServiceWorker");
    }
}

message();