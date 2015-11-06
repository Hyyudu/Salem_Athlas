<?php
header("Content-type: text/html; charset=utf-8");
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/login.css">
</head>
<body>
<div id="wrapper">
    <div id="doctor" class='roundrect'>
        <div class=block_caption style='width:175px'>НЕДАВНИЕ ЛОГИ</div>
        <div style='padding: 10mm'>
            <?php

            $SHOW_LOGS_COUNT = 9;
            if (in_array(strtolower($_SESSION['doctor']), array('jackob fawcett', 'adelind reber')))
                $SHOW_LOGS_COUNT = 15;

            $text = file_get_contents('hypno.log');
            $matches = explode("\n==================================================\n", $text);
            $matches = array_reverse($matches);
            $matches = array_slice($matches, 1);
            $matched = 0;
            foreach ($matches as $match)    {
                if (strpos($match, '*SNEAKY*'))
                    continue;
                $match = explode("\n", $match);
    //            print_r($match);
                echo join("<br>\n", array($match[0], $match[1], $match[2], $match[count($match)-1], "==================================================<br>\n"));
                $matched++;
                if ($matched >= $SHOW_LOGS_COUNT)
                    break;
            }
            echo "<a style='color:yellow' href=login.php>Вернуться к странице авторизации</a>";
    ?>
        </div>
    </div>
</div>
</body>
</html>
