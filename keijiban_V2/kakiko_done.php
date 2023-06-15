<?php
    session_start();
    try{
        ///////////////////////////////////////////////////////////////////////////////////////////
        //他ページからの値の取得
        ///////////////////////////////////////////////////////////////////////////////////////////
        require_once('../common/common.php');
        $post=sanitize($_POST);
        $user_name=$post['user_name'];
        $main_text=$post['main_text'];
        $thread_id=$post['thread_id'];
        $prev_url=$post['url'];
        $image=$post['image'];
        $_SESSION['user_name']=$user_name;//追加


        ///////////////////////////////////////////////////////////////////////////////////////////
        //データベースとの接続
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
        //書き込みリストに書き込みを挿入
        ///////////////////////////////////////////////////////////////////////////////////////////
        $sql='INSERT INTO kakiko_list(user_name,main_text,kakiko_time,thread_id,link) value(?,?,CURRENT_TIMESTAMP,?,?)';
        $stmt=$dbh->prepare($sql);
        $data[]=$user_name;
        $data[]=$main_text;
        $data[]=$thread_id;
        $data[]=$image;
        $stmt->execute($data);
        $data=array();//初期化

        ///////////////////////////////////////////////////////////////////////////////////////////
        //スレッドリストの書き込み数と最終更新時間を　アップデート
        ///////////////////////////////////////////////////////////////////////////////////////////
        $sql='SELECT count(*) FROM kakiko_list WHERE thread_id=?';
        $stmt=$dbh->prepare($sql);
        $data[]=$thread_id;
        $stmt->execute($data);
        $rec=$stmt->fetch(PDO::FETCH_ASSOC);//書き込み数をここで取得
        $data=array();//初期化

        $sql='UPDATE thread_list SET kakiko_count=?,update_time=CURRENT_TIMESTAMP WHERE thread_id=?';
        $stmt=$dbh->prepare($sql);
        $data[]=$rec['count(*)'];
        $data[]=$thread_id;
        $stmt->execute($data);
        $data=array();//初期化

        ///////////////////////////////////////////////////////////////////////////////////////////
        //テーブルアンロック
        ///////////////////////////////////////////////////////////////////////////////////////////
        $sql='UNLOCK TABLES';
        $stmt=$dbh->prepare($sql);
        $stmt->execute();

        $dbh=null;

        header('Location:'.$prev_url);
    }


    catch(Exception $e){
        print'ただいま障害により大変ご迷惑をおかけいたしております。';
        exit();
    }
?>
