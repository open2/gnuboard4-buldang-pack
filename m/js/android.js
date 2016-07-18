var app = {
    exitApp: false,
    interval: null,
    app_started: false,
    push: null,
    device: {},

    // Application Constructor
    initialize: function () {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function () {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function () {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function (id) {
        //console.log('Received Event: ' + id);
        //document.addEventListener("backbutton", app.back, false);
        document.addEventListener("online", app.toggleCon, false);
        document.addEventListener("offline", app.toggleCon, false);

        if (navigator.network.connection.type == Connection.NONE) {
            window.plugins.toast.showLongBottom("인터넷에 연결할 수 없습니다.");
        } else {
            // 첫 실행시
            app.startApp();
        }
    },
    toggleCon: function (e) {
        //console.log('toggleCon: ' + e.type);
        if (e.type == "offline") {
            window.plugins.toast.showLongBottom("인터넷에 연결할 수 없습니다.");
        } else {
            // 네트워크가 꺼져있다가 다시 켜진 경우
        }
    },
    startApp: function () {
        if (app.app_started === false) {
            // 알림수 배지에 적용
            var notification_count = document.getElementById('notification_count');
            if (notification_count) {
                cordova.plugins.notification.badge.set(
                    Number(notification_count.innerHTML)
                );
            }

            // 푸시 -  앱 자체에서 보여주므로 웹단에선 생략
            /*
            app.push = PushNotification.init({
                android: {
                    senderID: "401748780659",
                    icon: 'ic_memory_black_48dp',
                    iconColor: 'black'
                }
            });

             app.push.on('notification', function (data) {
             window.plugins.toast.showLongTop(data.title + "\n" + data.message);
             });

            app.push.on('error', function (e) {
                console.log(e.message);
            });
             */

            app.app_started = true;
        }
    }
};

app.initialize();
