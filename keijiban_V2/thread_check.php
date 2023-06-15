<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>確認ページ</title>
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
        <h2>下記の内容でスレッドを作成します<br>
        よろしいですか？</h2><br/><br/>

        <?php 
            ///////////////////////////////////////////////////////////////////////////////////////////
            //他ページからの値の取得
            ///////////////////////////////////////////////////////////////////////////////////////////
            require_once('../common/common.php');
            $post=sanitize($_POST);
            $user_name=$post['user_name'];
            $title_name=$post['title_name'];
            $main_text=$post['main_text'];
            $image=$_FILES['image'];



            ///////////////////////////////////////////////////////////////////////////////////////////
            //ちくちく言葉のチェック NGワードのリスト(CSVファイル)を参照し該当する単語は置き換え
            ///////////////////////////////////////////////////////////////////////////////////////////
            $fp=fopen('./tikutiku_list.csv','r');
            $file=fgetcsv($fp);
            $tikutiku=$file;        //ファイルを1行読み込み　配列tikutikuに保存
            define("HUWA","HUWAHUWA");   //NG文字の置換文字列

            for($i=0;$i<10;$i++){       //main_text内にtikutiku配列内のワードが含まれていたらHUWAに置き換え
                $main_text=str_replace($tikutiku[$i], HUWA, $main_text);
                $title_name=str_replace($tikutiku[$i], HUWA, $title_name);
                $user_name=str_replace($tikutiku[$i], HUWA, $user_name);
            }


            ///////////////////////////////////////////////////////////////////////////////////////////
            //表示部分
            ///////////////////////////////////////////////////////////////////////////////////////////
            print '●名前<br/>';
            print $user_name.'<br><br>';

            print '●タイトル<br/>';
            print $title_name.'<br><br>';

            print' ●本文<br/>';
            print nl2br($main_text).'<br>';
            if($image['name']!=''){
                if($image['size']>1000*1000){
                    print 'ファイルがでかすぎる<br>';
                }
                else{//名前かぶりを防ぐ仕組みが必要
                    move_uploaded_file($image['tmp_name'],'./strage/'.$image['name']);
                    print'<img src ="./strage/'.$image['name'].'" width="300" ><br>';
                }
            }
        ?>

        <form method="post" action="thread_done.php">
            <input type="hidden" name="user_name" value="<?php print $user_name?>"><br/>
            <input type="hidden" name="title_name" style="width:500px" value="<?php print $title_name?>"><br/>
            <input type="hidden" name="main_text" value="<?php print $main_text;?>">
            <input type="hidden" name="image" value="<?php print $image['name'];?>"><br/>
            <input type="submit" value="スレッド作成実行"><br/>
        </form>
        
    </body>
</html>
