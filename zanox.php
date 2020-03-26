<?php 
$connectID = '34ABFCA4D62AB24CAA2D';  //Please fill in these two variables with the proper information 
$secretKey = 'd2b839A0515B4e+9badE8186c964a8/9cc08e64B'; // They can be found in the zanox Marketplace under "Links & Tools", "API"   
$http_verb = 'GET'; 
$date = date('Y-m-d');  
$fromdate = date('2015-01-01'); 
$todate = $date; 
$uri = '/reports/basic/fromdate/' . $fromdate. '/todate/' . $todate; 
$uri2 = '/reports/basic?fromdate='.$fromdate.'&todate=' . $todate; 
$uri3 = '/reports/sales/date/' . 
$date; 	 
$time_stamp = gmdate('D, d M Y H:i:s T', time()); 
$nonce = uniqid() . uniqid(); 
echo $nonce."<br>";
$string_to_sign = mb_convert_encoding($http_verb . $uri2 . $time_stamp . $nonce, 'UTF-8'); 
$signature = base64_encode(hash_hmac('sha1', $string_to_sign, $secretKey, true)); 
$requestURL = 'http://api.zanox.com/json/2011-03-01' . $uri2 . '&connectid=' . $connectID . '&date=' . $time_stamp . '&nonce=' . $nonce . '&signature=' . $signature; 
echo "Request: ". $requestURL . "<br>"; 
echo "<a href=\"" . $requestURL . "\">Link</a>"; ?>