<?php

//db config
$DB_NAME = "pastebin";
$HOST = "127.0.0.1";
$USERNAME = "YOUR_USERNAME";
$PASSWORD = "YOUR_PASSWORD";


/*
curl the 'archive' page each 'x' seconds
recommended value 90
*/
$ARCHIVE_INTERVAL = 90; 

/*
curl a unique paste each 'x' seconds 
recommed value 3
may get ip banned if crawling faster
*/
$PASTE_INTERVAL = 3;

?>
