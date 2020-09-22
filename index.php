<?php

date_default_timezone_set('America/New_York');

if (isset($_GET['year'])) {
    $year = (int) $_GET['year'];
} else {
    $year = date('Y');
}

$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$current_month = date('n');
$current_day = date('d');
$month = 0;

$db = new PDO('sqlite:db.sqlite');

?>
<!doctype html>
<html lang="en" style="height: 100%;">
<head>
  <meta charset="utf-8">
  <title>Cliffs Tracker</title>
  <style>
  .calendar {
    width: 100%;
    height: 100%;
  }

  .calendar,
  .calendar table {
    border: 0;
    margin: 0;
  }

  .calendar,
  .calendar table,
  .calendar td {
    text-align: center;
  }

  .calendar .year {
    font-family: Verdana;
    font-size: 18pt;
  }

  .calendar .month {
    width: 25%;
    vertical-align: top;
  }

  .calendar .month table {
    font-size: 12pt;
    font-family: Verdana;
    margin: auto;
  }

  .calendar .month th {
    text-align: center;
    font-size: 12pt;
    font-family: Arial;
  }

  .calendar .month td {
    font-size: 12pt;
    font-family: Verdana;
  }

  .calendar .month .days td {
    font-weight: bold;
  }

  .calendar .month .today {
    background: #ff0000;
    color: #ffffff;
  }

  </style>
</head>
<body style="height: 100%; margin: 0;">
  <table class="calendar">
    <th colspan="4" class="year"><?php echo $year; ?></th>
<?php for ($row = 1; $row <= 3; $row++) { ?>
    <tr>
<?php for ($column = 1; $column <= 4; $column++) { ?>
      <td class="month">
<?php

    $month++;

    $first_day_in_month = date('w', mktime(0, 0, 0, $month, 1, $year));
    $month_days = date('t', mktime(0, 0, 0, $month, 1, $year));

    // in PHP, Sunday is the first day in the week with number zero (0)
    // to make our calendar works we will change this to (7)
    if ($first_day_in_month == 0) {
        $first_day_in_month = 7;
    }

    ?>
        <table>
          <th colspan="7"><?php echo $months[$month - 1]; ?></th>
          <tr class="days">
            <td>Mo</td>
            <td>Tu</td>
            <td>We</td>
            <td>Th</td>
            <td>Fr</td>
            <td class="sat">Sa</td>
            <td class="sun">Su</td>
          </tr>
          <tr>
<?php
    for ($i = 1; $i < $first_day_in_month; $i++) {?> <td> </td> <?php }

    for ($day = 1; $day <= $month_days; $day++) {
        $pos = ($day + $first_day_in_month - 1) % 7;
        $class = (($day == $current_day) && ($month == $current_month)) ? 'today' : 'day';
        $class .= ($pos == 6) ? ' sat' : '';
        $class .= ($pos == 0) ? ' sun' : '';

        $day_start = mktime(0, 0, 0, $month, $day, $year);
        $day_end = mktime(24, 0, 0, $month, $day, $year);
        $valid = $db->query("SELECT COUNT(*) FROM data WHERE time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll()[0][0];

        if ($valid) {?> <td class="<?php echo $class; ?>"><a href="day.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day; ?>"><?php echo $day; ?></a></td> <?php } else {?> <td class="<?php echo $class; ?>"><?php echo $day; ?></td> <?php }

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
