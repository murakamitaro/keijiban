<?php 
    session_start();
    session_regenerate_id(true);
    if(!isset($_SESSION['login'])){
        print'ログインしてください.<br>';
        print'<a href="admin_login.php">ログイン画面へ</a>';
        exit();
    }
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
        
        <ul>  
            <?php
            try{
                $kakiko_id=$_GET['kakiko_id'];

                ///////////////////////////////////////////////////////////////////////////////////////////
                //データベースへの接続
                ///////////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                ///////////////////////////////////////////////////////////////////////////////////////////
                //該当する書き込みのレコードを取得し、確認表示
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='SELECT * FROM kakiko_list WHERE kakiko_id=?';
                $stmt=$dbh->prepare($sql);
                $data[]=$kakiko_id;
                $stmt->execute($data);

                $dbh=null;
                
                $rec=$stmt->fetch(PDO::FETCH_ASSOC);
                print $rec['user_name'].':  '.$rec['kakiko_time'].'<br>'.nl2br($rec['main_text']).'<br>';
                if($rec['link']!=''){
                    print '<img src="strage/'.$rec['link'].'" width="300">';
                }

            }

            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけしております。';
                exit();
            }

            ?>
            <form method="post" action="admin_sakujyo_done.php" >
                <input type="hidden" name="kakiko_id"  value=<?php print $rec['kakiko_id'] ;?>>
                <input type="hidden" name="image"  value=<?php print $rec['link'] ;?>>
                <input type="hidden" name="judge"  value="NG">
                <input type="submit" value="＜この書き込みを削除する＞">
            </form>

            <form method="post" action="admin_sakujyo_done.php" >
                <input type="hidden" name="kakiko_id"  value=<?php print $rec['kakiko_id'] ;?>>
                <input type="hidden" name="image"  value=<?php print $rec['link'] ;?>>
                <input type="hidden" name="judge"  value="OK">
                <input type="submit" value="＜通報を取り消す＞"><br/>
            </form>

            <a href="admin_top.php">管理topに戻る</a>

    </body>
</html>