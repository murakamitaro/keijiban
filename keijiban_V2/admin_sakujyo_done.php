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

            <?php
            try{
                ///////////////////////////////////////////////////////////////////////////////////////////
                //他ページからの値の取得
                ///////////////////////////////////////////////////////////////////////////////////////////
                require_once('../common/common.php');
                $post=sanitize($_POST);
                $kakiko_id=$post['kakiko_id'];//
                $image=$post['image'];//
                $judge=$post['judge'];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //データベースへの接続
                ///////////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


                ///////////////////////////////////////////////////////////////////////////////////////////
                //$judgeの値に応じてSQL文を生成→実行
                ///////////////////////////////////////////////////////////////////////////////////////////
                switch($judge){
                    case'OK':
                        $sql='UPDATE kakiko_list SET tsuho_flag=0 WHERE kakiko_id=?';
                        $stmt=$dbh->prepare($sql);
                        $data[]=$kakiko_id;
                        $stmt->execute($data);
        
                        $dbh=null;
        
                        print'通報を取り消しました。<br>';
                        break;

                    case'NG':
                        $user_name='名無し';
                        $main_text='この書き込みは削除されました';
                        $sql='UPDATE kakiko_list SET user_name=?,main_text=?,link="",tsuho_flag="0"  WHERE kakiko_id=?';
                        //$sql='DELETE FROM kakiko_list WHERE kakiko_id=?';
                        $stmt=$dbh->prepare($sql);
                        $data[]=$user_name;
                        $data[]=$main_text;
                        $data[]=$kakiko_id;
                        $stmt->execute($data);
        
                        $dbh=null;
        
                        if($image!=''){
                            unlink('strage/'.$image);
                        }
                        print'書き込みを削除しました。<br>';
                        break;
                }
            }

            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけしております。';
                exit();
            }

            ?>
 
            
            <a href="admin_top.php">管理topに戻る</a>

    </body>
</html>