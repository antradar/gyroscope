self.addEventListener('fetch', function(event) {
  event.respondWith(
      caches.match(event.request).then(function(response) {
        return response || fetch(event.request).catch(function(err){
		return caches.match('notes.php');
	});
      }))
});

self.addEventListener('install', function(e) {
	e.waitUntil(
		caches.open('gyroscope').then(function(cache) { //vendor portal specific
			return cache.addAll([
				'imgs/logo.png',
				'imgs/dlogo.png',
				'nano.js',
				'notes.php',
				'notes.php?mode=embed',
				'gsnotes.css',
				'gsnotes.js',
				'validators.js'
			]);
		})
	);
});

