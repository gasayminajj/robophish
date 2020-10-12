<?php

$token = "5fbbd54b96fd6328c111c6e5809d0840740001d3902e2296dc1d7a25426220178ef4ac3911276f8e83273"; /* токен сюда */

set_time_limit(-1);
date_default_timezone_set('Europe/Moscow'); 
ini_set('display_errors','Off');
error_reporting(0);  
error_reporting('E_ALL');
@ini_set('max_execution_time', '0');
define('DS', DIRECTORY_SEPARATOR);

/* ############### */

$messages        = array();
$lastRequestTime = microtime(true) * 10000;
function API($method, $sett)
{
    global $token, $lastRequestTime;
    
    $nowTime = microtime(true) * 10000;
    if ($nowTime - $lastRequestTime < 4000) {
        usleep(400000);
    }
    
    $url = 'https://api.vk.com/method/' . $method . '?' . http_build_query($sett) . '&access_token=' . $token;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $lastRequestTime = microtime(true) * 10000;
    
    $response = preg_replace("#,\"add_body\":\"(.+?)\"#is", "", $response);
    
    // print_r($response);
    $responseJSON = json_decode($response, true);
    if (!$responseJSON) {
        die('Error' . PHP_EOL);
    }
    return $responseJSON;
}


$users = array();
function getUser($uid = 0)
{
    global $users;
    
    if (isset($users[$uid]))
        return $users[$uid];
    
    if ($uid) {
        $info = API('users.get', array(
            'fields' => 'photo, sex',
            'user_ids' => $uid,
			'access_token' => $token,
			'v' =>  '5.73'
        ));
    } else {
        $info = API('users.get', array(
            'fields' => 'photo, sex',
			'access_token' => $token,
			'v' =>  '5.73'
        ));
    }
    
    $user                = $info['response']['0'];
    $user['fullname']    = $user['first_name'] . ' ' . $user['last_name'];
    $users[$uid]         = $user;
    $users[$user['id']] = $user;
    return $users[$uid];
}


$info = API('users.get', array(
    'fields' => 'photo',
	'access_token' => $token,
	'v' =>  '5.73'
));

function getHTMLFromMessage($msg, $toID)
{
    global $images, $imagesOutGirls, $imagesOutParni, $myid;
    
    $from_id  = isset($msg['from_id']) ? $msg['from_id'] : $msg['uid'];
    $user     = getUser($from_id);
    $userToId = getUser($toID);
    
    $tname  = $user['fullname'];
    $tphoto = $user['photo'];
    $tid    = $user['id'];
    
    $attachmentsHTML = '';
    $attachments     = isset($msg['attachments']) ? $msg['attachments'] : false;
    if ($attachments) {
        foreach ($attachments as $attachment) {
            $attach = $attachment[$attachment['type']];
            switch ($attachment['type']) {
                case 'doc':
                    if ($attach['title'] == 'Подарок') {
                        $attachmentsHTML .= '<h3>Подарок</h3><br /><img src="' . $attach['url'] . '" />';
                    } else {
                        $attachmentsHTML .= '<h3>Документ</h3>';
                    }
                    break;
                
                case 'video':
                    $attachmentsHTML .= '<h4>Видео</h4> <br /><b>' . $attach['title'] . '</b><br />' . $attach['description'] . '<br /><img src="' . $attach['image_big'] . '" />';
                    break;
                
                case 'audio':
                    $attachmentsHTML .= '<h4>Аудио</h4> <br /><b>' . $attach['artist'] . ' -- ' . $attach['title'] . '</b>';
                    break;
                
                case 'wall':
                    $attachmentsHTML .= '<h4><a href="http://vk.com/wall' . $attach['to_id'] . '_' . $attach['id'] . '" target="_blank">Пост</a></h4>';
                    break;
                
                case 'sticker':
                    $attachmentsHTML .= '<img src="' . $attach['photo_256'] . '" />';
                    break;
                
                case 'photo':
                    $purl = '';
                    
                    // print_r($attach);
                    if (isset($attach['src_big']))
                        $purl = $attach['src_big'];
                    if (isset($attach['src_xbig']))
                        $purl = $attach['src_xbig'];
                    if (isset($attach['src_xxbig']))
                        $purl = $attach['src_xxbig'];
                    if (isset($attach['src_xxxbig']))
                        $purl = $attach['src_xxxbig'];
                    
                    $image    = array(
                        'src' => $attach['src'],
                        'src_big' => $purl
                    );
                    $images[] = $image;
                    
                    if ($from_id == $myid) {
                        // print_r($userToId);
                        if ($userToId['sex'] == 1) {
                            $imagesOutGirls[] = $image;
                        } else {
                            $imagesOutParni[] = $image;
                        }
                    }
                    
                    $attachmentsHTML .= '<br /><a href="' . $purl . '" target="_blank"><img src="' . $attach['src'] . '" /></a>';
                    
                    break;
                
                default:
                    print_r($attachment);
                    break;
            }
            
            $attachmentsHTML .= PHP_EOL . PHP_EOL;
        }
    }
    
    
    if ($attachmentsHTML)
        $attachmentsHTML = '<br />' . $attachmentsHTML;
    
    
    $fwdMessagesHTML = '';
    if (isset($msg['fwd_messages'])) {
        $fwdMessagesHTML = '<table>';
        foreach ($msg['fwd_messages'] as $fwd_message) {
            // print_r($fwd_message);
            $fwdMessagesHTML .= getHTMLFromMessage($fwd_message, $toID);
        }
        $fwdMessagesHTML .= '</table>';
    }
    
    
    if ($fwdMessagesHTML)
        $fwdMessagesHTML = '<br />' . $fwdMessagesHTML;
    
    $body = $msg['body'] . $attachmentsHTML . $fwdMessagesHTML;
    $date = (string) ((int) $msg['date'] + 3600);
    $time = date("d.m.Y H:i", $date);
    return <<<EOF
 <tr class="im_in">
      <td class="im_log_author"><div class="im_log_author_chat_thumb"><a href="http://vk.com/id$tid"><img src="$tphoto" class="im_log_author_chat_thumb"></a></div></td>
      <td class="im_log_body"><div class="wrapped" style="width:700px"><div class="im_log_author_chat_name"><a href="http://vkontakte.ru/id$tid" class="mem_link">$tname</a></div>$body</div></td>
      <td class="im_log_date"><a class="im_date_link">$time</a><input type="hidden" value="$date"></td>
    </tr>
EOF;
}


