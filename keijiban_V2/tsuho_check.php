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
        <h2>下記の書き込みを通報します<br/>
        よろしいですか？</h2><br/><br/>
        
        <?php
            try{
                ///////////////////////////////////////////////////////////////////////////////////////////
                //他ページからの値の取得
                ///////////////////////////////////////////////////////////////////////////////////////////
                require_once('../common/common.php');
                $post=sanitize($_POST);
                $tsuho_kakiko_id=$post['kakiko_id'];
                $user_name=$post['user_name'];
                $main_text=$post['main_text'];
                $prev_url=$post['url'];
                $image=$post['image'];


                ///////////////////////////////////////////////////////////////////////////////////////////
                //表示部分
                ///////////////////////////////////////////////////////////////////////////////////////////
                print '●名前<br/>';
                print $user_name.'<br><br>';
    
                print' ●本文<br/>';
                print nl2br($main_text).'<br>';

                if($image!=''){
                    print '<img src="./strage/'.$image.'" width="300">';
                }
            }


            catch(Exception $e){
                print'ただいま障害により大変ご迷惑をおかけいたしております。';
                exit();
            }

        ?>

    <form method="post" action="tsuho_done.php">
        <input type="hidden" name="url" value="<?php print $prev_url; ?>">
        <input type="hidden" name="kakiko_id"value="<?php print $tsuho_kakiko_id;?>">
        <input type="submit" value="＜この書き込みを通報＞"><br/>
    </form>
    <?php print'<a href="'.$prev_url.'">スレッドに戻る</a><br>'; ?>
    </body>
</html>