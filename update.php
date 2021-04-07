<?php

if (php_sapi_name() != 'cli') {
    exit;
}

date_default_timezone_set('America/New_York');

//$db = new PDO('sqlite:db.sqlite');
$db = new PDO('sqlite:/var/www/thecliffstracker.com/db.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS data (
    location TEXT, 
    time INTEGER,
    count INTEGER,
    capacity INTEGER,
    UNIQUE(location, time)
)');

while (true) {
    if (date('G') < 6) {
        sleep(30);
        continue;
    }

    $month = date('n');

    $src = file_get_contents('https://portal.rockgympro.com/portal/public/a74dd6bfe28553eba4fca4ab9510e42f/occupancy?&iframeid=occupancyCounter&fId=1214');

    preg_match('/var\sdata\s=\s([^;]+);/m', $src, $matches);

    $data = $matches[1];

    $data = str_replace(array("\n", "\r", "\t"), '', $data);
    $data = str_replace(array('\''), '"', $data);
    $data = str_replace(array(': "'), ':"', $data);
    $data = str_replace(array(',    }'), '}', $data);

    $data = json_decode($data);

    foreach ($data as $key => $val) {
        if ($key == 'DUM' && ($month == 12 || $month == 1 || $month == 2 || $month == 3)) { // fast skip winter months for dumbo
            continue;
        }
        preg_match('/\((.*)\)/', $val->lastUpdate, $time);
        $time = strtotime($time[1]);
        if ($key == 'DUM') { // slow skip partial months for dumbo
            $closing = mktime(0, 0, 0, 11, 2, 2020);
            $opening = mktime(0, 0, 0, 4, 16, 2021);
            if ($time > $closing && $time < $opening) {
                continue;
            }
        }
        if ($time > time() + 60) {
            continue; // we're at least a minute in the future, forget it
        }
        $arr = array($key, $time, $val->count, $val->capacity);
        
        $stmt = $db->prepare('INSERT INTO data (location, time, count, capacity) VALUES (?, ?, ?, ?)');
        $stmt->execute($arr);
    }

    sleep(30);
}
