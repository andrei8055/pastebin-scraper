<?php

include("config.php");
$iteration = 1;

//connect to db
$dsn = "mysql:dbname={$DB_NAME};host={$HOST}";
$user = $USERNAME;
$password = $PASSWORD;
$dbh = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    echo 'Connection okay!';
} catch (PDOException $e) {
    echo 'Make sure USERNAME and PASSWORD are correct set in the config.php!';
    echo 'Connection failed: ' . $e->getMessage();
    die();
}

//run script every $ARCHIVE_INTERVAL seconds, and collect latest pastes added
while(1) {

echo " Start iteration {$iteration} :";
$iteration++;

$url = "https://pastebin.com/archive";
$html = file_get_contents($url);

//some regex to get the pastes unique IDs
preg_match_all('~<a href="\/[a-zA-Z0-9]{8}"~', $html, $matches);
$ids=$matches[0];

//hack to avoid fetching twice some of the pastes
echo ' Fetched ' . (count($ids) - 11) . ' pastes: ';

//insert each of the paste in DB as not parsed (default 0)
for($i=11;$i<count($ids);$i++) {
$id = substr(substr($ids[$i], -9), 0, 8);

//add dummy content - we fetch the content using parse-paste.php
$title = 'N/A';
$syntax = 'N/A';
$content = "N/A";

$stmt = $dbh->prepare("INSERT INTO pastes (id, title, posted, syntax, content) VALUES (:id, :title, now(), :syntax, :content)");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':syntax', $syntax);
$stmt->bindParam(':content', $content);

$stmt->execute();

echo "{$id},";

}

echo " Pause {$ARCHIVE_INTERVAL} sec. ";
sleep($ARCHIVE_INTERVAL);
}

?>
