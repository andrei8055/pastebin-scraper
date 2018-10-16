<?php

include("config.php");

//connect to db
$dsn = "mysql:dbname={$DB_NAME};host={$HOST}";
$user = $USERNAME;
$password = $PASSWORD;
$dbh = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    echo 'Connection okay';
} catch (PDOException $e) {
    echo 'Make sure USERNAME and PASSWORD are correct set in the config.php!';
    echo 'Connection failed: ' . $e->getMessage();
    die();
}

while(1){

//select oldest paste ID that was not parsed yet
$id = '';
$stmt = $dbh->query("SELECT id FROM pastes WHERE posted = (SELECT max(posted) FROM pastes) and parsed = 0 limit 1 ");

while ($row = $stmt->fetch()) {
    $id = $row['id'];
    echo " Fetching id: {$id} ";
}

//read the paste content
$url = "https://pastebin.com/raw/{$id}";
$content = file_get_contents($url);


//update row content with actual data
$stmt =  $dbh->prepare("UPDATE pastes SET content = :content, parsed = 1  WHERE id=:id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':content', $content);

$stmt->execute();

//wait $PASTE_INTERVAL before next crawl to avoid IP ban
sleep($PASTE_INTERVAL);
}

?>
