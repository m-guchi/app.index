<?php
setcookie("token","",time()-24*60*60,"/","app.minagu.work",);

//ログイン認証
$error_msg = false;
if(!empty($_POST["user"]) && !empty($_POST["password"])){
    include(__DIR__."/../../auth/check.php");
    $check = new Check();
    $ret = $check->login($_POST["user"],$_POST["password"]);
    if($ret["ok"]){
        setcookie(
            "token",
            $value=$ret['token'],
            $expires_or_options=time()+$check->token_exp_seconds,
            $path="/",
            $domain="app.minagu.work",
            $secure=true
        );
        if(!empty($_POST["page"])){
            header("Location: ".$_POST["page"]);
            exit;
        }
        header("Location:../");
        exit;
    }else{
        if($ret["error"]==="not_user" || $ret["error"]==="invalid_password"){
            $error_msg = "ユーザーIDまたはパスワードが異なります";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App一覧 | ログイン画面</title>
    <meta name="description" content="制限付きAppを表示するにはログインが必要です" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="style_login.css">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-MKGFTM8N');</script>
<!-- End Google Tag Manager -->
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MKGFTM8N" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div class="topbar">
        <div class="top__title">App一覧 | ログイン画面</div>
        <a class="top__login" href="https://app.minagu.work"><span class="top__underline">App一覧</span></a>
    </div>

    <div class="login">
        <form class="login-container" method="post" action="">
            <p><input type="text" name="user" placeholder="ユーザーID" required></p>
            <p><input type="password" name="password" placeholder="パスワード" required></p>
            <input type="hidden" name="page" value="<?=$_GET["page"]?>">
<?php if(!empty($_GET["c"]) && $_GET["c"]==403){ ?>
            <p style="font-size:12px">ページにアクセスする権限がないため、ログアウトしました。</p>
<?php } ?>
<?php if(!empty($_GET["page"])){ ?>
            <p style="font-size:12px">ログイン後、<?=$_GET["page"]?> に移動します。</p>
<?php } ?>
<?php if($error_msg){ ?>
            <p style="color:red"><?=$error_msg?></p>
<?php } ?>
            <p><input type="submit" value="ログイン"></p>
            <p style="font-size:12px; text-align:center"><a href="https://forms.gle/1DPU6dSgZgjiJyMg8" target="_blank">ログイン申請(Googleフォーム)</p>
        </form>
    </div>
</body>
</html>
