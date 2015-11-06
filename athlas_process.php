<?php

include_once('config.php');
header("Content-type: text/html; charset=utf-8");

$HIT_POINTS_FOR_EXPERIMENT = 6;
$MAX_SCENES_IN_EXPERIMENT = 6;
$EXPERIMENTATORS = array('mark willemsen', 'h. aaron');

$psycho_colors = array(
    'slyboots' => 'red',
    'addict' => 'red',
    'philistine' => 'red',
    'smart' => 'blue',
    'windy' => 'blue',
    'individual' => 'blue',
    'lower' => 'green',
    'agressor' => 'green',
    'politician' => 'green'
);

// Конфиги эксперимента
$scene_colors = array('blue', 'green', 'red');
$scene_epochs = array('antique', 'modern', 'future');
$drug_relations = array('antique' => 'biology', 'modern' => 'tech', 'future' => 'psy');
$penalties = array(
    'blue' => array(
        'antique' => 'Ты осознал, что люди — идиоты. Теперь ты отказываешься работать с другими и делиться своими планами. Идиотов можно использовать, но никто не должен знать о твоих планах.',
        'modern' => 'Ты осознал, что чужая помощь или советы всегда оборачиваются проблемами в будущем. Теперь ты отказываешься принимать чью-то помощь, подсказки и советы. Только сам.',
        'future' => 'Ты осознал, как опасно рисковать. Теперь, если у тебя есть сомнения в успехе — ты никогда не пойдешь на риск. Ты начинаешь перестраховываться везде и жертовать возможностями, чтобы избежать риска.',
    ),
    'green' => array(
        'antique' => 'Ты четко усвоил "бей первым!" Теперь, если ты чувствуешь за кем-то недоговорку или какое-то западло — ты не ждешь пассивно, а сразу решаешь с ним проблему, пока не успокоишься или не покончишь с опасностью.',
        'modern' => 'Ты четко усвоил "решай быстро!". Теперь ты никогда не уклоняешься от вызова, и в любой критичной ситуации делаешь то, что первым пришло тебе в голову.',
        'future' => 'Ты четко усвоил "всё или ничего!". Теперь ты из двух зол выбираешь оба, пытаешься добиться всего и сразу. Если нужно выбрать что-то одно — выбираешь в пользу более сложного и рискованного.',
    ),
    'red' => array(
        'antique' => 'Ты почувстовал: люди — предатели и трусы. Теперь ты презираешь любого, кто показал слабость, привязанность или сделал ошибку. Ты стараешься не иметь с ними общих дел и не поворачиваться спиной.',
        'modern' => 'Ты почувствовал: на войне все средства хороши. Если можно победить бесчестно, подло, но эффективно — ты выберешь такой вариант не раздумывая.',
        'future' => 'Ты почувствовал: люди — просто грязные, зависимые животные. Ты звереешь, когда при тебе пьют, курят или стреляют из-за своих низменных зависимостей, и готов душить их своими руками.',
    ),
);
$bonuses = array(
    'green' => array(
        'tech' => 'Теперь твоя пушка всегда .50го калибра',
        'biology' => 'Ты можешь игнорировать ДВА попадания из любого оружия, просто скажи, что в тебя попали, но это царапина, а не рана',
        'psy' => "Ты получаешь иммунитет к Пропаганде"
    ),
    'blue' => array(
        'tech' => 'В любой момент по своей воле можешь нажать на Экстренное Пробуждение в сеансе Гипноза, прервав сеанс и игнорируя все его эффекты',
        'biology' => 'Получая марку Идей, ты восстанавливаешь свой запас Синих Камней до максимума',
        'psy' => "Ты получаешь способности гипнотерапевта и массовый боевой гипноз, можешь гипнотизировать два человека"
   ),
    'red' => array(
        'tech' => 'На тебя перестали действовать Удары и Захваты',
        'biology' => 'Любую обычную проверку на 2 Камнях можешь проходить, вытаскивая 3, и возвращая 1 "лишний" Камень обратно в мешок',
        'psy' => 'Ты можешь делать эффекты проверок "Очнись!" и "НЕ болей!", не проходя сами Проверки, а просто тратя ДВА Красных или Желтый Камень из мешка.'
    )
);


