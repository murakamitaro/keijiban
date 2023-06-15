<?php
    session_start();//追加
?>
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
                background-color:#DDFFFF;
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
                $user_name=$post['user_name'];
                $title_name=$post['title_name'];
                $main_text=$post['main_text'];
                $image=$post['image'];
                $_SESSION['user_name']=$user_name;//追加


                ///////////////////////////////////////////////////////////////////////////////////////////
                //データベースへの接続
                ///////////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


                ///////////////////////////////////////////////////////////////////////////////////////////
                //テーブルロック
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='LOCK TABLES thread_list WRITE,kakiko_list WRITE';
                $stmt=$dbh->prepare($sql);
                $stmt->execute();


                ///////////////////////////////////////////////////////////////////////////////////////////
                //スレッドリストに新しく作るスレッドタイトルをinsert
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='INSERT INTO thread_list (title,create_time,kakiko_count) value(?,CURRENT_TIMESTAMP,1)';
                $stmt=$dbh->prepare($sql);
                $data[]=$title_name;
                $stmt->execute($data);
                $data=[];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //thread_id(AUTO_INCREMENT)の値を取得
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='SELECT LAST_INSERT_ID();';
                $stmt=$dbh->prepare($sql);
                $stmt->execute();
                $thread_id=$stmt->fetch(PDO::FETCH_ASSOC);
                $thread_id=$thread_id['LAST_INSERT_ID()'];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //書き込み用のテーブルにスレッドの最初の書き込みをレコードに挿入
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='INSERT INTO kakiko_list (user_name,main_text,kakiko_time,thread_id,link) value(?,?,CURRENT_TIMESTAMP,?,?)';
                $stmt=$dbh->prepare($sql);
                $data[]=$user_name;
                $data[]=$main_text;
                $data[]=$thread_id;
                $data[]=$image;
                $stmt->execute($data);


                ///////////////////////////////////////////////////////////////////////////////////////////
                //テーブルアンロック
                ///////////////////////////////////////////////////////////////////////////////////////////
                $sql='UNLOCK TABLES';
                $stmt=$dbh->prepare($sql);
                $stmt->execute();
                
                $dbh=null;


                ///////////////////////////////////////////////////////////////////////////////////////////
                //表示部分
                ///////////////////////////////////////////////////////////////////////////////////////////
                print'<h2>スレッド作成完了!</h2><br>';
                $url='thread_view.php'.'?thread_id='.$thread_id.'&title='.$title_name;
                print'<a href="'.$url.'">作成したスレッドに移動</a>';
            }


            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけいたしております。';
                exit();
            }
        ?>
    </body>
</html>