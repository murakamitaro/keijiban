<?php
    function anka2link2($str){ //サニタイズ済みの文字列の場合のみ使用可能
        //$str=htmlspecialchars($str,ENT_QUOTES,'UTF-8');//
        $search='/&gt;&gt;[0-9]+/';//　>>XXX　の正規表現
        preg_match_all($search,$str,$anka);//str内から　＞＞XXX　形式の文字列　を検索し　anka[]に格納
        
        for($i=0;$i<count($anka[0]);$i++){//anka[]の各要素の値をもとに　書き込み内の>>XXX形式の部分をリンクにする
            $anka[0][$i]=substr($anka[0][$i],8);//anka[]内の＞＞XXXから　>>を取り除いて　数字にする
            $okikae='<a href="#'.$anka[0][$i].'"> >>'.$anka[0][$i].'</a>';//置き換え後のtag、リンク作成
            $str=preg_replace('/&gt;&gt;'.$anka[0][$i].'([^0-9]|$)/',$okikae,$str);//str内の　>>.anka[]　と完全一致する部分をタグに置き換え
        }
        return $str;
    }

    function anka2link($str){//サニタイズされていない文字列の場合のみ使用可能
        $str=htmlspecialchars($str,ENT_QUOTES,'UTF-8');//
        $search='/>>[0-9]+/';//　>>XXX　の正規表現
        preg_match_all($search,$str,$anka);//str内から　＞＞XXX　形式の文字列　を検索し　anka[]に格納
        
        for($i=0;$i<count($anka[0]);$i++){//anka[]の各要素の値をもとに　書き込み内の>>XXX形式の部分をリンクにする
            $anka[0][$i]=substr($anka[0][$i],2);//anka[]内の＞＞XXXから　>>を取り除いて　数字にする
            $okikae='<a href="#'.$anka[0][$i].'"> >>'.$anka[0][$i].'</a>';//置き換え後のtag、リンク作成
            $str=preg_replace('/>>'.$anka[0][$i].'([^0-9]|$)/',$okikae,$str);//str内の　>>.anka[]　と完全一致する部分をタグに置き換え
        }
        return $str;
    }

    function gengo($seireki){
        if(1868<=$seireki && $seireki<=1911){
            $gengo='明治';
        }
        if(1912<=$seireki && $seireki<=1925){
            $gengo='大正';
        }
        if(1926<=$seireki && $seireki<=1988){
            $gengo='昭和';
        }
        if(1989<=$seireki){
            $gengo='平成';
        }
        return($gengo);
    }

    function sanitize($before){
        if(is_array($before)===true){
            foreach($before as $key => $val){
                $after[$key]=htmlspecialchars($val,ENT_QUOTES,'UTF-8');
            }
        }
        else{
            $after=htmlspecialchars($before,ENT_QUOTES,'UTF-8');
        }
        return $after;
    }
?>
