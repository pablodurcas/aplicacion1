<?php

$token = 'AAAHvfgA7ueMBABqhp67ECryTZAMhebAjsZAGw05i3u2hfqjTBmkQPWstzdhwqehmQ0EZCXLOFiFM23BdqNDnRZBqZBvHmThBTbu5xaBKWyAZDZD'
$msg = 'prueba de mensage'
$title = 'prueba de titulo'
$uri= 'www.buscandopadel.com'
$desc = 'prueba de descripción'
$pic = 'http://www.buscandopadel.com/images/avatar/groups/d5736bc8ee930f4c430fc8c0.jpg'


$attachment =  array(
'access_token' => $token,
'message' => $msg,
'name' => $title,
'link' => $uri,
'description' => $desc,
'picture'=>$pic,
'actions' => json_encode(array('name' => $action_name,'link' => $action_link))
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/me/feed');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
$result = curl_exec($ch);
curl_close ($ch);

print_r($result);

?>