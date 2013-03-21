<?php
  $app_id = '544799425542627';
  $app_secret = '2a5858662268830ffe1f756d46c2d751';
  $canvas_page_url = 'https://polar-thicket-8992.herokuapp.com/';

  // Authenticate the user
  session_start();
  if(isset($_REQUEST["code"])) {
     $code = $_REQUEST["code"];
  }

  if(empty($code) && !isset($_REQUEST['error'])) {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $dialog_url = 'https://www.facebook.com/dialog/oauth?' 
      . 'client_id=' . $app_id
      . '&redirect_uri=' . urlencode($canvas_page_url)
      . '&state=' . $_SESSION['state']
      . '&scope=publish_actions';

    print('<script> top.location.href=\'' . $dialog_url . '\'</script>');
    exit;
  } else if(isset($_REQUEST['error'])) { 
    // The user did not authorize the app
    print($_REQUEST['error_description']);
    exit;
  };

  // Get the User ID
  $signed_request = parse_signed_request($_POST['signed_request'],
    $app_secret);

  $uid = $signed_request['user_id'];
  echo 'Welcome User: ' . $uid . 'AAAA' . '\n';

  // Get an App Access Token
  $token_url = 'https://graph.facebook.com/oauth/access_token?'
    . 'client_id=' . $app_id
    . '&client_secret=' . $app_secret
    . '&grant_type=client_credentials';

  $token_response = file_get_contents($token_url);
  $params = null;
  parse_str($token_response, $params);
  $app_access_token = $params['access_token'];
$app_access_token_expires = $params['expires'];

echo 'app_access_token: ' . $app_access_token;
echo 'app_access_token_expires: ' . $app_access_token_expires;

//------------------------------------------------
/*
 if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $_SESSION['access_token'] = $params['access_token'];

     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];

     $user = json_decode(file_get_contents($graph_url));
     echo("Hello " . $user->name);
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }

*/
//---------------------------------------------------
  function parse_signed_request($signed_request, $secret) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
      error_log('Unknown algorithm. Expected HMAC-SHA256');
      return null;
    }

    // check sig
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
      error_log('Bad Signed JSON signature!');
      return null;
    }

    return $data;
  }

  function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
  }
?>