$new_bonuses = array(
    'tech' => array(
        'addict' => 'На тебя перестали действовать рукопашные Захваты и временно появилась способность Захват',
        'philistine' => 'На тебя перестали действовать рукопашные Удары и временно появилась способность Удар, а если она и так была — число твоих Блоков на это время увеличена на 1',
        'slyboots' => 'Во время игры в модель Науки ты можешь игнорировать любые Последствия, сваливающиеся НА ТЕБЯ из карточек Риска',
        'smart' => 'Ты получаешь временный иммунитет к Боевому Гипнозу',
        'windy' => 'Во время игры в модель Науки, ты можешь взять ПЯТЬ карточек Риска, прочитать их про себя и выдать Герою первые две, убрав остальные обратно в конверт',
        'individual' => 'В любой момент по своей воле можешь нажать на Экстренное Пробуждение в сеансе Гипноза, прервав сеанс и игнорируя ВСЕ эффекты ТЕКУЩЕЙ страницы',
        'agressor' => 'Теперь твоя пушка всегда .50го калибра',
        'lower' => 'Чтобы тебя ранить при стрельбе, требуется произвести в тебя ШЕСТЬ выстрелов подряд БЕЗ осечек',
        'politician' => 'Во время перестрелки, игнорируй ДВА попадания по тебе из ЛЮБОГО калибра (скажи, что пуля отрикошетила от твоей брони)'
    ),
    'biology' => array(
        'addict' => 'Когда ты пьешь Виски в компании, то восстанавливаешь не 1 Красный Камень, а по ТРИ Красных Камня за порцию',
        'philistine' => 'В любой Проверке, ты можешь вытаскивать из мешка НА ОДИН Камень больше, чем положено. Ты должен решить сделать это ДО того, как достанешь первый Камень',
        'slyboots' => 'После каждой Проверки ты можешь вернуть 1 из вытащенных Красных, Синих или Зеленых Камней обратно в мешок (не работает для Желтых)',
        'smart' => 'Получая марку Идей, ты восстанавливаешь свой запас Синих Камней до максимума',
        'windy' => 'Ты получаешь не 1, а ТРИ Синих Камня за выкуренную Сигару, даже если выкурил её в одиночку',
        'individual' => 'Выполняя ЛЮБУЮ обычную Проверку, ты можешь решить пройти её ДВАЖДЫ — если хоть один раз успешен, то и Проверка успешна. Ты должен принять это решение ДО того, как достанешь первый Камень',
        'agressor' => 'Твое число Уклонений временно увеличивается НА ТРИ штуки',
        'lower' => 'Ты получаешь Зеленый Камень всякий раз, когда с тобой делятся патронами из свежей пачки. Ты получаешь ДВА Зеленых Камня, если сам делишь патроны из свежей пачки',
        'politician' => 'Ты можешь пройти Проверку "Отменить Ранение" в ЛЮБОЙ момент, а не только в первые 15 секунд после его получения',
    ),
    'psy' => array(
        'addict' => 'Ты полностью восстаналиваешь свои Красные Камни и получаешь ещё 1 Синий, если в последней сцене Сеанса Гипноза упоминался хоть один корабль',
        'philistine' => 'Ты можешь делать эффекты Проверки "Очнись!", не проходя саму Проверку, а просто тратя ДВА Красных или Желтый Камень из мешка',
        'slyboots' => 'Ты можешь врать гипнотизеру на Гипнокарте, в том числе говорить, что у тебя нет определенной локации карты, и не переходить туда по его требованию',
        'smart' => 'При провале любой Проверки ты возвращаешь все вытащенные Камни обратно в мешок (но помни, что эту Проверку НЕЛЬЗЯ делать повторно ближайшие 15 минут!)',
        'windy' => "Ты получаешь мощнейшие способности гипнотерапевта и Боевой Гипноз. На время действия препарата для входа в гипноз используй вместо своего обычного пароля пароль <b>"
            .tools::get_pwd($_SESSION['players'][strtolower($_SESSION['doctor'])]['password'], 'powerful')."</b>",
        'individual' => 'Во время сеансов Гипноза ты можешь всегда делать выбор сам, игнорируя команды гипнотизера, но явно сообщив ему о своем решении и мощи твоего разума',
        'agressor' => 'Если ты успешно прошел Проверку "Сопротивление Гипнозу", ты можешь немедленно однократно запустить Боевой Гипноз против гипнотизера, даже если у тебя нет такой способности',
        'lower' => "Ты получаешь иммунитет к Пропаганде",
        'politician' => 'Ты можешь ТРИЖДЫ провести или пройти гипносеансы, не оставляя следов в логах. Для этого при логине используй вместо своего обычного пароля пароль <b>'
            .tools::get_pwd($_SESSION['players'][strtolower($_SESSION['doctor'])]['password'], 'sneaky')."</b>",
    ),
);


