<?php

function curl_get_contents($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}



$url = 'https://api.delivery.com/api/merchant/search/delivery?address=23330+Calvert+St,+91367&client_id=MDlkMzY3Nzg3MjU1ZjRkNmY4OWZjNDA0NjBjMTI0MWZl';
$json = json_decode(curl_get_contents($url));
$json = objectToArray($json);

$merchants = $json['merchants'];
/*$t1 =  $_POST['t1'];
$t2 =  $_POST['t2'];*/
//header('Location: http://yoursite.com/page2.php?t1='.$t1.'&t2='.$t2);
//$data = json_decode(file_get_contents('https:\/\/api.delivery.com\/api\/merchant\/search\/delivery?address=23330+Calvert+St,+91367&client_id=MDlkMzY3Nzg3MjU1ZjRkNmY4OWZjNDA0NjBjMTI0MWZl'));
?>