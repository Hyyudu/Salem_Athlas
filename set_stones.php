<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 15.06.15
 * Time: 12:31
 * To change this template use File | Settings | File Templates.
 */
include_once('config.php');
tools::check_auth();
header("Content-type: text/html; charset=utf-8");
$colors = array('red'=>'Красные', 'green'=>'Зеленые', 'blue'=>'Синие', 'yellow'=>'Желтые', 'black'=>'Черные');

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/login.css">
</head>
<body>
<div id=wrapper>
    <div id=sliders class=roundrect >
        <div class=block_caption style="width:200px">РЕСУРСЫ ПСИХИКИ</div>
        <span class=hint>Введи точное <b>текущее</b> количество Камней в твоем мешке</span>
        <table id="set_stones" class="input_block1">
    <?
    foreach ($colors as $col=>$rus_col)
    echo "
        <tr>
            <td><img src=images/gem_{$col}_2.png></td>
            <td>".$rus_col."</td>
            <td><div id='trackbar_$col'></div></td>
            <td><input readonly id=$col value=0> </td>
        </tr>
    ";
            ?>

        </table>
        <div class="clear"></div>
    </div>
    <div id=controls>
        <img src='images/label.png'><br>
        <img src="images/diod_green.png"><br>
        АКТИВНОСТЬ МОЗГА<br><br>
        <img id=tumbler src='images/tumbler.png' onClick='ready_steady_go()'><br>
        НАЧАТЬ СЕАНС
    </div>
</div>

<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script src="js/smartslider.js" type="text/javascript"></script>
<link href="styles/smartslider.css" rel="stylesheet" type="text/css" />
<script type='text/javascript'>
    $(document).ready(function() {
    <?
    foreach ($colors as $col=>$a)
        echo "$('#trackbar_$col').strackbar({
            callback: function(value){onTick(value, \"$col\")}
            , defaultValue: 0
            , sliderHeight: 36
            , sliderWidth: 400
            , style: 'style2'
            , animate: true
            , ticks: false
            , labels: false
            , trackerHeight: 40
            , trackerWidth: 40
            , minValue:0
            , maxValue: 27
         });\n";
?>
    });

    function onTick(value, color) {
        $('#'+color).val(value);
    }1

    function ready_steady_go()  {
        jQuery.ajax({
            url: 'athlas_process.php',
            method: 'POST',
            data: {
                action: 'set_stones',
                R: $('#red').val(),
                G: $('#green').val(),
                B: $('#blue').val(),
                Y: $('#yellow').val(),
                K: $('#black').val()
            },
            success: function(data) {
                eval('data='+data)
                if (data.success)   {
                    if (data.hypnomap)
                        href = 'hypnomap.php'
                    else
                        href='view/athlas.php';
                    document.location.href = href;
                }
            }

        })
    }
</script>