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
            <form method="post" action="admin_login_check.php" enctype="multipart/form-data">
                <h2>  ◆管理者ログイン</h2>
                ・ID<br/>
                <input type="text" name="user_id" ><br/>
                ・PASS<br/>
                <input type="password" name="pass"><br/>
                <input type="submit" value="＜確認ページへ進む＞"><br/>
            </form>
            
            <a href="toppage.php">topに戻る</a>

    </body>
</html>