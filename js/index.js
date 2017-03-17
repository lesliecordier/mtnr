//Register the ServiceWorker

if ('serviceWorker' in navigator) { 
  console.log('registration...');
  navigator.serviceWorker.register('service-worker.js', {
    scope: './'
  }).then(function(registration) {
    console.log('The service worker has been registered ', registration);
  });
}

navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
  // Let's see if you have a subscription already
  return serviceWorkerRegistration.pushManager.getSubscription();
})
.then(function(subscription) {
  if (!subscription) {
    // You do not have subscription
  }
  // You have subscription.
  // Send data to service worker
    navigator.serviceWorker.controller.postMessage('urlsPdf');

})

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

