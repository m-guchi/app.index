const publicVapidKey = "BJeSEiTNZCil5CbzU4zsSd_kYMlEOBgDihoV9bE-X-lo8sU_pwhk2uMItzWkFwmcQAgqMyc2VViQzQCBP4o8PBA";
    const firebaseConfig = {
        apiKey: "AIzaSyA1nUd_Oe7K_-IkXbmKEvEq9BhYQThdE18",
        authDomain: "minagu-app-work.firebaseapp.com",
        projectId: "minagu-app-work",
        storageBucket: "minagu-app-work.appspot.com",
        messagingSenderId: "653833506374",
        appId: "1:653833506374:web:0c769c8445697f32fb0447",
        measurementId: "G-H9KYXPX3NT"
    };
    const app = firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging(app);

    function getFCMToken(){
        Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            messaging.getToken({vapidKey:publicVapidKey}).then((token) => {
            if (token) {
                console.log("currentToken:", token);
                //tokenをサーバー等に送る
            } else {
                console.log("登録トークンがありません．");
                //認証画面
            }
            }).catch((err) => {
                console.error("トークンの取得に失敗しました: ",err);
            });
        } else {
            console.log('通知する権限がありません．');
        }
        })
    };

    var btn = document.getElementById("clk_push_btn");
    console.log(btn);
    btn.addEventListener("click", function() {
        console.log("「通知を許可」")
        getFCMToken();
    },false);