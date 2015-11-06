function right_end(x, var1, var2, var3, show_number)  {
    if (show_number == undefined)
        show_number = 1
    if (show_number)
        ret=x+' ';
    x=x%100;
    if (x>=5 && x<=20)
        ret+=var3;
    else if (x%10==1)
        ret+=var1;
    else if (x%10>=2 && x%10<=4)
        ret+=var2;
else ret+=var3;
    return ret;
}



function tick(ending) {
    p = new Date();
    remains = ending - p.getTime()
    if (remains<0)  {
        clearInterval(document.intId)
        transfer('wrong')
    }
    $('#timer').html ('У гипнотизера осталось '+right_end(Math.round(remains/1000), 'секунда', 'секунды','секунд')+', чтобы дать тебе нужное указание')
}

function start_timer(seconds)  {
    p=new Date();
    ending = p.getTime() + seconds*1000
    console.log('ending', ending % 100)
    $('#start_timer').hide()
    document.intId = setInterval('tick('+ending+')', 1000)
    return false;
}

function awakening()	{
    if (confirm('ВНИМАНИЕ! Применять Экстренное Пробуждение следует ТОЛЬКО в одном из трех случаев:' + "\n"+
        ' 1) когда Гипнотизер или другой человек прошел Проверку "Очнись!", сказал тебе "Очнись!" и потряс тебя, '+ "\n"+
        ' 2) у тебя есть артефакт, умение или свойство, позволяющее тебе выходить из сеанса гипноза по желанию, ' + "\n"+
        ' 3) Ты находишься в Коллективном Бессознательном (т.е. твой сеанс начался с гипнокарты), и сейчас начинается Пропаганда. '+ "\n"+
        'Если любое из этих условий справедливо, нажми ОК, если нет - Отмена'))
        document.location.href='../athlas_process.php?action=extra_awake';
    return false;
}

function awakening_map()	{
    if (confirm('ВНИМАНИЕ! Применять Завершение Сеанса следует ТОЛЬКО в одном из трех случаев:' + "\n"+
        ' 1) когда Гипнотизер или другой человек прошел Проверку "Очнись!", сказал тебе "Очнись!" и потряс тебя, '+ "\n"+
        ' 2) у тебя есть артефакт, умение или свойство, позволяющее тебе выходить из сеанса гипноза по желанию, ' + "\n"+
        ' 3) сейчас начинается Пропаганда. '+ "\n"+
        'Если любое из этих условий справедливо, нажми ОК, если нет - Отмена'))
        document.location.href='../login.php';
    return false;
}

function exp_select(choice) {
//    Отобразить текст нужного выбора
    $('.select_result#'+choice).slideDown()
//  Скрыть все селекторы
    $('.selector').slideUp();
    if (choice > 0) // Только если цифра
        return;
    jQuery.ajax({
        url: '../athlas_process.php',
        method: 'POST',
        data: {
            action: 'process_experiment',
            select: choice
        },
        success: function(data) {
            eval('data='+data)
//            console.log(data)
            if (data.success && data.hit_lost > 0)   {
                $('#blood_splash').show()
                $('#blood_splash').addClass('blood_'+data.hit_lost)
                $('#blood_splash').animate({opacity:1}, 300)
                setTimeout(function(){
                    $('#blood_splash').animate({opacity:0}, 1500, 'linear', function(){
                        $('#blood_splash').removeClass('blood_'+data.hit_lost)
                        $('#blood_splash').hide()
                    })
                }, 1000)
            }
        }
    })
    return false;
}


function athlas(page)   {
    jQuery.ajax({
        url: 'athlas_process.php',
        method: 'POST',
        data: {
            action: 'set_page',
            page: page
        },
        success: function(data) {
            eval('data='+data)
            if (data.success)
                document.location.href='view/athlas.php';
        }
    })
}

function enter_from_map(location)   {
    jQuery.ajax({
        url: 'athlas_process.php',
        method: 'POST',
        data: {
            action: 'enter_from_map',
            location: location
        },
        success: function(data) {
            eval('data='+data)
            if (data.success)
                document.location.href='view/athlas.php';
        }
    })
}