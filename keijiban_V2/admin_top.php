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
                ///////////////////////////////////////////////////////////////////////////////////////////
                //　データベース接続
                ///////////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                

                ///////////////////////////////////////////////////////////////////////////////////////////
                //　tsuho_flag==1のレコードを取得
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='SELECT * FROM kakiko_list WHERE tsuho_flag=1';
                $stmt=$dbh->prepare($sql);
                $stmt->execute();

                $dbh=null;
                

                ///////////////////////////////////////////////////////////////////////////////////////////
                //　該当するレコードの内容をリスト表示
                ///////////////////////////////////////////////////////////////////////////////////////////
                print '<h2>通報された書き込み一覧</h2>';   //表示文　変数化？
                $count=0;
                while(true){//レコードをひとつづつ取り出してrec(配列)に格納　&リスト表示
                    $rec=$stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        if($count==0){
                            print'●通報なし　平和です!<br>';
                        }
                        break;
                    }
                    print'<li><a href="./admin_sakujyo_check.php?kakiko_id='.$rec['kakiko_id'].'">';//POSTで送る
                    print $rec['user_name'].':  '.$rec['kakiko_time'].':'.$rec['main_text'];
                    print '</a></li>';
                    $count++;
                }
            }

            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけしております。';
                exit();
            }

            ?>
        </ul>
        <br>

        <form method="post" action="tikuword_check.php" enctype="multipart/form-data">
            <a href="./tikutiku_list.csv">★ちくちく言葉リストをダウンロード</a><br/><br/>
            ★ちくちく言葉リストをアップロード<br>
            <input type="file" name="tikulist" >
            <input type="submit" value="アップロード"><br><br>
         </form>
        <br>
        <a href="admin_logout.php">ログアウト</a> <br><br>
        <a href="toppage.php">topに戻る</a>

    </body>
</html>