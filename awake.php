<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 21.06.15
 * Time: 16:32
 * To change this template use File | Settings | File Templates.
 */

include_once('config.php');
header("Content-type: text/html; charset=utf-8");

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel=stylesheet href='styles/main.css'>
    <link rel=stylesheet href='styles/login.css'>
</head>
<h2>Результаты сеанса:</h2>
<? if(strpos($_SESSION['current_page'], 'experiment/')===FALSE) { ?>

<div id="hypno_results" class='roundrect'>
    <div class=block_caption style='width:200px'>РЕСУРСЫ ПСИХИКИ</div>
    <div class=comment>БЫЛО</div>
<?

$sack = new cStoneSet($_SESSION['sack']);
$start_sack = new cStoneSet($_SESSION['start_sack']);
$diff = $sack->diff($_SESSION['start_sack'], true);
foreach (tools::$COLORS as $letter => $col)
    echo "<div class=stone_result>
        <img src=images/gem_{$col}_2.png><br>".$start_sack->getCount($letter)."
</div>\n";
?>
<div class=clear></div><hr>
    <div class=comment>СТАЛО</div>
<?
    foreach (tools::$COLORS as $letter => $col) {
        $was = $start_sack->getCount($letter);
        $now = $sack->getCount($letter);
        echo "<div class=stone_result>
        <img src=images/gem_{$col}_2.png><br>";
        if ($now == $was)
            echo $now;
        else    {
            $ldiff = $now-$was;
            if ($ldiff > 0) {
				if ($_SESSION['doctor_skills']['quot'])	{
					$ldiff = floor($ldiff*$_SESSION['doctor_skills']['quot']);
					$now = $was + $ldiff;
				}
                $ldiff = '+'.$ldiff;
            }
            echo "<span class=diff>".$now." ($ldiff)";
        }
        echo "</div>";
    }
?>
    <div class=clear></div>
</div>
<? } ?>
<div id=other_results class=roundrect>
    <div class=block_caption style='width: 206px'>ПРОЧИЕ РЕЗУЛЬТАТЫ</div>
    <?
if ($_SESSION['messages'])
    echo "<ul><li>".join("<li>\n", $_SESSION['messages'])."</ul>";
echo "</div>
<br><a style='color: #FFDD00; font-size: 24px;' href='login.php'>Завершить сеанс</a>";
if (!$_SESSION['labels']['extra_awake'])
    tools::log(" -> awake");
if(strpos($_SESSION['current_page'], 'experiment')===FALSE)
    tools::log(json_encode($sack->diff($_SESSION['start_sack'], true)));
if ($_SESSION['messages'])
    tools::log(join("\n", $_SESSION['messages']));
tools::log("\n".date('d.m.Y H:i:s').'. Hypno_end');
tools::log(str_repeat('=',50));