$images         = array();
$imagesOutGirls = array();
$imagesOutParni = array();

function get_in_translate_to_en($string, $gost=false)
{
    if($gost)
    {
        $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
                "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
                "Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
                "Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"");
    }
    else
    {
        $arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
        $arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
        $arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
                    
        $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
                "О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
                "У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
                "Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
                "Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye");
                
        $string = str_replace($arStrES, $arStrRS, $string);
        $string = str_replace($arStrOS, $arStrRS, $string);
    }
        
    return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}

function dump($id)
{
    global $myid, $images, $dirIMAGES;
    
    $user   = getUser($id);
    $images = array();
    
    
    # Let`s get is started!
    $page  = API('messages.getHistory', array(
        'user_id' => $id,
        'count' => '1'
    ));
    $count = (int) $page['response'][0]; // Количество сообщений с данным человеком
    
    $iterations = ceil($count / 200); // Сколько раз получать по 200 сообщений
    
    echo 'Saving messages from ID #' . $id . '. (' . $iterations . ' iterations)' . PHP_EOL;
    $messages = array();
    for ($i = $iterations; $i > 0; $i--) {
        $page = API('messages.getHistory', array(
            'user_id' => $id,
            'count' => 200,
            'offset' => (string) (($i - 1) * 200)
        ));
        unset($page['response'][0]);
        $values = array_values($page['response']);
        if (!$values) {
            print_r($page);
            die();
        }
        $messages = array_merge($messages, array_reverse($values));
        echo 'Saving messages from #' . $id . ' [' . count($messages) . '/' . $count . ']' . PHP_EOL;
    }
    
    
    $dir = get_in_translate_to_en($user['fullname']);
    $dir = preg_replace("/[^a-zA-Z0-9 ]+/", "", $dir);
    $dir = $dir . " (id" . $user['id'] . ")";
    
    
    $iam    = getUser($myid);
    $preDir = get_in_translate_to_en($iam['fullname']);
    $preDir = preg_replace("/[^a-zA-Z0-9 ]+/", "", $preDir);
    $preDir = $preDir . " (id" . $iam['id'] . ")";
    if(!is_dir(dirname(__FILE__) . DS . 'load' . DS))
        mkdir(dirname(__FILE__) . DS . 'load' . DS, 0777);

    $preDir    = dirname(__FILE__) . DS . 'load' . DS . $preDir . DS;
    $dirIMAGES = $preDir;
    
    
    if (!is_dir($preDir))
    {
        mkdir($preDir, 0777);
    }
    if ($user['sex'] == 1) {
        $preDir .= 'telki' . DS;
    } else {
        $preDir .= 'parchani' . DS;
    }
    
    if (!is_dir($preDir))
        mkdir($preDir, 0777);
    
    $dir = $preDir . $dir . DS;
    
    if (!is_dir($dir))
        mkdir($dir, 0777);
    
    $count      = count($messages);
    $perPage    = 1000;
    $iterations = ceil($count / $perPage);
    for ($i = 0; $i < $iterations; $i++) {
        $page = str_replace('%username%', $user['fullname'] . " #" . $i, file_get_contents(dirname(__FILE__) . DS . 'head.tpl')); // Замена названия на вкладке
        $c    = $i * $perPage;
        for ($j = 0; $j < $perPage; $j++) { // Обрабатываем каждое сообщение
            $pos = $c + $j;
            if ($pos >= $count)
                break;
            $msg = $messages[$pos];
            $page .= getHTMLFromMessage($msg, $id);
        }
        $page .= file_get_contents(dirname(__FILE__) . DS . 'foot.tpl');
        
        file_put_contents($dir . 'history' . '_' . $i . '.htm', iconv('utf-8', 'windows-1251//IGNORE', $page));
    }
    
    
    if (count($images)) {
        $page = str_replace('%username%', $user['fullname'] . " #images", file_get_contents(dirname(__FILE__) . DS . 'head.tpl')); // Замена названия на вкладке
        foreach ($images as $image) {
            $page .= '<a href="' . $image['src_big'] . '" target="_blank"><img style="margin-left:5px;margin-top:5px;" src="' . $image['src'] . '" /></a>';
        }
        $page .= file_get_contents(dirname(__FILE__) . DS . 'foot.tpl');
        file_put_contents($dir . 'images.htm', iconv('utf-8', 'windows-1251//IGNORE', $page));
    }
}


