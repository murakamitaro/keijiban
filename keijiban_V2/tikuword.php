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
    require_once('../common/common.php');
    try{
        $fp=fopen('./tikutiku_list.csv','r');
        $file=fgetcsv($fp);
        $file=mb_convert_encoding($file,'UTF-8','SJIS');//SJIS->UTF8

       /* $fp=fopen('./tikutiku_list.csv','r');
        $file=fgetcsv($fp);
      */
        $tikutiku=$file;        //ファイルを1行読み込み　配列tikutikuに保存

        print '●ちくちく言葉一覧<br>';
        foreach($tikutiku as $key=>$val){
            print $val.'<br>';
        }
    }
    catch(Exception $e){
        print'ただいま障害により大変ご迷惑をおかけしております。';
        exit();
    }
    ?>

    <form method="post" action="tikuword_check.php" enctype="multipart/form-data">
        <input type="file" name="tikulist" >
        <input type="submit" value="アップロード">
    </form>
    <a href="./tikutiku_list.csv">CSVファイルをダウンロード</a><br/>
    <a href="./toppage.php">topに戻る</a>
    </body>
</html>