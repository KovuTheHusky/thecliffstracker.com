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
    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
    <li class="page-item"><a class="page-link" href="#">2020</a></li>
    <li class="page-item active"><a class="page-link" href="#">2021</a></li>
    <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
  </ul>
</nav>


  <table class="calendar">
    <th colspan="4" class="year">
<?php if ($year > 2020) { ?>
      <a href="/cliffs-tracker/browse/<?php echo $year - 1; ?>">&lt;</a>
<?php } ?>
      <?php echo $year; ?>
<?php if ($year < $current_year) { ?>
      <a href="/cliffs-tracker/browse/<?php echo $year + 1; ?>">&gt;</a>
<?php } ?>
    </th>
<?php for ($row = 1; $row <= 3; $row++) { ?>
    <tr>
<?php for ($column = 1; $column <= 4; $column++) { ?>
      <td class="month">
<?php

    $month++;

    $first_day_in_month = date('w', mktime(0, 0, 0, $month, 1, $year));
    $month_days = date('t', mktime(0, 0, 0, $month, 1, $year));

    ?>
        <table>
          <th colspan="7"><?php echo $months[$month - 1]; ?></th>
          <tr class="days">
            <td class="sun">Su</td>
            <td>Mo</td>
            <td>Tu</td>
            <td>We</td>
            <td>Th</td>
            <td>Fr</td>
            <td class="sat">Sa</td>
          </tr>
          <tr>
<?php
    for ($i = 1; $i <= $first_day_in_month; $i++) {?> <td> </td> <?php }

    for ($day = 1; $day <= $month_days; $day++) {
        $pos = ($day + $first_day_in_month) % 7;
        $class = (($day == $current_day) && ($month == $current_month) && ($year == $current_year)) ? 'today' : 'day';
        $class .= ($pos == 6) ? ' sat' : '';
        $class .= ($pos == 0) ? ' sun' : '';

        $day_start = mktime(0, 0, 0, $month, $day, $year);
        if ($day_start < mktime(0, 0, 0, 9, 7, 2020) || $day_start > time()) {
            $valid = false;
        } else {
            // $day_end = mktime(24, 0, 0, $month, $day, $year);
            // $valid = $db->query("SELECT COUNT(*) FROM data WHERE time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll()[0][0];
            $valid = true;
        }

        if ($valid) {?> <td class="<?php echo $class; ?>"><a href="/cliffs-tracker/browse/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $day; ?>"><?php echo $day; ?></a></td> <?php } else {?> <td class="<?php echo $class; ?>"><?php echo $day; ?></td> <?php }

        if ($pos == 0) {
            echo '</tr><tr>';
        }

    }
?>
          </tr>
        </table>
      </td>
<?php } ?>
    </tr>
<?php } ?>
  </table>
</body>
</html>
