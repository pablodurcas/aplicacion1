<?php 

   $app_id = "544799425542627";
   $app_secret = "2a5858662268830ffe1f756d46c2d751";
   $my_url = "https://polar-thicket-8992.herokuapp.com/";

   session_start();


$code = $_REQUEST["code"];

  if(isset($_REQUEST["code"])) {
     $code = $_REQUEST["code"];
  }

  if(empty($code) && !isset($_REQUEST['error'])) {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $dialog_url = 'https://www.facebook.com/dialog/oauth?' 
      . 'client_id=' . $app_id
      . '&redirect_uri=' . urlencode($my_url)
      . '&state=' . $_SESSION['state']
      . '&scope=publish_actions';

    print('<script> top.location.href=\'' . $dialog_url . '\'</script>');
    exit;
  } else if(isset($_REQUEST['error'])) { 
    // The user did not authorize the app
    print($_REQUEST['error_description']);
    exit;
  };

   if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     // state variable matches
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



 ?>
