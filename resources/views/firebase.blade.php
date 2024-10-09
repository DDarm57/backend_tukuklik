<script>
    var firebaseConfig = {
        apiKey: "AIzaSyCaS6bBeG5x-IEB6TK-TWShTppUwG89CyM",
        authDomain: "tukuklik-c8c32.firebaseapp.com",
        projectId: "tukuklik-c8c32",
        storageBucket: "tukuklik-c8c32.appspot.com",
        messagingSenderId: "16050192246",
        appId: "1:16050192246:web:4b9a455061dfbe4bacc457"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);
                $.ajax({
                    url: '{{ route("firebase.token") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                    },
                    data: {
                        token: token
                    },
                    success: function (response) {
                        console.log('Firebase Saved Successfully');
                    },
                    error: function (err) {
                        console.log('Error Firebase'+ err);
                    },
                });

            }).catch(function (err) {
                console.log('Error Firebase'+ err);
            });
    }

    initFirebaseMessagingRegistration();

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
            url: "{{ Storage::url('settings/logo.png') }}"
        };
        console.log(payload);
        notification();
        message();
        new Notification(noteTitle, noteOptions);
    });
</script>