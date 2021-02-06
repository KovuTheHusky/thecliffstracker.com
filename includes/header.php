<!doctype html>
<html lang="en" style="height: 100%; margin: 0; padding: 0;">
<head>
  <meta charset="utf-8">
  <title>Cliffs Tracker</title>
  
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
          <img src="/cliffs-tracker/assets/logo.svg" alt="Cliffs-Tracker" style="width: 32px; height: 32px;">
          <!-- <img src="/cliffs-tracker/node_modules/bootstrap-icons/icons/graph-up.svg" alt="Tracker" style="width: 32px; height: 32px;"> -->
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
              Average
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
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">About</a>
          </li>
        </ul>
      </div>
      <div>
        <div class="input-group flex-nowrap">
          
          <input type="date" class="form-control" aria-label="Date" aria-describedby="date-label" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="2020-09-07" max="<?php echo date('Y-m-d'); ?>">
          <button class="btn btn-primary" id="date-label">
            <img src="/cliffs-tracker/node_modules/bootstrap-icons/icons/arrow-return-right.svg" style="filter: invert(1);">
          </button>
        </div>
        
      </div>
    </div>
  </nav>
