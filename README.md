# pastebin-scraper

## db set up

run init_db.sql
edit $USERNAME and $PASSWORD in config.php 

## run the scraper

```
cd pastebin-scraper
sudo php -f parse-archive.php & sudo php -f parse-paste.php
```

## check results

```
SELECT * FROM `pastes` WHERE parsed = 1 ORDER BY `posted` DESC 
```
