<?php
       
        try{
            ///////////////////////////////////////////////////////////////////////////////////////////
            //他ページからの値の取得
            ///////////////////////////////////////////////////////////////////////////////////////////
            require_once('../common/common.php');
            $post=sanitize($_POST);
            $user_id=$post['user_id'];
            $pass=$post['pass'];


            ///////////////////////////////////////////////////////////////////////////////////////////
            //データベースへの接続
            ///////////////////////////////////////////////////////////////////////////////////////////
            $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
            $user='root';
            $password='';
            $dbh=new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);            
            
            
            ///////////////////////////////////////////////////////////////////////////////////////////
            //idとpassが一致するレコードがあるか確認
            ///////////////////////////////////////////////////////////////////////////////////////////
            $sql='SELECT * FROM user_list WHERE user_id=? AND pass=?';
            $stmt=$dbh->prepare($sql);
            $data[]=$user_id;
            $data[]=$pass;
            $stmt->execute($data);

            $dbh=null;
        
            $rec=$stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                print'idかパスワードが違います<br>';
                print'<a href="admin_login.php">ログイン画面に戻る</a>';
            }
            else{
                session_start();
                $_SESSION['login']=1;//1:login OK 
                header('Location:admin_top.php');
                exit();
            }
        }

        catch(Exception $e){
            print'エラー';
            exit();
        }

      

?>
