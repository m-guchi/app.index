<?php

session_start();


include_once(__DIR__."/db.php");
include_once(__DIR__."/../auth/check.php");
$db = new AppPage\DB();
$check = new Check();

//ログインユーザーの検証
$is_login = false;
$user_id = $check->fetch_user_from_cookie();
if($user_id){
    try{
        $sql = "SELECT user_name FROM users WHERE id = :id";
        $sth = $db->pdo->prepare($sql);
        $sth->bindParam(':id', $user_id);
        $sth->execute();
    }catch(PDOException $e){
        echo $e;
        return false;
    }
    $user_data = $sth->fetch(PDO::FETCH_ASSOC);
    if($user_data===false){
        $user_name = false;
    }else{
        $user_name = $user_data["user_name"];
        $is_login = true;
    }
}else{
    $user_name = false;
}

//ページ一覧を取得
try{
    $sql = "SELECT * FROM pages";
    $sth = $db->pdo->prepare($sql);
    $sth->execute();
}catch(PDOException $e){
    echo $e;
    exit;
}
$page_data = $sth->fetchAll(PDO::FETCH_ASSOC);

//表示できるページを取得
if($user_id){
    try{
        $sql = "SELECT page_id FROM scopes WHERE user_id = :user_id and display = true";
        $sth = $db->pdo->prepare($sql);
        $sth->bindParam(':user_id', $user_id);
        $sth->execute();
    }catch(PDOException $e){
        echo $e;
        exit;
    }
    $display_page_id = array_map(function($page){return $page["page_id"];} ,$sth->fetchAll(PDO::FETCH_ASSOC));

    try{
        $sql = "SELECT page_id FROM scopes WHERE user_id = :user_id and access = true";
        $sth = $db->pdo->prepare($sql);
        $sth->bindParam(':user_id', $user_id);
        $sth->execute();
    }catch(PDOException $e){
        echo $e;
        exit;
    }
    $access_page_id = array_map(function($page){return $page["page_id"];} ,$sth->fetchAll(PDO::FETCH_ASSOC));

}else{
    $display_page_id = [];
    $access_page_id = [];
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-MKGFTM8N');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App一覧</title>
    <meta name="description" content="Minamiguchi.KAZUKIが管理するAppの一覧画面です" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="apple-touch-icon" type="image/png" href="apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="icon-192x192.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MKGFTM8N" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="topbar">
        <div class="top__title">App一覧</div>
<?php

if($is_login){
    echo '<a class="top__login" href="https://app.minagu.work/login/">'.$user_name.' で<span class="top__underline">ログイン中</span></a>';
}else{
    echo '<a class="top__login" href="https://app.minagu.work/login/"><span class="top__underline">ログイン</span></a>';
}
?>
    </div>
    <div class="card-group">
<?php
foreach($page_data as $page){
    if($page["anyone_display"]==true || in_array($page["id"], $display_page_id)){
        $no_access = !($page["anyone_access"]==true || in_array($page["id"], $access_page_id));
?>
<?php  if($no_access){ ?>
        <div class="card" style="background-color:#ccc">
<?php  }else{ ?>
        <a class="card" href="<?=$page["url"]?>" target="_blank">
<?php  } ?>
          <div class="card__textbox">
            <div class="card__titletext">
                <?=$page["title_name"]?>
            </div>
            <div class="card__overviewtext">
                <?=$page["overview"]?>
            </div>
<?php if($no_access){ ?>
            <div class="card_noaccess">アクセスが制限されているため、詳細は表示されません。</div>
<?php  }else{ ?>
            <div class="card__urltext">
                <?=$page["url"]?>
            </div>
<?php } ?>
          </div>
<?php  if($no_access){ ?>
        </div>
<?php  }else{ ?>
        </a>
<?php
        }
    }
}
?>
</body>
</html>