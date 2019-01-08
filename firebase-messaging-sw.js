importScripts('https://www.gstatic.com/firebasejs/5.7.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.7.2/firebase-messaging.js');

firebase.initializeApp({ 'messagingSenderId': '549561555496' });

var messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'OKBABE+ Customer Services';
    const notificationOptions = {
        body: 'You got a new message.',
        icon: '/okbabe-192x192.png',
        image:'/okbabe-192x192.png',
        requireInteraction: true
    };

    registration.showNotification(notificationTitle, notificationOptions);
});