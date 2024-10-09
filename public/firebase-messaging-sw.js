/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyCaS6bBeG5x-IEB6TK-TWShTppUwG89CyM",
    authDomain: "tukuklik-c8c32.firebaseapp.com",
    projectId: "tukuklik-c8c32",
    storageBucket: "tukuklik-c8c32.appspot.com",
    messagingSenderId: "16050192246",
    appId: "1:16050192246:web:4b9a455061dfbe4bacc457"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Notifikasi Dari Tukuklik";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "https://tukuklik.com/public/uploads/settings/639888cb0f3c3.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});