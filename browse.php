<?php

$page = 'browse';

date_default_timezone_set('America/New_York');

if (isset($_GET['year'])) {
    $year = (int) $_GET['year'];
} else {
    $year = date('Y');
}

$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$current_year = date('Y');
$current_month = date('n');
$current_day = date('d');
$month = 0;

$db = new PDO('sqlite:db.sqlite');

require_once($_SERVER['DOCUMENT_ROOT'] . '/cliffs-tracker/includes/header.php');

?>

<nav aria-label="..." style="margin-top: 16px;">
  <ul class="pagination justify-content-center">
    <li class="page-item<?php if ($year <= 2020) { echo ' disabled'; } ?>"><a class="page-link" href="/cliffs-tracker/browse/<?php echo $year - 1; ?>">&laquo;</a></li>
<?php for ($i = '2020'; $i <= date('Y'); ++$i) { ?>
    <li class="page-item<?php if ($i == $year) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/browse/<?php echo $i; ?>"><?php echo $i; ?></a></li>
<?php } ?>
    <li class="page-item<?php if ($year >= date('Y')) { echo ' disabled'; } ?>"><a class="page-link" href="/cliffs-tracker/browse/<?php echo $year + 1; ?>">&raquo;</a></li>
  </ul>
</nav>

<div class="container-fluid" style="text-align: center;">

<?php for ($row = 1; $row <= 3; $row++) { ?>
  <div class="row">
<?php for ($column = 1; $column <= 4; $column++) { ?>
    <div class="col-lg" style="margin-bottom: 16px; padding-left: 12px; padding-right: 12px;">
      <div class="card" style="height: 100%;">
<?php

    $month++;

    $first_day_in_month = date('w', mktime(0, 0, 0, $month, 1, $year));
    $month_days = date('t', mktime(0, 0, 0, $month, 1, $year));

    ?>
          <div class="row g-0 mt-1 mb-1">
            <div class="col" style="font-weight: bold;"><?php echo $months[$month - 1]; ?></div>
          </div>
          <div class="days row g-0 mb-1" style="font-weight: bold;">
            <div class="sun col">U</div>
            <div class="col">M</div>
            <div class="col">T</div>
            <div class="col">W</div>
            <div class="col">R</div>
            <div class="col">F</div>
            <div class="sat col">S</div>
          </div>
          <div class="row g-0 mb-1">
<?php
    for ($i = 1; $i <= $first_day_in_month; $i++) {?> <div class="col"> </div> <?php }

    for ($day = 1; $day <= $month_days; $day++) {
        $pos = ($day + $first_day_in_month) % 7;
        $class = (($day == $current_day) && ($month == $current_month) && ($year == $current_year)) ? 'today' : 'day';
        $class .= ($pos == 6) ? ' sat' : '';
        $class .= ($pos == 0) ? ' sun' : '';

        $day_start = mktime(0, 0, 0, $month, $day, $year);
        if ($day_start < mktime(0, 0, 0, 9, 7, 2020) || $day_start + 21600 > time()) {
            $valid = false;
        } else {
            $valid = true;
        }

        if ($valid) {?> <div class="col <?php echo $class; ?>"><a href="/cliffs-tracker/browse/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $day; ?>"><?php echo $day; ?></a></div> <?php } else {?> <div class="col <?php echo $class; ?>"><?php echo $day; ?></div> <?php }

        if ($pos == 0) {
            echo '</div><div class="row g-0 mb-1">';
        }

    }

    for ($last = date('w', mktime(0, 0, 0, $month, $month_days, $year)); $last < 6; ++$last) {
        echo '<div class="col"></div>';
    }
?>
          </div>

        </div>
      </div>
<?php } ?>
  </div>
<?php } ?>

  </div>

</body>
</html>
