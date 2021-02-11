<?php

switch ($page) {
    case 'today':
        $title = 'Today | ';
        break;
    case 'browse':
        if ($day_page) {
            $title = substr(str_replace('Thu', 'R', str_replace('Sun', 'U', date('D', $day_start))), 0, 1) . ' ' . date('n/j', $day_start) . ' | ' . $year . ' | Browse | ';
        } else {
            $title = $year . ' | Browse | ';
        }
        break;
    case 'average':
        switch ($weekday) {
            case 0:
                $wt = 'Sunday';
                break;
            case 1:
                $wt = 'Monday';
                break;
            case 2:
                $wt = 'Tuesday';
                break;
            case 3:
                $wt = 'Wednesday';
                break;
            case 4:
                $wt = 'Thursday';
                break;
            case 5:
                $wt = 'Friday';
                break;
            case 6:
                $wt = 'Saturday';
                break;
            default:
                $wt = 'Unknown';
                break;
        }
        $title = $wt . ' | Averages | ';
        break;
    case 'about':
      $title = 'About | ';
        break;
    default:
        $title = '';
        break;
}

?>
<!doctype html>
<html lang="en" style="height: 100%; margin: 0; padding: 0;">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $title; ?>Cliffs Tracker</title>
  <link rel="stylesheet" href="/cliffs-tracker/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/cliffs-tracker/assets/main.css">
  <script src="/cliffs-tracker/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="/cliffs-tracker/node_modules/@popperjs/core/dist/umd/popper.min.js" defer></script>
  <script src="/cliffs-tracker/node_modules/bootstrap/dist/js/bootstrap.min.js" defer></script>
</head>
<body style="height: 100%; margin: 0; padding: 0;">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <div>
        <a class="navbar-brand p-0" href="/cliffs-tracker">
          <img src="/cliffs-tracker/assets/logo.svg" alt="Cliffs-Tracker" title="Cliffs-Tracker" style="width: 32px; height: 32px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link<?php if ($page == 'today') { echo ' active'; } ?>" aria-current="page" href="/cliffs-tracker">Today</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?php if ($page == 'browse') { echo ' active'; } ?>" aria-current="page" href="/cliffs-tracker/browse">Browse</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link<?php if ($page == 'average') { echo ' active'; } ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Averages
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 0) { echo ' active'; } ?>" href="/cliffs-tracker/average/0">Sunday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 1) { echo ' active'; } ?>" href="/cliffs-tracker/average/1">Monday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 2) { echo ' active'; } ?>" href="/cliffs-tracker/average/2">Tuesday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 3) { echo ' active'; } ?>" href="/cliffs-tracker/average/3">Wednesday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 4) { echo ' active'; } ?>" href="/cliffs-tracker/average/4">Thursday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 5) { echo ' active'; } ?>" href="/cliffs-tracker/average/5">Friday</a></li>
              <li><a class="dropdown-item<?php if ($page == 'average' && $weekday == 6) { echo ' active'; } ?>" href="/cliffs-tracker/average/6">Saturday</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link<?php if ($page == 'about') { echo ' active'; } ?>" href="/cliffs-tracker/about">About</a>
          </li>
        </ul>
      </div>
      <div>
        <form method="post" action="/cliffs-tracker/form">
          <div class="input-group flex-nowrap">
<?php

if ($day_page) {
    $input = date('Y-m-d', $day_start);
} else {
    if (date('G') < 6) {
        $input = date('Y-m-d', mktime(6, 0, 0) - 86400);
    } else {
        $input = date('Y-m-d');
    }
}

if (date('G') < 6) {
    $max = date('Y-m-d', mktime(6, 0, 0) - 86400);
} else {
    $max = date('Y-m-d');
}

?>
            <input type="date" class="form-control" aria-label="Date" aria-describedby="date-label" id="date" name="date" value="<?php echo $input; ?>" min="2020-09-07" max="<?php echo $max; ?>">
            <button class="btn btn-primary" id="date-label">
              <img src="/cliffs-tracker/node_modules/bootstrap-icons/icons/arrow-return-right.svg" style="filter: invert(1);">
            </button>
          </div>
        </form>
      </div>
    </div>
  </nav>
