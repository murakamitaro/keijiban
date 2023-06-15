<?php
    session_start();
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
        <a href="admin_login.php"> 管理ページ</a><br>
        <?php
            try{
                ////////////////////////////////////////////////////////////////////////////////////////
                //他ページからの値取得　&　フォームの入力に応じたSQL文の生成
                ///////////////////////////////////////////////////////////////////////////////////////
                require_once('../common/common.php');
                $get=sanitize($_GET);

                if(!isset($get['order'])){
                    $order="create_time";
                }
                else{
                    $order=$get['order'];
                }

                if(!isset($get['kensaku_moji'])){
                    $kensaku_moji="";
                    $like='%'.$kensaku_moji.'%';
                }
                else{
                    $kensaku_moji=$get['kensaku_moji'];
                    $like='%'.$kensaku_moji.'%';
                }

                if(!isset($get['limit_num'])){
                    $limit_num=10;
                }
                else{
                    $limit_num=$get['limit_num'];
                }

                if(!isset($get['offset_num'])){
                    $offset_num=0;
                }
                else{
                    $offset_num=$get['offset_num'];
                }

                switch($order){
                    case 'create_time':
                        $sql='SELECT title,create_time,thread_id,kakiko_count FROM thread_list WHERE title LIKE ? ORDER BY create_time DESC LIMIT ? OFFSET ?';
                        break;

                    case 'update_time':
                        $sql='SELECT title,create_time,thread_id,kakiko_count FROM thread_list WHERE title LIKE ? ORDER BY update_time DESC LIMIT ? OFFSET ?';
                        break;         

                    case 'title':
                        $sql='SELECT title,create_time,thread_id,kakiko_count FROM thread_list WHERE title LIKE ? ORDER BY title DESC LIMIT ? OFFSET ?';
                        break;

                    case 'kakiko_count':
                        $sql='SELECT title,create_time,thread_id,kakiko_count FROM thread_list WHERE title LIKE ? ORDER BY kakiko_count DESC LIMIT ? OFFSET ?';
                        break;                    
                }


                ///////////////////////////////////////////////////////////////////////////////////////
                //データベースとの接続、レコード内容の取得
                ///////////////////////////////////////////////////////////////////////////////////////
                $dsn='mysql:dbname=keijiban_V2;host=localhost;charset=utf8';
                $user='root';
                $password='';
                $dbh=new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $stmt=$dbh->prepare($sql);
                $stmt->bindParam(1,$like);                      //$stmt->bindParam(2,$order);//order内は　？で設定不可
                $stmt->bindParam(2,$limit_num,PDO::PARAM_INT);  //第一の1が何番目の?が対象か、第2バインドする変数、第3データ型　
                $stmt->bindParam(3,$offset_num,PDO::PARAM_INT);
                $stmt->execute();
                $dbh=null;
                


                ///////////////////////////////////////////////////////////////////////////////////////
                //表示部分
                ///////////////////////////////////////////////////////////////////////////////////////
                $count=0;
                print '<h2>スレッド一覧</h2>';
                print'<ul>';
                while(true){//レコードをひとつづつ取り出してrec(配列)に格納　&リスト表示
                    $rec=$stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        break;
                    }
                    print'<li><a href="./thread_view.php?thread_id='.$rec['thread_id'].'">';
                    print $rec['title'].' ('.$rec['kakiko_count'].')'.'　'.$rec['create_time'];
                    print '</a></li>';
                    $count++;
                }
                print'</ul>';
                print'<br>';

                //PREVボタンの表示
                if($offset_num-$limit_num>=0){
                    print'<a href="javascript:page_prev.submit()">PREV</a>';
                    print'<form method="get" name="page_prev" action="toppage.php" style="display:inline">';
                    print'<input type="hidden" name="kensaku_moji" value='.$kensaku_moji.'>';
                    print'<input type="hidden" name="order" value='.$order.'>';
                    print'<input type="hidden" name="limit_num" value='.$limit_num.'>';
                    print'<input type="hidden" name="offset_num" value='.($offset_num-$limit_num).'>';
                    print'</form>';
                }

                //NEXTボタンの表示
                if($count>=$limit_num){
                    print'　　';
                    print'<a href="javascript:page_next.submit()">NEXT</a>';//ここGETでよかった
                    print'<form method="get" name="page_next" action="toppage.php" style="display:inline">';
                    print'<input type="hidden" name="kensaku_moji" value='.$kensaku_moji.'>';
                    print'<input type="hidden" name="order" value='.$order.'>';
                    print'<input type="hidden" name="limit_num" value='.$limit_num.' >';
                    print'<input type="hidden" name="offset_num" value='.($offset_num+$limit_num).'>';
                    print'</form>';
                }
            }

            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけしております。';
                exit();
            }
        ?>
        

        <br/><br/>

            <form method="get" action="toppage.php">
                <h2>  ◆リストを並び替え</h2>
                <select name="order">
                    <option value="update_time" <?=$order==="update_time"?'selected':''?>>更新順</option>
                    <option value="create_time" <?=$order==="create_time"?'selected':''?>>作成順</option>
                    <option value="title" <?=$order==="title"?'selected':''?>>タイトル順</option><!-- phpの短縮構文　条件に応じてprintする　-->
                    <option value="kakiko_count" <?=$order==="kakiko_count"?'selected':''?>>書き込み数順</option>
                </select>
                <select name="limit_num">
                    <option value="20" <?=$limit_num==="20"?'selected':''?>>20件</option>
                    <option value="10" <?=$limit_num==="10"?'selected':''?>>10件</option>
                    <option value="5" <?=$limit_num==="5"?'selected':''?>>5件</option><!-- phpの短縮構文　条件に応じてprintする　-->
                </select>
                <input type="submit" value="＜並び替え実行＞"><br/>
            </form>

            <form method="get" action="toppage.php">
                <h2>  ◆スレッドを検索する</h2>
                <input type="text" name="kensaku_moji" value="<?php echo"$kensaku_moji";?>">
                <select name="limit_num">
                    <option value="20" <?=$limit_num==="20"?'selected':''?>>20件</option>
                    <option value="10" <?=$limit_num==="10"?'selected':''?>>10件</option>
                    <option value="5" <?=$limit_num==="5"?'selected':''?>>5件</option><!-- phpの短縮構文　条件に応じてprintする　-->
                </select>
                <input type="submit" value="＜検索実行＞"><br/>
            </form>

            <form method="post" action="thread_check.php" enctype="multipart/form-data">
                <h2>  ◆新しいスレッドを作成する</h2>
                ・名前<br/>
                <input type="text" name="user_name" value=<?php isset($_SESSION['user_name']) ? print $_SESSION['user_name'] : print"名無し"?> maxlength="20" required><br/>
                ・タイトル<br/>
                <input type="text" name="title_name"  size="50" maxlength="50" required><br/>
                ・本文<br/>
                <textarea  name="main_text" rows="6" cols="50" maxlength="300" required></textarea><br/>
                ・画像アップロード<br>
                <input type="file" name="image" style="width:400px"><br/>
                <input type="submit" value="＜確認ページへ進む＞"><br/>
            </form>

    </body>
</html>