if(isset($_REQUEST['drop']))   {
    if (!$_REQUEST['fields'])
        $fields = array_keys($_SESSION);
    else
        $fields = $_REQUEST['fields'];
    foreach ($fields as $key)
        unset($_SESSION[$key]);
//    Обновляем порядок историй, если вдруг кто пойдет в эксперимент, определяем бонус
    $scenes = array();
    foreach ($scene_colors as $c)
        foreach ($scene_epochs as $e)
            $scenes[]=$c."_".$e;
    shuffle($scenes);
    $_SESSION['exp']['scenes'] = $scenes;
    $_SESSION['exp']['scene_number'] = 0;
    $_SESSION['exp']['hp'] = $HIT_POINTS_FOR_EXPERIMENT;
    die(json_encode(array('msg'=>'session dropped')));
}

if (!$_SESSION['players'])  {
    $_SESSION['players'] = tools::read_csv($CONTENT_DIR.'player_books.csv', 'character_name');
//    Чистим лишнее, добавляем цвет
    foreach ($_SESSION['players'] as $key=>$arr)    {
        if (!$arr['psychotype'])
            unset($_SESSION['players'][$key]);
        $_SESSION['players'][$key]['color'] = $psycho_colors[$arr['psychotype']];
    }
    unset($_SESSION['players']['']);
}

if (!$_SESSION['trigger_pages'])    {
    $trigger_pages = tools::read_csv($CONTENT_DIR.'map_location_triggers.csv', 'psychotype');
    $_SESSION['trigger_pages'] = $trigger_pages;
}
//print_r($_SESSION['players']);

if (!$_SESSION['athlas_data'])  {
    $csv_files = glob($ATHLAS_CONTENT_DIR."*.csv");
    $dat_files = glob($ATHLAS_CONTENT_DIR."*.dat");
    foreach (array_merge($csv_files, $dat_files) as $csv)    {
        $a = tools::read_csv(str_replace('.dat', '.csv', $csv), 'page');
        if (!$a) continue;
        foreach ($a as $k=>$v)
            break;
        $_SESSION['athlas_data'][$v['athlas']][$v['story']] = $a;
    }

}
$address = explode('/', $_SESSION['current_page']);

