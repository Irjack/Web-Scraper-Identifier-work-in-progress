<?php
@ini_set('max_execution_time',0);
?>
<html>

<head>
<style>
input:focus{
        outline:none;
        border-color:rgba(255,25,33,.75);
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        box-shadow:008pxrgba(255,25,33,.5);
        -moz-box-shadow:008pxrgba(255,25,33,.5);
        -webkit-box-shadow:008pxrgba(255,25,33,.5);
}
input{
        border:1pxsolid#aaa;
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        transition:borderlinear.2s,box-shadowlinear.2s;
        -moz-transition:borderlinear.2s,-moz-box-shadowlinear.2s;
        -webkit-transition:borderlinear.2s,-webkit-box-shadowlinear.2s;
}

textarea:focus{
        outline:none;
        border-color:rgba(255,25,33,.75);
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        box-shadow:008pxrgba(255,25,33,.5);
        -moz-box-shadow:008pxrgba(255,25,33,.5);
        -webkit-box-shadow:008pxrgba(255,25,33,.5);
}
textarea{
        border:1pxsolid#aaa;
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        transition:borderlinear.2s,box-shadowlinear.2s;
        -moz-transition:borderlinear.2s,-moz-box-shadowlinear.2s;
        -webkit-transition:borderlinear.2s,-webkit-box-shadowlinear.2s;
}

table{
        background-color:#cccccc;
        width:400px;
}
tr{
        background-color:#ffffff;
}
</style>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>YouTube Парсер</title>
</head>

<body>

<form action=""method="post">
<center>

<table>
<tr><td><div style='padding:5px;' align="right">Ключевые слова:</div></td><td><div style='padding:5px;' align="left"><textarea name="keys"rows=5 cols=20 wrap="off"></textarea></div></td></tr>
<tr><td><div style='padding:5px;' align="right">Количество страниц:</div></td><td><div style='padding:5px;' align="left"><input name="page" type="text" value="1"></div></td></tr>
<tr><td><div style='padding:5px;' align="right">Имя файла:</div></td><td><div style='padding:5px;' align="left"><input name="filename" type="text" value="parse.txt"></div></td></tr>

</table>

<table>
<tr><td><center><input name="sub" type="submit" value="Парсить">&nbsp;&nbsp;&nbsp;<input type="reset" value="Сбросить настройки"></center></td></tr>
</table>

</center>

</form>

<?php

if(isset($_POST['sub'])){
        $keys=$_POST['keys'];
        $page=intval(trim($_POST['page']));
        $filename=trim($_POST['filename']);

        $KeysArray=explode("\n",$keys);
        $KeysArray=array_map("trim",$KeysArray);
        $CountKeys=count($KeysArray);

        //счетчик
        $cpl=0;

        ///Условие
        if($CountKeys>0&&!empty($page)){

                for($i=0;$i<$CountKeys;$i++){

                        //количество страниц
                        for($p=1;$p<=$page;$p++){

                        $YouLink="";

                        //обрабатываем ключевик
                        $key=trim($KeysArray[$i]);
                        $key=urlencode($key);
                        $key=str_replace("%20","+",$key);

                        $PageParse=file_get_contents("http://www.youtube.com/results?search_type=videos&search_query=".$key."&page=".$p);

                        //if(strpos($PageParse,"/watch?v=")!=FALSE)
                        //{
                                preg_match_all("/href=\"\/watch\?v=([^\"]*)\"/sU",$PageParse,$matches);

                                $resultmovies=array_unique($matches[1]);

                                $moviescount=count($resultmovies);
                                foreach($resultmovies as $movielink)
                                {
                                        $YouLink.="http://www.youtube.com/watch?v=".trim($movielink)."\r\n";
                                        $cpl++;
                                }
                                 echo $YouLink."<hr />";
                                //ЗаписываемлинкyouTube
                                $fp=fopen($filename,"a+");
                                fwrite($fp,$YouLink);
                                fclose($fp);

                                sleep(1);
                        //}
                            //    echo $key."<hr />";

                }

                }

        }

        echo"<center><table><tr><td><fontcolor=\"green\">Спарсено $cpl ссылок на видео.Данные ссылки сохранены в файл $filename</font></td></tr></table></center>";

}

?>

</body>

</html>