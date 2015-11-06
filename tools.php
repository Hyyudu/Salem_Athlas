<?php
/*
 * Created on 05.08.2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once('config.php');
 $year = file_get_contents('version.txt');

class tools {

	static $_work_days = array();
    static $fl_filename = 'fl.dat';

	/**
	 * Конвертирует текст из windows-1251 в utf8
	 * @param string $txt - текст
	 * @return string строка в UTF8
	 */
	static function cyr2utf($txt) {
		//return mb_convert_encoding($txt, "utf-8", "windows-1251");
		if (is_array($txt)) {
			foreach ($txt as $k => $v) {
				$txt[$k] = self::cyr2utf ($v);
			}
			return $txt;
		}

		if (!self::is_utf8($txt)) {
			$txt = iconv("windows-1251", "utf-8", $txt);

		}

		return $txt;
	}
	/**
	 * Конвертирует текст из utf8 в windows-1251
	 * @param string $txt - текст utf8
	 * @return string строка в windows-1251
	 */
	static function utf2cyr($txt) {

		if (is_array($txt)) {
			foreach ($txt as $k => $v) {
				$txt[$k] = self::utf2cyr ($v);
			}
			return $txt;
		}

		if (self::is_utf8($txt)) {
			return iconv("utf-8", "windows-1251", $txt);

		}

		return $txt;

		//return mb_convert_encoding($txt, "windows-1251", "utf-8");
	}
	/**
	 * Проверяет - это рольф или нет? true - рольф, false - что-то другое
	 */
    static function right_end($x, $var1, $var2, $var3, $show_number=true)  {
        if ($show_number)
            $ret=$x.' ';
        $x=$x%100;
        if ($x>=5 && $x<=20)
            $ret.=$var3;
        elseif ($x%10==1)
            $ret.=$var1;
        elseif ($x%10>=2 && $x%10<=4)
            $ret.=$var2;
        else $ret.=$var3;
        return $ret;
    }
	/**
	 * Проверка строку на кодировку UTF-8
	 * @param $str
	 * @return bool
	 */
	public static function is_utf8 ($str) {

		return (bool) preg_match('//u', $str);

	}


    /**
     * Проверяет, начинается ли строка $str на подстроку $token
     * @author IIvanov
     * @param $str
     * @param $token
     * @param bool $ignore_case - игнорировать различия в регистре
     * @return bool
     */
    static function startswith($str, $token, $ignore_case=false)   {
        $str = substr($str, 0, mb_strlen($token));
        if ($ignore_case)   {
            $str = strtolower($str);
            $token = strtolower($token);
        }
        return $str==$token;
    }

    /**
     * Проверяет, заканчивается ли строка $str на подстроку $token
     * @author IIvanov
     * @param $str
     * @param $token
     * @param bool $ignore_case - игнорировать различия в регистре
     * @return bool
     */
    static function endswith($str, $token, $ignore_case=false)   {
        $str= substr($str, -mb_strlen($token));
        if ($ignore_case)   {
            $str = strtolower($str);
            $token = strtolower($token);
        }
        return $str==$token;
    }
	/**
	 * @param $arrVals
	 */
	public static function arrKey2Upper( &$arrVals ) {

		foreach( $arrVals as $key => $item ) {

			$key2 = strtoupper($key);
			if ( $key2 != $key) {
				unset($arrVals[$key]);
				$arrVals[$key2]=$item;
				$key=$key2;
			}

			if ( is_array($item) ) { self::arrKey2Upper($arrVals[$key]); }
		}
	}

	/**
	 * @param $arrVals
	 */
	public static function arrKey2Lower( &$arrVals ) {

		foreach( $arrVals as $key => $item ) {

			$key2 = strtolower($key);
			if ( $key2 != $key) {
				unset($arrVals[$key]);
				$arrVals[$key2]=$item;
				$key=$key2;
			}

			if ( is_array($item) ) { self::arrKey2Lower($arrVals[$key]); }
		}
	}

    function csv2arr($file, $delimiter = ';') {
        $data = false;
        if (($handle = fopen($file, "r")) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter)) !== FALSE) {
                for ($j=0; $j<count($lineArray); $j++) {
                    $data[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $data;
    }

    public static function read_csv($filename, $assoc_field='', $needed_fields = array(), $use_ciphered=false)  {
        $tempfile = '~tmp.dat';
        if ($use_ciphered)  {
            $ciphered_file = str_replace('.csv', '.dat', $filename);
            if (file_exists($filename))
                file_put_contents($ciphered_file, base64_encode(file_get_contents($filename)));
            $text = file_get_contents($ciphered_file);
            file_put_contents($tempfile, base64_decode($text));
            $f = fopen($tempfile,'r');
        }
        else
            $f=fopen($filename,'r');
        while ($row = fgetcsv($f, 0, ';'))   {
            $row = tools::cyr2utf($row);
            if (!isset($fields))   {
                $fields = $row;
                $last = array_search('—', $fields);
                if ($last)
                    $fields = array_splice($fields, 0, $last);
            }
            else {
                $row = array_splice($row, 0, count($fields));
                if ($row)   {
                    while (count($row) < count($fields))
                        $row[]='';
    //                Делаем ассоциативный массив по полям
                    $arr = array_combine($fields, $row);
    //                Оставляем только нужные поля
                    if ($needed_fields)
                        foreach ($arr as $key=>$val)
                            if (!in_array($key, $needed_fields))
                                unset($arr[$key]);
                    if ($assoc_field)
                        $out[strtolower($arr[$assoc_field])] = $arr;
                    else
                        $out[] = $arr;
                }
            }
        }
        if (file_exists($tempfile))
            @unlink($tempfile);
        return $out;
    }

    public static function getPage($address)    {
        $address = explode('/', $address);
        return $_SESSION['athlas_data'][$address[0]][$address[1]][$address[2]];
    }

    public static function decipher_address($address)   {
        $levels = array(
            1 => array('exodus_1', 'exodus_2', 'travel_1'),
            2 => array('ilion_1', 'ships_1', 'ships_2', 'travel_2'),
            3 => array('exodus_3', 'ilion_2', 'ships_3', 'ships_4', 'travel_3'),
            4 => array('ilion_3', 'ships_5')
        );
        $address = explode('/', $address);
        $data = explode('_', $address[2]);
        if ($data[0] == 'random')  {
            $level = $data[2];
            $color = $data[1];
            $page = $levels[$level];
            $page = $page[rand(0, count($page)-1)];
            return $address[0].'/'.$address[1].'/'.$color.'_'.$page;
        }
        else
            return join('/', $address);
    }

    public static function renderPage($address) {
        global $MAIN_DIR;

        $item = tools::getPage($address);
        $template = file_get_contents('templates/athlas.htm');

//        Если это не неведомое, не песочница и не меморизы - обрезаем сцену под коэффициент
        if (!tools::startswith($address, 'unknown') && !tools::startswith($address, 'common/memories') && $_SESSION['doctor_skills']['text_size']) {
            $item['scen'] = tools::splice_text($item['scen'], $_SESSION['doctor_skills']['text_size']);
        }

        if (!$_SESSION['labels']['memories_passed'])
            $_SESSION['labels']['memories_passed'] = array();
        //        Если это меморизы, и на этой странице уже были - разрешаем второй раз не читать
        if(strpos($_SESSION['current_page'], 'memories') && in_array($_SESSION['current_page'], $_SESSION['labels']['memories_passed'])){
            $arr = tools::split_text($item['scen']);
//            print_r($arr);
            if (count($arr)>9)
                $item['scen'] = join('',array_slice($arr, 0, 5))."{3mm}...{3mm}".join("", array_slice($arr, -4));
        }


        $out = $template;
        if (!$item['image'])
            $item['image'] = $item['page'].'.jpg';
        $item['athlas_page'] = '';
        $sack = new cStoneSet($_SESSION['sack']);
        $item['sack_stones'] = $sack->getStonesText();
        $heap = new cStoneSet($_SESSION['heap']);
        $item['heap_stones'] = $heap->getStonesText();
        $patient = $_SESSION['patient'];
        if ($item['todos'])
            $item['todos'] = "<img src='../images/question.png' class=img_question>" . $item['todos'];
        foreach ($item as $key=>$value)
            $out = str_replace("{".$key."}", $value, $out);


        $out = tools::replace_hiddens($out);

        $out = tools::replace_template_conditions($out);
        $out = tools::replace_page_fog($out);
        $out = tools::replace_template_conditions($out);

        // Заменяем hypno_next
        if (count($_SESSION['labels']['hypno_page_sequence']))
            $out = str_replace('{hypno_next}', 'Иди {page:'.$_SESSION['labels']['hypno_page_sequence'][0].'}', $out);
        else
            $out = str_replace('{hypno_next}', '{hypno_end}', $out);

        $out = preg_replace('~\{awake:(.*?)\}~', 'Перейди {page:unknown/sandbox/random_\\1}', $out);
        $out = preg_replace('~\{page:(.*?)\}~', '<div class=button><a onclick="transfer(\'\1\');">НА СЛЕДУЮЩУЮ СТРАНИЦУ</a></div>', $out);

        $repl = file_get_contents($MAIN_DIR."replacements.py");
        $repl = substr($repl, strpos($repl, '{'));
        $repl = json_decode($repl,1);
        foreach ($repl as $key=> $value)
            $out = str_replace("{".$key."}", $value, $out);

//        die("<pre>".$out."</pre>");
        return $out;
    }

    public static function read_fl()    {

        if (!file_exists(tools::$fl_filename))
            $frozen_locations = array();
        else
            $frozen_locations = json_decode(file_get_contents(tools::$fl_filename), true);
        return $frozen_locations;
    }

    public static function replace_page_fog($out)   {
        global $year;

        preg_match_all('~\{page_fog:(.*?)\}~', $out, $matches, PREG_SET_ORDER);
//print_r($matches);
        foreach ($matches as $m)    {
            list($loc, $qty) = explode('_', $m[1]);
//            $qty = substr($m[1], -1);
//            Проверяем, не заморожена ли локация
            $frozen_locations = tools::read_fl();
			$freeze_time = $year=='1945' ? 9*3600 : 5400;
//            Если с момента заморозки не прошло 9 часов - ссылаемся на пустую страницу
            if (time() - $frozen_locations[$loc] < $freeze_time)
                $replace = "{page:empty}";
            else    {
//                $pad = str_repeat('?', $qty);
                $replace = "{if fog:\$sack->has('?')}{page:".$m[1]."}{else}{page:fog}{endif fog}";
            }
//            echo $m[0]."<br>".$replace;
            $out = str_replace($m[0], $replace, $out);
        }
//        die($out);
        return $out;

    }

    public static function freeze_location($loc)    {
        $frozen_locations = tools::read_fl();
        $frozen_locations[$loc] = time();
        file_put_contents(tools::$fl_filename, json_encode($frozen_locations));
    }

    public static function read_dicts($item)    {
        global $MAIN_DIR;
        $locations = file_get_contents($MAIN_DIR.'dicts.py');
        preg_match('~'.$item.' = (\{.*?\})~is', $locations, $m);
        return json_decode($m[1], 1);
    }

    public static function check_auth()   {
        if (!isset($_SESSION['doctor']) || !isset($_SESSION['patient']))
            header('Location: login.php');
    }

    public static $COLORS =  array('R' => 'red', 'G' => 'green', 'B' => 'blue', 'Y' => 'yellow', 'K' => 'black');

    public static function replace_template_conditions($text)   {
        $preg = "~{if(.*?):(.*?)}(.*?){endif\\1}~isu";
        preg_match_all($preg, $text, $m, PREG_SET_ORDER);
        $sack = new cStoneSet($_SESSION['sack']);
        $heap = new cStoneSet($_SESSION['heap']);
        $patient = $_SESSION['patient'];
        $drug = $_SESSION['exp']['drug'];
        foreach ($m as $arr)    {
            $full_text = $arr[0];
            $cond = $arr[2];
            $vars = $arr[3];
//            print_r($arr);

//            $cond = '';
            eval('$cond = '.$cond.';');
//            echo $cond."\n";
            $vars = explode('{else}', $vars);
            $output = $cond ? $vars[0] : $vars[1];
            $text = str_replace($full_text, $output, $text);
        }
        return $text;
    }

    public static function replace_hiddens($text)   {
        preg_match_all('~\{hidden\}(.*?)\{ehidden\}~', $text, $matches, PREG_SET_ORDER);
        $cnt = 1;
        foreach ($matches as $arr)    {
            preg_match('~page\:next\#(.*?)\}~', $arr[1], $page);
            $page = $page ? $page[1] : $cnt++;
            $line="<div class=selector><a href=# onclick='exp_select(\"$page\");return false'>Нажми, чтобы выбрать</a></div><div class=select_result id=$page>".str_replace('next#'.$page, '', $arr[1])."</div>\n";
            $text = str_replace($arr[0], $line, $text);
        }
        return $text;
    }

    public static function log($msg, $nobr = false)    {
        file_put_contents('hypno.log', $msg.($nobr? '': "\n"), FILE_APPEND);
    }

    public static function start_log($where)  {
        $address = explode('/', $_SESSION['current_page']);
        if (!$_SESSION['log_started'])  {
            tools::log(date('d.m.Y H:i:s').
                ". Hypno start\nDoctor: ".$_SESSION['doctor'].". Patient: ".$_SESSION['patient']['character_name']);
            if ($_SESSION['sneaky'])
                tools::log('*SNEAKY* ', true);
            tools::log('Entered from: '.$where);
            tools::log($_SESSION['sack']);
            tools::log($address[1].'/'.$address[2], true);
            $_SESSION['log_started'] = 1;
        }
        else
            tools::log(" -> ".$address[1].'/'.$address[2], true);
    }

    public static $PWD_TYPES = array('normal', 'certified', 'sneaky', 'powerful', 'uncertified', 'amulet');

    public static function get_pwd($pwd, $type) {
        if ($type == 'normal') return $pwd;
        if ($type == 'amulet') return 'balthasar';

        $offset = array_search($type, tools::$PWD_TYPES);
        $suffix = abs(hexdec(substr(md5($pwd), $offset, $offset+6))%1000);
        return $pwd.$suffix;
    }

    public static function check_pwd($need, $gotten) {
        foreach (tools::$PWD_TYPES as $type)
            if (tools::get_pwd($need, $type) == $gotten)
                return $type;
        return false;
    }


    public static function splice_text($text, $quot)    {
        if ($quot >=1 || !$text )
            return $text;
        $start_byte = rand(0, strlen($text)*(1-$quot));
        while (!preg_match("~^[\.\?\!] ~", mb_substr($text,$start_byte))) {
            $start_byte--;
            if ($start_byte <=0)    {
                $start_byte = 0; break;
            }
        }
        if ($start_byte>0)
            $start_byte += 2;
        $end_byte = $start_byte+round(strlen($text)*$quot);
        while (!preg_match('~[\.\?\!] $~', mb_substr($text,0,$end_byte))) {
            $end_byte++;
            if ($end_byte > mb_strlen($text))
                break;

        }
        $end_byte-=3;
        $out = trim(mb_substr($text, $start_byte, ($end_byte-$start_byte+1)));
        $out = '...'.$out.'...';
        return $out;
    }

    public static function split_text($text)  {
        preg_match_all('~.*?[\?\!\.]+(\{\dmm\})* ?~', $text, $matches);
        return $matches[0];
    }

}