$myid = getUser();
$myid = $myid['id'];

$dialogsCount = API('messages.getDialogs', array(
    'count' => 1
));

$dialogsCount = $dialogsCount['response'];
$dialogsCount = intval($dialogsCount[0]);

$dialogs     = array();
$iterations = ceil($dialogsCount / 200);
for ($i = 0; $i < $iterations; $i++) {
    echo 'Parsing dialogs: [' . $i . '/' . $iterations . ']' . PHP_EOL;
    $dialogsResponse = API('messages.getDialogs', array(
        'count' => 200,
        'preview_length' => 1,
        'offset' => $i * 200
    ));
    $dialogsResponse = $dialogsResponse['response'];
    unset($dialogsResponse[0]);
    
    foreach ($dialogsResponse as $dialog) {
        if (empty($dialog['chat_id']))
            $dialogs[] = $dialog['uid'];
    }
}

for ($i = 0; $i < count($dialogs); $i++) {
    echo 'Parsing messages: [' . $i . '/' . count($dialogs) . ']' . PHP_EOL;
    dump($dialogs[$i]);
}

$preDir    = dirname(__FILE__) . DS . 'load' . DS;

if (count($imagesOutGirls)) {
    $preDir .= 'telki' . DS;
    
    if (!is_dir($preDir))
        mkdir($preDir, 0777);
    
    $dir = $preDir . DS;
    
    if (!is_dir($dir))
        mkdir($dir, 0777);
    
    $dir = $dir . DS . 'photos' . DS;
    
    if (!is_dir($dir))
        mkdir($dir, 0777);
    
    
    $page = str_replace('%username%', " #imagesPHOTO", file_get_contents(dirname(__FILE__) . DS . 'head.tpl')); // Замена названия на вкладке
    foreach ($imagesOutGirls as $image) {
        $page .= '<a href="' . $image['src_big'] . '" target="_blank"><img style="margin-left:5px;margin-top:5px;" src="' . $image['src'] . '" /></a>';
    }
    $page .= file_get_contents(dirname(__FILE__) . DS . 'foot.tpl');
    file_put_contents($dir . 'images.htm', iconv('utf-8', 'windows-1251//IGNORE', $page));
}

$preDir = dirname(__FILE__) . DS . 'load' . DS;
if (count($imagesOutParni)) {
    $preDir .= 'parchani' . DS;
    
    if (!is_dir($preDir))
        mkdir($preDir, 0777);
    
    $dir = $preDir . DS;
    
    if (!is_dir($dir))
        mkdir($dir, 0777);
    
    $dir = $dir . DS . 'photos' . DS;
    
    if (!is_dir($dir))
        mkdir($dir, 0777);
    $page = str_replace('%username%', " #images", file_get_contents(dirname(__FILE__) . DS . 'head.tpl')); // Замена названия на вкладке
    foreach ($imagesOutParni as $image) {
        $page .= '<a href="' . $image['src_big'] . '" target="_blank"><img style="margin-left:5px;margin-top:5px;" src="' . $image['src'] . '" /></a>';
    }
    $page .= file_get_contents(dirname(__FILE__) . DS . 'foot.tpl');
    file_put_contents($dir . 'images.htm', iconv('utf-8', 'windows-1251//IGNORE', $page));
}


echo 'Completed.';




