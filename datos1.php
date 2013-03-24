<?php

$graph_url = "https://graph.facebook.com/me?access_token=" 
       . 'access_token';

     $data = json_decode(file_get_contents($graph_url));
     print_r ($data);


?>
