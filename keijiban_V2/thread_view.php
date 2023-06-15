<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>スレッド</title>
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
        $get=sanitize($_GET);
        $thread_id=$get['thread_id'];
        $kakiko_limit=5;//このページに表示する書き込み数の最大値
        if(!isset($get['offset_num'])){
            $offset_num=0;
        }
        else{
            $offset_num=(int)($get['offset_num']);
        }
        

        ///////////////////////////////////////////////////////////////////////////////////////////
        //データベースとの接続
        ///////////////////////////////////////////////////////////////////////////////////////////
        $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
        $user='root';
        $password='';
        $dbh=new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        ///////////////////////////////////////////////////////////////////////////////////////////
        //スレッドリストからタイトル取得
        ///////////////////////////////////////////////////////////////////////////////////////////
        //$sql='SELECT title FROM thread_list WHERE thread_id='.$thread_id ;
        $sql='SELECT title FROM thread_list WHERE thread_id=?' ;
        $stmt=$dbh->prepare($sql);
        $data[]=$thread_id;
        $stmt->execute($data);
        $rec=$stmt->fetch(PDO::FETCH_ASSOC);
        $title=$rec['title'];
        $data=array();

        ///////////////////////////////////////////////////////////////////////////////////////////
        //スレッドのテーブルから特定のthread_idの書き込みを取得
        ///////////////////////////////////////////////////////////////////////////////////////////
        $sql='SELECT * FROM kakiko_list WHERE thread_id=? ORDER BY kakiko_id ASC LIMIT ? OFFSET ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindParam(1,$thread_id);
        $stmt->bindParam(2,$kakiko_limit,PDO::PARAM_INT);
        $stmt->bindParam(3,$offset_num,PDO::PARAM_INT);
        $stmt->execute();
        $data=array();
        $dbh=null;

        ///////////////////////////////////////////////////////////////////////////////////////////
        //表示部分
        ///////////////////////////////////////////////////////////////////////////////////////////
        print '<h2>'.$title.'</h2><br/>';
        $kakiko_num=$offset_num+1;
        while(true){//レコードをひとつづつ取り出してrec(配列)に格納
            $rec=$stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false || $kakiko_num >$offset_num+$kakiko_limit){
                 break;
            }
            print'<a name="'.$kakiko_num.'">'.$kakiko_num.':</a>';
            //print $kakiko_num.':';
            print $rec['user_name'].' ';
            print $rec['kakiko_time'];
            print'<a href="javascript:tsuho_form'.$kakiko_num.'.submit()">通報する</a><br>';
            //print '<a href ="tsuho_check.php?kakiko_id='.$rec['kakiko_id'].'&image='.$rec['link'].'&user_name='.$rec['user_name'].'&main_text='.$rec['main_text'].'&url='.$_SERVER['REQUEST_URI'].'">通報する</a><br/>';
            print nl2br(anka2link2($rec['main_text'])).'<br/><br>';
            if($rec['link']!=""){//画像の保存場所がテーブルに登録されてる場合のみ表示
                print'<img src ="./strage/'.$rec['link'].'" width="300" ><br><br>';
            }
            print
                '<form method="post" name="tsuho_form'.$kakiko_num.'" action="tsuho_check.php">
                    <input type="hidden" name="kakiko_id" value='.$rec['kakiko_id'].'>
                    <input type="hidden" name="user_name" value='.$rec['user_name'].'>
                    <input type="hidden" name="main_text" value='.$rec['main_text'].' >
                    <input type="hidden" name="image" value='.$rec['link'].'>
                    <input type="hidden" name="url" 
                    value='.$_SERVER['REQUEST_URI'].'>
                </form>';
            print '<hr><br/>';
            $kakiko_num++;
        }
        
        //PREVボタンの表示
        if($offset_num-$kakiko_limit>=0){
            print'<a href="javascript:page_prev.submit()">PREV</a>';
            print'<form method="get" name="page_prev" action="thread_view.php" style="display:inline">';
            print'<input type="hidden" name="thread_id" value='.$thread_id.'>';
            print'<input type="hidden" name="offset_num" value='.($offset_num-$kakiko_limit).'>';
            print'</form>';
        }

        //NEXTボタンの表示
        if($kakiko_num>=$kakiko_limit){
            print'　　';
            print'<a href="javascript:page_next.submit()">NEXT</a>';
            print'<form method="get" name="page_next" action="thread_view.php" style="display:inline">';
            print'<input type="hidden" name="thread_id" value='.$thread_id.'>';
            print'<input type="hidden" name="offset_num" value='.($offset_num+$kakiko_limit).'>';
            print'</form>';
        }
    }
    catch(Exception $e){
        print'ただいま障害により大変ご迷惑をおかけしております。';
        exit();
    }
    ?>

    <form method="post" action="kakiko_check.php" enctype="multipart/form-data">
            <input type="hidden" name="thread_id" value=<?php print $thread_id; ?>><br/>
            <input type="hidden" name="url" value="<?php print $_SERVER['REQUEST_URI']; ?>"><br/>
            <br/>
            ・名前<br/>
            <input type="text" name="user_name" value=<?php isset($_SESSION['user_name']) ? print $_SESSION['user_name'] : print"名無し"?> maxlength="20" required><br/>
            ・本文<br/>
            <textarea  name="main_text" rows="6" cols="50" maxlength="300" required></textarea><br/>
            ・画像アップロード<br>
            <input type="file" name="image" style="width:400px"><br/>
            <input type="submit" value="＜確認画面に進む＞"><br/>
    </form>
    
    <br/>

    <a href="./toppage.php">topに戻る</a>
    </body>
</html>