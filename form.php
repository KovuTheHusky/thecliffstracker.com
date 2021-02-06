<?php

date_default_timezone_set('America/New_York');

$date = explode('-', $_POST['date']);

for ($i = 0; $i < count($date); ++$i) {
    $date[$i] = (int) $date[$i];
}

$date = implode('/', $date);

exit(header('Location: /cliffs-tracker/browse/' . $date));
