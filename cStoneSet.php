<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 15.06.15
 * Time: 12:58
 * To change this template use File | Settings | File Templates.
 */


class cStoneSet
{
//    Строка с буквами, означающими наличные в сете камни
    private $_stones;

    public function __construct($stones='') {
        $stones = strtoupper($stones);
        $this->_stones = '';
        for ($i=0; $i<strlen($stones); $i++)    {
            if (in_array($stones[$i], array_keys(tools::$COLORS)))
                $this->_stones .= $stones[$i];
        }
    }

//    Перемешивает камни
    private function _shuffle() {
        $arr = str_split($this->_stones);
        shuffle($arr);
        $this->_stones = implode('', $arr);
        return $this;
    }

//    Выбирает рандомный незапрещенный цвет
    private function _getRandColor($forbidden_rand)  {
        do {
            $c = array_keys(tools::$COLORS);
            $c = $c[rand(0,4)];
        } while (strpos($forbidden_rand, $c) !== FALSE);
        return $c;
    }

//    Выбирает незапрещенный цвет камня, наличествующего в мешке, или null
    private function _getExistRandColor($forbidden_rand)    {
        if (str_replace(str_split($forbidden_rand), array(), $this->_stones) =='')
            return null;
        do {
            $this->_shuffle();
        } while (strpos($forbidden_rand, $this->_stones[0]) !== FALSE);
        return $this->_stones[0];
    }

//    Пытается достать из сета некоторый набор камней, возвращает строку вытащенных камней.
//   'RG' - вытащить красный и зеленый (если они есть)
//   '??Y' - вытащить два рандомных и желтый
//   '*BR' - вытащить все синие и один красный
    private function _take($line, $forbidden_rand = 'K')    {
        if ($line == '*')
            $line = $this->_stones;
        $line = strtoupper($line);
        $drop_all = 0;
        $taken = '';
        for ($i=0; $i<strlen($line); $i++)  {
            $letter=$line[$i];
            if ($letter == '*')
                $drop_all = 1;
            elseif ($letter == '?')
                $letter = $this->_getExistRandColor($forbidden_rand);
            if ($letter != null && $letter != '*')  {
                $limit = $drop_all? -1: 1;
                $this->_stones = preg_replace('~'.$letter.'~', '', $this->_stones, $limit, $qty);
                $drop_all = 0;
                $taken .= str_pad('', $qty, $letter);
            }
        }
        return $taken;
    }

//    Добавляет в сет камни из строки
    public function add($line, $forbidden_rand = 'YK')  {
        if ($line == '*')
            $line = $this->_stones;
        $line = strtoupper($line);
        for ($i=0; $i<strlen($line); $i++)  {
            $letter=$line[$i];
            if ($letter == '?')
                $c = $this->_getRandColor($forbidden_rand);
            else
                $c = $letter;
            if (in_array($c, array_keys(tools::$COLORS)))
                $this->_stones .= $c;
        }
        return $this;
    }
	

//    Добавляет в сет камни из строки
    public function addRand()  {
		$args = func_get_args();
		if (is_array($args[0]) && count($args) == 2)	{
			$arr = $args[0];
			$forbidden_rand = $args[1];
		}
		else
			$arr = $args;
		$line = $arr[rand(0, count($arr)-1)];
		if ($forbidden_rand)
			$this->add($line, $forbidden_rand);
		else
			$this->add($line);
        return $this;
    }	

//  Сбрасывает из сета камни, если там такие есть. ? означает камень случайного цвета, кроме того, что указан в forbidden_rand
    public function drop($line, $forbidden_rand = 'K') {
        $this->_take($line, $forbidden_rand);
        return $this;
    }

//    Берет из сета камни согласно строке и формирует из них новый сет
    public function take($line, $forbidden_rand = 'K')  {
        $taken = $this->_take($line, $forbidden_rand);
        return new cStoneSet($taken);
    }

//    Передает камни в другой сет
    public function pass(&$where, $line='', $forbidden_rand = 'K')    {
        if ($line == '') $line = $this->_stones;
        $taken = $this->take($line, $forbidden_rand);

        if (!$where)
            $where = new cStoneSet($taken);
        else
            $where->add($taken->getStones());
        return $this;
    }

    public function stone2text($stone)  {
        if ($stone)
        return "<img class=stone src=../images/player_book/gem_".tools::$COLORS[$stone]."_2.png>";
    }

//    Возвращает триггер игрока для заданной локации
    public function getTrigger($location)   {
        return $_SESSION['trigger_pages'][$_SESSION['patient']['psychotype']][$location];
    }

//    Получает список всех камней сета в виде упорядоченной строки
    public function getStones() {
        $arr = str_split($this->_stones);
        sort($arr);
        $this->_stones = implode('', $arr);
        return $this->_stones;
    }

    public function getStonesText() {
        $arr = str_split($this->_stones);
        sort($arr);
        foreach ($arr as $col)
            $out .= $this->stone2text($col);
        return $out;
    }

//    Возвращает количество камней нужного цвета или общее количество камней
    public function getCount($color='')    {
        if ($color)
            return substr_count($this->_stones, $color);
        else
            return strlen($this->_stones);
    }

//    Возвращает число камней нужных цветов в отсортированном словаре
    public function getCounts($check, $desc=true)   {
        $check = str_split($check);
        foreach ($check as $color)
            $out[$color] = $this->getCount($color);
        if ($desc)
            arsort($out);
        else
            asort($out);
        return $out;
    }

    public function getRed()    {return $this->getCount('R');}
    public function getBlue()    {return $this->getCount('B');}
    public function getGreen()    {return $this->getCount('G');}
    public function getYellow()    {return $this->getCount('Y');}
    public function getBlack()    {return $this->getCount('K');}

    public function has($line, $forbidden_rand = 'K')  {
        $lines = explode('||', $line);
        foreach ($lines as $line)   {
            $taken = $this->_take($line, $forbidden_rand);
            $this->add($taken);
            if (strlen($taken) == strlen($line))
                return true;
        }
        return false;
    }

    public function swap($from, $to, $qty=1)    {
        if (is_array($from))
            $from = $from[rand(0, count($from)-1)];
        for ($i=0; $i<$qty; $i++)   {
            if ($this->has($from))	
                $this->drop($from)->add($to);
            else
				break;
        }
		return $this;
    }

    public function swapHard($from, $to)    {
        if ($this->has($from))
            $this->swap($from, $to);
        else
            $this->drop($from);
        return $this;
    }

    public function swapSoft($from, $to)    {
        $this->drop($from)->add($to);
        return $this;
    }

    public function msg($text)  {
        $_SESSION['messages'][]=$text;
        return $this;
    }



    public function diff($prev, $as_array=false) {
        $now = $this->_stones;
        $res = array();
        foreach (tools::$COLORS as $color => $color_name)  {
            $dif = substr_count($now, $color) - substr_count($prev, $color);
            if ($dif)
                $res[$color] = $dif;
        }
        arsort($res);
        if ($as_array) return $res;
        $out = '';
        foreach ($res as $col =>$qty)   {
            if ($qty < 0) $out.="<img class=stone src=../images/minus.png>";
            else $out.="<img class=stone src=../images/plus.png>";
            $out.=str_repeat($this->stone2text($col), abs($qty))."&nbsp; &nbsp; \n";
        }
        return $out;
    }
}
