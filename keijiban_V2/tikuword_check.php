<?php
    require_once('../common/common.php');
    try{
        $tikufile=$_FILES['tikulist'];

        $fp=fopen('./tikutiku_list.csv','r');
        $file=fgetcsv($fp);
        $file=mb_convert_encoding($file,'UTF-8','SJIS');//SJIS->UTF8
        move_uploaded_file($tikufile['tmp_name'],'./tikutiku_list.csv');
        header('Location:user_syukusei_top.php');

                
        
       /* $fp=fopen('./tikutiku_list.csv','r');
        $file=fgetcsv($fp);
      */
        $tikutiku=$file;        //ファイルを1行読み込み　配列tikutikuに保存
    }
    catch(Exception $e){
        print'ただいま障害により大変ご迷惑をおかけしております。';
        exit();
    }
?>
