<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>スレッド追加完了</title>
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
                /*background-color:#DDFFFF;*/
            }
        </style>
    </head>
    <body>
        <h1>ふわふわちゃんねる</h1>
        <?php
            try{
                ///////////////////////////////////////////////////////////////////////////////////////////
                //他ページからの値の取得
                ///////////////////////////////////////////////////////////////////////////////////////////
                require_once('../common/common.php');
                $post=sanitize($_POST);
                $tsuho_kakiko_id=$post['kakiko_id'];
                $url=$post['url'];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //データベースとの接続
                ///////////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


                ///////////////////////////////////////////////////////////////////////////////////////////
                //通報フラグの値を1に変更
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='UPDATE kakiko_list SET tsuho_flag=1 WHERE kakiko_id=?';
                $stmt=$dbh->prepare($sql);
                $data[]=$tsuho_kakiko_id;
                $stmt->execute($data);
                
                $dbh=null;


                ///////////////////////////////////////////////////////////////////////////////////////////
                //表示部分
                ///////////////////////////////////////////////////////////////////////////////////////////
                print '通報完了しました。<br/>';
                print'<a href="'.$url.'">スレッドに戻る</a><br><br>';
                print'<a href="toppage.php">トップに戻る</a>';

            }


            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけいたしております。';
                exit();
            }

        ?>

    </body>
</html>