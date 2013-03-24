<?php

$graph_url = "https://graph.facebook.com/100000859551472?access_token=" 
       . 'AAAHvfgA7ueMBABqhp67ECryTZAMhebAjsZAGw05i3u2hfqjTBmkQPWstzdhwqehmQ0EZCXLOFiFM23BdqNDnRZBqZBvHmThBTbu5xaBKWyAZDZD';

     $data = json_decode(file_get_contents($graph_url));
     print_r ($data);


?>
