<?php 
    session_start();//セッション開始
    $_SESSION=array();//セッション変数を空にする
    if(isset($_COOKIE[session_name()])){//ブラウザのcookie内にsession_idが残っていれば削除
        setcookie(session_name(),'',time()-42000,'/');
    }
    session_destroy();//セッション破棄
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body {
                font-family:"ＭＳ Ｐゴシック";
            }
            h1{
                color:#3399FF;
                font-family:"fantasy";
                background: #CCFFCC;
            }
            h2{
                color:#66CCff;
                font-family:"fantasy";
            }
            li{
                background-color:#FFEEFF;
            }
            form{
                background-color:#DDFFFF;
            }
        </style>
    </head>
    <body>
        <h1>ふわふわちゃんねる</h1>
        
        <a href="admin_login_check.php">ログイン画面に戻る</a><br>
        <a href="toppage.php">topに戻る</a>

    </body>
</html>