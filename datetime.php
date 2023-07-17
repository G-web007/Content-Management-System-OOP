<?php

date_default_timezone_set("Asia/Karachi");
$currentTime = time();
$dateTime = strftime("%B-%d-%Y %h:%M:%S", $currentTime);
echo $dateTime;

?> 