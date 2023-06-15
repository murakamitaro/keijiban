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
        <h2>下記の内容で書き込みます<br/>
        よろしいですか？</h2><br/><br/>
        
        <?php
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
                $image=$_FILES['image'];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //ちくちく言葉のチェック NGワードのリスト(CSVファイル)を参照し該当する単語は置き換え
                ///////////////////////////////////////////////////////////////////////////////////////////
                $fp=fopen('./tikutiku_list.csv','r');
                $file=fgetcsv($fp);
                $file=mb_convert_encoding($file,'UTF-8','SJIS');//SJIS->UTF8

                $tikutiku=$file;        //ファイルを1行読み込み　配列tikutikuに保存
                define("HUWA","ふわふわ");   //NG文字の置換文字列

                foreach($tikutiku as $tikuword){       //main_text内にtikutiku配列内のワードが含まれていたらHUWAに置き換え
                    $main_text=str_replace($tikuword, HUWA, $main_text);
                    $user_name=str_replace($tikuword, HUWA, $user_name);
                }
                

                ///////////////////////////////////////////////////////////////////////////////////////////
                //表示部分
                ///////////////////////////////////////////////////////////////////////////////////////////
                print '●名前<br/>';
                print $user_name.'<br><br>';
    
                print' ●本文<br/>';
                print nl2br($main_text).'<br>';

                print' ●画像<br/>';
                if($image['name']!=''){
                    if($image['size']>1000*1000){
                        print 'ファイルがでかすぎる<br>';
                    }
                    else{//名前かぶりを防ぐ仕組みが必要
                        move_uploaded_file($image['tmp_name'],'./strage/'.$image['name']);
                        print'<img src ="./strage/'.$image['name'].'" width="300" ><br>';
                    }
                }
            }  


            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけいたしております。';
                exit();
            }

        ?>

    <form method="post" action="kakiko_done.php">
        <input type="hidden" name="url" value="<?php print $prev_url; ?>">
        <input type="hidden" name="user_name" value="<?php print $user_name;?>">
        <input type="hidden" name="thread_id"value="<?php print $thread_id;?>">
        <input type="hidden" name="main_text" value="<?php print $main_text;?>">
        <input type="hidden" name="image" value="<?php print $image['name'];?>"><br/>
        <input type="submit" value="＜この内容で書き込む＞"><br/>
    </form>
    <?php print'<a href="'.$prev_url.'">スレッドに戻る</a><br>'; ?>
    </body>
</html>