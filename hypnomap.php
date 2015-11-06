<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 12.06.15
 * Time: 7:59
 * To change this template use File | Settings | File Templates.
 */
include_once('config.php');
header("Content-type: text/html; charset=utf-8");

tools::check_auth();

$txt = file_get_contents($MAIN_DIR.'locations_mapping.py');
$mapping = json_decode($txt,1);
//print_r($mapping);
$locations = tools::read_dicts('map_locations');
$cities = tools::read_dicts('cities');
$trigger_pages = $_SESSION['trigger_pages'];
$template = file_get_contents('templates/output_start.htm').
            file_get_contents('templates/hypnomap.htm').
            file_get_contents('templates/output_end.htm');
//echo $template;
$patient = $_SESSION['patient'];
//print_r($patient);

//Проверка - Салем или нет
if (strtolower($patient['city']) == 'salem')    {
    $template = str_replace(array('{ifsalem}', '{endifsalem}'), array('',''), $template);
}
else
    $template = preg_replace('~\{ifsalem\}.*?\{endifsalem\}~', '', $template);

$repl = array(
    'character_name' => $_SESSION['patient']['character_name'],
    'cityname' => $cities[strtolower($patient['city'])]
);
$tmpl = $year == '1945' ? 'location_1945.htm' : 'location.htm';
$location_stub = file_get_contents('templates/'.$tmpl);
foreach ($mapping[strtolower($patient['city'])] as $item)   {
    $rus_loc = $locations[$item];
    $rus_loc = tools::cyr2utf(mb_strtolower(tools::utf2cyr($rus_loc)));
    if (!$item || mb_strpos($patient['locs_absent'], $rus_loc) !== false)
        $txt = "\n		<div class=location></div>\n";
    else    {
        $txt = $location_stub;
        foreach (array('location' => $item,
                       'location_name' => $locations[$item],
                        'trigger_word' => $trigger_pages[$patient['psychotype']][$item],
                        'trigger_page' => 'common/'.$trigger_pages['trigger_page'][$item],
                    ) as $k=>$v)
            $txt = str_replace('{'.$k.'}', $v, $txt);
    }
    $loc_text .= $txt;
}
$repl['locations'] = $loc_text;

foreach ($repl as $k=>$v)
    $template = str_replace('{'.$k.'}', $v, $template);
echo $template;


?>
