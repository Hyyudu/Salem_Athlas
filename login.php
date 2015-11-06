<?php
header("Content-type: text/html; charset=utf-8");
session_start();
foreach(array('1922', '1942', '1945') as $year)
    if (isset($_GET[$year]))    {
        file_put_contents('version.txt', $year);
        foreach($_SESSION as $key=>$val)
            unset($_SESSION[$key]);
        header('Location:/');
    }

$year = file_get_contents('version.txt');
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/login.css">
</head>
<body>
    <div id=year style='position:absolute;color:white'><?=$year?></div>
    <div id="wrapper">
        <div id="doctor" class='roundrect'>
            <div class=block_caption style='width:140px'>ГИПНОТИЗЕР</div>
            <div id='doctor_login' class=input_block1>
                <div class=unlogged>
                    <input id="doctor_name">
                </div>
                <div class=logged>
                    <div class=logged_name></div>
                </div>
                <div class=input_caption>Имя гипнотизера</div>
            </div>
            <div id="doctor_pass" class=input_block1>
                <div class=unlogged>
                    <input type=password id="doctor_password">
                    <div class=input_caption>Пароль гипнотизера</div>
                </div>
                <div class=logged><a href=check_logs.php>Просмотр последних логов гипноза</a></div>
            </div>
            <div id="doctor_operate" class=input_block2>
                <div class=unlogged>
                    <img class='img_switcher' src="images/switcher_small_2.png" onclick='login("doctor");'><br>
                    Активировано <img class='img_diod' src='images/diod_gray.png'>
                </div>
                <div class=logged>
                    <img class='img_switcher' src="images/switcher_small_1.png" onclick='logout("doctor");'><br>
                    Активировано <img class='img_diod' src='images/diod_green.png'>
                </div>
            </div>
            <div id="doctor_error" class="clear error_msg"></div>
        </div>
        <div id="patient" class='roundrect'>
            <div class=block_caption style='width: 110px'>ПАЦИЕНТ</div>
            <div id='patient_login' class=input_block1>
                <div class=unlogged>
                    <input id="patient_name">
                </div>
                <div class=logged>
                    <div class=logged_name></div>
                </div>
                <div class=input_caption>Имя пациента</div>
            </div>
            <div id="patient_pass" class=input_block1>
                <div class=unlogged>
                    <input type=password id="patient_password">
                    <div class=input_caption>Пароль пациента</div>
                </div>
                <div class=logged>&nbsp;</div>
            </div>
            <div id="patient_operate" class=input_block2>
                <div class=unlogged>
                    <img class='img_switcher' src="images/switcher_small_2.png" onclick='login("patient");'><br>
                    Активировано <img class='img_diod' src='images/diod_gray.png'>
                </div>
                <div class=logged>
                    <img class='img_switcher' src="images/switcher_small_1.png" onclick='logout("patient");'><br>
                    Активировано <img class='img_diod' src='images/diod_green.png'>
                </div>
            </div>
            <div id="patient_error" class="clear error_msg"></div>
        </div>
        <div class='clear'></div>
        <div id='proceed' class='roundrect'>
            <div class=block_caption style='width: 90px'>РЕЖИМ</div>
            <table style='width: 100%'>
                <tr>
                    <td rowspan=3><img id=switcher_story src="images/switcher_story_0.png" onClick='change_mode()'></td>
                    <td>
                        <div class=proceed_comment>Пациент в полном сознании</div>
                        <div class=proceed_dir>Обычный сеанс — перейти на карту</div>
                    </td>
                    <td><img id=diod_0 src='images/diod_green.png'></td>
                    <td rowspan=3 style='text-align: center'>
                        НАЧАТЬ СЕАНС<BR>
                        <img id=tumbler src="images/tumbler.png" onClick='hypno_start()'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class=proceed_comment>Пациент под Императивным поведением</div>
                        <div class=proceed_dir>Психика нестабильна — падение в Неведомое</div>
                    </td>
                    <td><img id=diod_1 src='images/diod_gray.png'></td>
                </tr>
                <tr>
                    <td>
                        <div class=proceed_comment>Начать гипноэксперимент</div>
                        <div class=proceed_dir>Требуется максимальный уровень допуска!</div>
                    </td>
                    <td><img id=diod_2 src='images/diod_gray.png'></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        jQuery.ajax({
            url: 'athlas_process.php',
            method: 'POST',
            data: {drop: 1,
                    fields: ['doctor', 'doctor_skills', 'sneaky', 'patient', 'current_page', 'sack', 'start_sack', 'heap', 'athlas_data', 'messages', 'labels', 'log_started', 'exp']
            }
    });
        document.athlas_direction=0;
    })

    function login(usertype) {
//      Очищаем текст ошибки
        $('#'+usertype+'_error').html('');
        jQuery.ajax({
            url: 'athlas_process.php',
            method: 'POST',
            data: {
                action: 'login',
                usertype: usertype,
                login: $('#'+usertype+'_name').val(),
                password: $('#'+usertype+'_password').val()
            },
            success: function(data) {
                eval('data='+data)
                console.log(data)
                if (!data.success)  {
                    $('#proceed').slideUp()
                    if (data.code == 'bad_name')    {
                        error_msg = "История Салема не знает человека по имени "+data.login+". "
                        if (data.props.length > 0)  {
                            for (i=0; i<data.props.length; i++)
                                data.props[i] = "<a href=# onclick='$(\"#"+data.usertype+"_name\").val(\""+data.props[i]+"\"); return false;'>"+data.props[i]+"</a>";
                            error_msg += " Возможно, вы имели в виду: "+data.props.join(', ')
                        }

                    }
                    else if (data.code == 'bad_password')   {
                        error_msg = data.login + ' требует другой ключ для входа в подсознание';
                    }
                    else if (data.code == 'hacking')    {
                        error_msg = 'Обнаружена попытка воровства программного обеспечения аппарата Dream Sequencer! Аппарат заблокирован!'
                    }
                    else if (data.code == 'same_person')    {
                        error_msg = 'Невозможно провести сеанс гипноза самому себе!'
                    }
                    $('#'+data.usertype+'_error').html(error_msg);
                    $('#'+data.usertype+'_operate .unlogged .img_diod').attr('src', 'images/diod_red.png')

                }
                else    {
//                  Все успешно
                    $('#'+data.usertype+' .logged').show()
                    $('#'+data.usertype+' .unlogged').hide()
                    $('#'+data.usertype+' .logged .logged_name').html(data.login)
                    $('#'+data.usertype+'_operate .unlogged .img_diod').attr('src', 'images/diod_gray.png')

                    if (data.ready)
                        $('#proceed').slideDown()
                }
            }

        })
    }

    function logout(usertype)   {
        jQuery.ajax({
            url: 'athlas_process.php',
            method: 'POST',
            data: {
                action: 'logout',
                usertype: usertype
            }
        })
        $('#proceed').hide()
        $('#'+usertype+' .logged').hide()
        $('#'+usertype+' .unlogged').show()
        $('#'+usertype+'_password').val('')
    }

    function hypno_start()  {
        if (document.athlas_direction == 0) {   // Переход на карту
            jQuery.ajax({
                url: 'athlas_process.php',
                method: 'POST',
                data: {
                    action: 'set_page',
                    page: 'hypnomap'
                },
                success: function(data) {
                    eval('data='+data)
                    if (data.success)
                        document.location.href='set_stones.php';
                }
            })
        }
        else if (document.athlas_direction == 1)    {// Императив -> Бессознательное
            jQuery.ajax({
                url: 'athlas_process.php',
                method: 'POST',
                data: {
                    action: 'start_unknown'
                },
                success: function(data) {
                    eval('data='+data)
                    if (data.success)
                        document.location.href='set_stones.php';
                    else
                        alert('Авторизованный гипнотерапевт не сертифицирован для проведения гипнотических сеансов в Неведомом!')

                }
            })
        }
        else if (document.athlas_direction == 2)    {   //Эксперимент
            jQuery.ajax({
                url: 'athlas_process.php',
                method: 'POST',
                data: {
                    action: 'start_experiment'
                },
                success: function(data) {
                    eval('data='+data)
                    if (data.success)
                        document.location.href='view/athlas.php';
                    else
						if (data.error=='no_rights')
							alert('Авторизованный гипнотерапевт не обладает достаточным уровнем допуска для проведения гипнотических экспериментов!');
						else if (data.error=='1922')
							alert('Да вы охренели? Какой "Непобедимый" в 1922 году?')
                }
            })
        }
    }


    function change_mode()  {
        // Отключить предыдущий диод
        $('#diod_'+document.athlas_direction).attr('src', 'images/diod_gray.png');
        // Перейти на другой режим
        document.athlas_direction = (document.athlas_direction+1)%3;
        // Включить новый диод
        $('#diod_'+document.athlas_direction).attr('src', 'images/diod_green.png');
        // Сменить картинку переключателя
        $('#switcher_story').attr('src', 'images/switcher_story_'+document.athlas_direction+'.png');
    }
</script>
