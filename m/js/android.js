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
        document.addEventListener("backbutton", app.back, false);
        document.addEventListener("online", app.toggleCon, false);
        document.addEventListener("offline", app.toggleCon, false);

        if (navigator.network.connection.type == Connection.NONE) {
            window.plugins.toast.showLongBottom("인터넷에 연결할 수 없습니다.");
        } else {
            // 첫 실행시
            app.startApp();
        }
    },
    back: function (e) {
        e.preventDefault();
        // 메인 페이지에서는 뒤로가기 두번 누르면 앱 종료
        if (window.location.pathname === "/") {
            if (app.exitApp) {
                clearInterval(app.interVal);
                navigator.app.exitApp();
            } else {
                app.exitApp = true;
                app.backInterval();
                window.plugins.toast.showShortBottom("'뒤로'버튼 한번 더 누르시면 종료됩니다.");
            }
        } else {
            window.history.back(1);
        }
    },
    backInterval: function () {
        if (app.interval !== null) {
            clearInterval(app.interval);
        }
        app.interval = setInterval(function () {
            app.exitApp = false;
        }, 2000);
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
            cordova.plugins.notification.badge.set(
                Number(document.getElementById('notification_count').innerHTML)
            );

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