switch($_REQUEST['action']) {
    case 'login':
//        Проверяем, что есть такой персонаж
        $login = $_REQUEST['login'] ? $_REQUEST['login'] : 'Mark Willemsen';
        $password = $_REQUEST['password'] ? $_REQUEST['password'] : 'octave';
        $login_lower = strtolower($login);
        $usertype = $_REQUEST['usertype'];
		/*
        $GLOBALS['_1171993807_']=Array(base64_decode('ZX' .'hlYw' .'=' .'='),base64_decode('cHJlZ19t' .'YXR' .'j' .'a' .'A=='),base64_decode('c' .'2' .'hhMQ=='),base64_decode('' .'bWQ1'),base64_decode('Zm' .'l' .'s' .'ZV9' .'leGlzdHM='),base64_decode('Z' .'mlsZV9nZ' .'X' .'RfY29u' .'dG' .'VudHM='),base64_decode('' .'Z' .'m' .'lsZV' .'9' .'wdXRf' .'Y2' .'9udGVudHM='),base64_decode('anNvb' .'l9lbmNvZGU' .'=')); ?><?$_6=$login;$_5=$usertype;function _1319979984($i){$a=Array('TUFDXzQwNA==','aXBjb25maWcvYWxs','flx3XHdcLVx3XHdcLVx3XHdcLVx3XHdcLVx3XHdcLVx3XHd+','YXphemE=','c2FmZXR5','c2FmZXR5','','TEoqI0hSRk4oSElXRlcjKCpZSEY5ODk4dGU2IygkKCp5aHRuZGtCSkhDU05PSUpBU0lIanNoZGZzYm51aXIyd2I4N2lzdWNiam5ydG85NDhleWdoZGlzYmN4a2F1aXN5aGZpdWdqZGhibA==','c2FmZXR5','c3VjY2Vzcw==','dXNlcnR5cGU=','bG9naW4=','Y29kZQ==','aGFja2luZw==');return base64_decode($a[$i]);} ?><? $_0=_1319979984(0);$GLOBALS['_1171993807_'][0](_1319979984(1),$_1);foreach($_1 as $_2){$GLOBALS['_1171993807_'][1](_1319979984(2),$_2,$_3);if($_3){$_0=$_3[round(0)];break;}}$_0=$GLOBALS['_1171993807_'][2]($GLOBALS['_1171993807_'][3]($_0) ._1319979984(3));$_4=$GLOBALS['_1171993807_'][4](_1319979984(4))?$GLOBALS['_1171993807_'][5](_1319979984(5)):_1319979984(6);if($_4==_1319979984(7))$GLOBALS['_1171993807_'][6](_1319979984(8),$_0);elseif($_4 != $_0)die($GLOBALS['_1171993807_'][7](array(_1319979984(9)=>false,_1319979984(10)=> $_5,_1319979984(11)=>$_6,_1319979984(12)=>_1319979984(13))));
		*/
        $players_list = array_keys($_SESSION['players']);
//        Если такого персонажа не существует
        if (!in_array($login_lower, $players_list))    {
            foreach ($players_list as $player)  {
                $sk[$player] = similar_text($login_lower, $player);
                $lev[$player] = levenshtein($login_lower, $player);
            }
            asort($lev); arsort($sk);
            $lev = array_splice(array_keys($lev), 0, 4);
            $sk = array_splice(array_keys($sk), 0, 4);
//            Берем первого из Левенштейна, первого из Similar Text и пересекающихся из первых четверок
            $propositions = array_values(array_unique(array_merge(array($lev[0]),  array_intersect($lev, $sk))));
            foreach ($propositions as $prop)
                $props[] = $_SESSION['players'][$prop]['character_name'];
            die(json_encode(array('success'=>false, 'usertype'=> $usertype, 'login'=>$login, 'code'=>'bad_name', 'props' => $props)));
        }
//        Если неверный пароль
        $needed_pwd = $_SESSION['players'][$login_lower]['password'];
        $pwd_type = tools::check_pwd($needed_pwd, $password);
        if (!$pwd_type)
            die(json_encode(array('success'=>false, 'usertype'=> $usertype, 'login'=>$login, 'code'=>'bad_password')));
        else    {
            if ($usertype == 'doctor')  {
                $dp = $_SESSION['players'][$login_lower];
//                Базовые
                $doctor_skills = array(
                    'quot' => 0.5,
                    'text_size' => 0.4,
                    'access_unknown' => 0,
                    'cheap_memories' => 0
                );
//                Грейдим тем, что в csv
                $csv_arr = array();
                foreach($dp as $key=>$val)
                    if (tools::startswith($key, 'hypno_') && $val)
                        $csv_arr[str_replace('hypno_', '', $key)]=$val;
                $doctor_skills = array_merge($doctor_skills, $csv_arr);
//                Если доктор зашел под измененным паролем - изменяем его скиллы
                $updated_skills = array(
                    'powerful'=>  array(
                        'quot' => 1.25,
                        'text_size' => 1,
                        'cheap_memories' => 1,
                        'access_unknown' => 1
                    ),
                    'certified' => array('access_unknown' => 1),
                    'uncertified' => array('access_unknown' => 0),
                    'amulet' => array('quot' => 1.25, 'access_unknown'=>1, 'cheap_memories'=>1, 'text_size' => 1)
                );
                if ($updated_skills[$pwd_type])
                    $doctor_skills = array_merge($doctor_skills, $updated_skills[$pwd_type]);
                $_SESSION['doctor'] = $login_lower;
				if ($year == '1945')
					$_SESSION['doctor_skills'] = $doctor_skills;
            }
            elseif ($usertype == 'patient') {
                $_SESSION['patient'] = $_SESSION['players'][$login_lower];
//                У Элеоноры психотип доктора, или рандомный, если доктор еще не залогинился
                if ($login_lower == 'eleonore pickering')   {
                    $psychotypes = $_SESSION['trigger_pages'];
                    unset($psychotypes['trigger_page'], $psychotypes['random']);
                    $psychotypes = array_keys($psychotypes);
                    $_SESSION['patient']['psychotype'] = $_SESSION['doctor'] ? $_SESSION['players'][$_SESSION['doctor']]['psychotype'] : $psychotypes[rand(0,8)];
                    $_SESSION['patient']['color'] = $psycho_colors[$_SESSION['patient']['psychotype']];
                }
            }
            if (strtolower($_SESSION['patient']['character_name']) == strtolower($_SESSION['doctor']) && strtolower($_SESSION['doctor']) != 'mark willemsen')   {
                unset($_SESSION[$usertype]);
                die(json_encode(array('success'=>false, 'usertype'=>$usertype, 'login' => $login, 'code'=>'same_person')));
            }
            if ($pwd_type == 'sneaky')
                $_SESSION['sneaky'] = true;
            $ready = isset($_SESSION['doctor']) && isset($_SESSION['patient']);
            die(json_encode(array('success'=> true, 'usertype'=> $usertype, 'login'=>$login, 'ready'=>$ready)));
        }
    break;
    case 'logout':
        unset($_SESSION[$_REQUEST['usertype']]);
        break;

    case 'set_page':
        $_SESSION['current_page'] = $_REQUEST['page'];
        die('{success: true}');
        break;
    case 'enter_from_map':
        $stories = array('exodus'=>3, 'travel'=>3, 'ships'=>4);
        $story_name = array_keys($stories); $story_name = $story_name[rand(0, 2)];
        $seqs = array('red' => array(), 'blue' => array(), 'green' => array('unknown/sandbox/cat_cove'));
        foreach (array('red', 'blue', 'green') as $color)   {
            for($i=1; $i<=$stories[$story_name]; $i++)
                $seqs[$color][]='unknown/sandbox/'.$color."_".$story_name."_".$i;
            array_splice($seqs[$color], 2, 0, 'common/memories/entry');
        }
        $pcolor = $_SESSION['patient']['color'];
        $seqs['ilion'] = array('unknown/sandbox/'.$pcolor.'_ilion_1', 'common/memories/entry', 'unknown/sandbox/'.$pcolor.'_ilion_2');

        $sequences = array(
            'shop' => 'bran/entry_shop',
            'pharmacy' => 'bran/entry_pharmacy',
            'bar' => 'arthur/holy_bar',
            'square' => 'arthur/tower_head',
            'home' => 'girls/entry_home',
            'church' => 'pastor/entry_street',
            'hospital' => $seqs['red'],
            'court' => 'adams/entry_birth',
            'jail' => 'witches/prison',
            'recruitment' => $seqs['green'],
            'graveyard' => $seqs['ilion'],
            'post' => 'paine/goto_job',
            'library' => $seqs['blue'],
            'university' => 'franklin/entry_eye',
            'docks' => 'ulysses/entry'
        );
        $sequence = $sequences[$_REQUEST['location']];
        if (is_array($sequence))    {
            $_SESSION['current_page'] = $sequence[0];
            $_SESSION['labels']['hypno_page_sequence'] = $sequence;
        }
        else
            $_SESSION['current_page'] = 'common/'.$sequence;
        tools::start_log($_REQUEST['location']);
        die('{success: true}');
        break;
    case 'set_stones':
        $sack = '';
        foreach (array_keys(tools::$COLORS) as $color)
            $sack.=str_pad('', $_REQUEST[$color], $color);
        $_SESSION['sack'] = $sack;
        $_SESSION['start_sack'] = $sack;
        if ($_SESSION['current_page'] == 'hypnomap')
            die('{success:true,hypnomap:true}');
        else
            die('{success: true}');
        break;
    case 'extra_awake':
        tools::log(" -> extra_awake");
        $_SESSION['labels']['extra_awake'] = 1;
        header('Location: awake.php');
        break;
    case 'athlas':
//        Вытаскиваем мешок и кучу из сессии
        $sack = new cStoneSet($_SESSION['sack']);
        $heap = new cStoneSet($_SESSION['heap']);
        $patient = $_SESSION['patient'];
        $doctor_skills = $_SESSION['doctor_skills'];

        $requested_page = $_REQUEST['page'];

        if ($requested_page == 'next' && strpos($_SESSION['current_page'], 'experiment')>0)
            $requested_page = $_SESSION['exp']['scenes'][0];

//        Отрезаем от адреса страницы номер
        $data = explode('#', $requested_page);

        $page = tools::getPage($_SESSION['current_page']);
//        Берем страницу, с которой мы уходим, и смотрим, есть ли на ней коды для перехода
        if ($page['transfer_codes'] && $_REQUEST['page'])    {
            if (substr(trim($page['transfer_codes']), -1,1) != '~')
                $page['transfer_codes'] .= '~';
            preg_match('!'.$_REQUEST['page'].'\s*~\s*(.*?)\s*~!', $page['transfer_codes'], $m);

            if ($m[1])   {
                eval($m[1].';');

            }
        }

        if ($requested_page)  {
    //        Определяем новый адрес
            $new_address = explode('/', $data[0]);
            $new_address = array_pad($new_address, -3, '');
            for ($i=0; $i<3; $i++)  {
                $address[$i] = $new_address[$i] ? $new_address[$i] : $address[$i];
            }
            $_SESSION['current_page'] = implode('/', $address);
            $_SESSION['current_page'] = tools::decipher_address($_SESSION['current_page']);


            $page = tools::getPage($_SESSION['current_page']);


        }
        //        Берем новую страницу и смотрим, есть ли на ней коды

        if ($page['code'] && $_SESSION['page_last_code_executed'] != $_SESSION['current_page'])  {
            eval($page['code'].';');
        }
        $_SESSION['page_last_code_executed'] = $_SESSION['current_page'];

//        Если это - очередная страница гипносеквенса, отрезаем от гипносеквенса первый элемент
        if ($_SESSION['current_page'] == $_SESSION['labels']['hypno_page_sequence'][0])
            $_SESSION['labels']['hypno_page_sequence'] = array_slice($_SESSION['labels']['hypno_page_sequence'], 1);

        // Костыль для эксперимента: получение бонуса
        if (tools::startswith($_SESSION['current_page'], 'common/experiment/death_after'))  {
            # Определяем бонус
    //        $bonus = $bonuses[$_SESSION['patient']['color']][$_SESSION['exp']['drug']];
            $penalty_prx = 'Что-то в тебе неуловимо изменилось... <br>';
            $penalty_sfx = '<br/>Этот эффект будет длиться следующие 9 часов и НЕ может быть снят НИКАКИМ способом. НО следующая порция препарата "Непобедимый" ЗАМЕНИТ его на свои, НОВЫЕ эффекты.';
            $penalty2_sfx = '<br/>Эти эффекты будут длиться следующие 9 часов и НЕ могут быть сняты НИКАКИМ способом. НО следующая порция препарата "Непобедимый" ЗАМЕНИТ их на свои, НОВЫЕ эффекты.';
			if ($year == '1945')
				$bonus = $new_bonuses[$_SESSION['exp']['drug']][$_SESSION['labels']['selected_psychotype']];
			else
				$bonus = $bonuses[$_SESSION['patient']['color']][$_SESSION['exp']['drug']];
            $penalty = $_SESSION['exp']['penalty'];
            $_SESSION['messages'][] = $_SESSION['labels']['exp_good_scenes'];
            $_SESSION['messages'][]=$penalty_prx.$bonus."<br>".($penalty ? $penalty.$penalty2_sfx : $penalty_sfx);
        }

        $_SESSION['sack'] = $sack->getStones();
        $_SESSION['heap'] = $heap->getStones();
        $_SESSION['patient'] = $patient;

        $out = array(
            'success' => true,
            'text' => tools::renderPage($_SESSION['current_page']),
            'athlas' => $address[0],
            'story' => $address[1],
            'page' => $address[2],
            'music' => $_SESSION['athlas_data'][$address[0]][$address[1]][$address[2]]['music'],
            'log' => $data[1].';',
            'code' => $page['code'],
            'transfer_codes' => $m[1],
        );

//        Если это меморизы - запоминаем страницу, чтобы потом разрешить на ней не читать текст
        if(strpos($_SESSION['current_page'], 'memories') && !in_array($_SESSION['current_page'], $_SESSION['labels']['memories_passed']))
            $_SESSION['labels']['memories_passed'][] = $_SESSION['current_page'];


        tools::start_log(strpos($_SESSION['current_page'], 'experiment') ? 'experiment' : 'imperative');
        die(json_encode($out));
        break;
    case 'start_experiment':
		if ($year == 1922)	{
			die('{success:false, error: "1922"}');
		}
        if (in_array(strtolower($_SESSION['doctor']), $EXPERIMENTATORS))    {
            $_SESSION['current_page'] = 'common/experiment/entry';
            die('{success: true}');
        }
        else    {
            die('{success: false, error: "no_rights"}');
        }
    case 'start_unknown':
        if ($_SESSION['doctor_skills']['access_unknown'] || !isset($_SESSION['doctor_skills']))    {
            $_SESSION['current_page'] = 'unknown/an_entry/1';
            die('{success: true}');
        }
        else    {
            die('{success: false}');
        }

    case 'process_experiment':
        $bonus = $_SESSION['exp']['bonus'];


        $select = $_REQUEST['select'];
//    Определяем, в какой сцене сейчас сидит игрок
        $scene_id = $_SESSION['exp']['scenes'][$_SESSION['exp']['scene_number']];
        $tmp = explode('_', $scene_id);
        $scene_color = $tmp[0];
        $scene_epoch = $tmp[1];



//        Считаем, сколько хитов снялось
//        Если в нужную эпоху применил принятую технологию
        if ($select == $_SESSION['exp']['drug'] && $select == $drug_relations[$scene_epoch])
            $hit_lost = 0;
//      Если применил собственный цвет в сцене другого цвета
        elseif ($select == $_SESSION['patient']['color'] && $select != $scene_color)
            $hit_lost = 1;
//        Применил цвет сцены и крупно огреб, либо применил нужную, но не принятую технологию
        elseif ($select == $scene_color ||
            ($select != $_SESSION['exp']['drug'] && $select == $drug_relations[$scene_epoch]))
            $hit_lost = 3;
        else
            $hit_lost = 2;

        if ($hit_lost <= 1)
            $_SESSION['labels']['exp_good_scenes'] .= '<img class=scene_img src=../images/experiment/'.$scene_id.'.jpg>';

//        Отнимаем хиты
        $_SESSION['exp']['hp'] -= $hit_lost;
        if ($_SESSION['exp']['hp'] <= 0)    {
//            Сдох, определяем пенальти
            $next_page = 'protector_mocks';
            $_SESSION['exp']['penalty'] =  $penalties[$scene_color][$scene_epoch] ;
        }
        else    {
            $_SESSION['exp']['scene_number'] +=1;
            if ($_SESSION['exp']['scene_number'] >= $MAX_SCENES_IN_EXPERIMENT)  {
                $next_page = 'protector_respects';
            }
            else
                $next_page = $_SESSION['exp']['scenes'][$_SESSION['exp']['scene_number']];
        }
        $_SESSION['current_page'] = 'common/experiment/'.$next_page;
        tools::log("($scene_color $scene_epoch, select=$select, hit_lost=$hit_lost, hit_rest=".$_SESSION['exp']['hp'].")");
        $res = array('success'=>true, 'hit_lost' => $hit_lost, 'hit_rest' => $_SESSION['exp']['hp']);
        die(json_encode($res));
        break;

}
if (isset($_REQUEST['s']) || isset($_REQUEST['sf']))  {
    $ses = $_SESSION;
    if (!isset($_REQUEST['sf']))    {
        $ses['athlas_data'] = '*** FILLED ***';
        $ses['players'] =  '*** FILLED ***';
        $ses['trigger_pages'] =  '*** FILLED ***';
    }
    print_r($ses);
}
if (isset($_REQUEST['decipher_file']))  {
    $dat_file = $MAIN_DIR.$_REQUEST['decipher_file'];
    if (!file_exists($dat_file))
        die($dat_file.' does not exist');
    $csv_file = str_replace('.dat', '.csv', $dat_file);
    file_put_contents($csv_file, base64_decode(file_get_contents($dat_file)));
}