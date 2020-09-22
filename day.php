<?php

date_default_timezone_set('America/New_York');

if (isset($_GET['year']) && isset($_GET['month'])  && isset($_GET['day'])) {
    $year = (int) $_GET['year'];
    $month = (int) $_GET['month'];
    $day = (int) $_GET['day'];
    $day_start = mktime(6, 0, 0, $month, $day, $year);
    $day_end = mktime(24, 0, 0, $month, $day, $year);
} else {
    $day_start = mktime(6, 0, 0);
    $day_end = mktime(24, 0, 0);
}

if ($day_start == mktime(6, 0, 0)) {
    $today = true;
} else {
    $today = false;
}

$db = new PDO('sqlite:db.sqlite');

if (isset($_GET['location'])) {
  $location = strtoupper($_GET['location']);
}

if (isset($location) && ($location == 'DUM' || $location == 'LIC' || $location == 'VAL' || $location == 'CAL')) {
    $rows = $db->query("SELECT * FROM data WHERE location = '{$location}' AND time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll();
} else {
    $rows = $db->query("SELECT * FROM data WHERE time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll();
}

$capacity = 0;
foreach($rows as $row) {
    $dt = date('r', $row['time']);
    $data[$row['location']]['data'][] = "{x: new Date('{$dt}'), y: {$row['count']}}";
    $data[$row['location']]['labels'][] = "'{$dt}'";
    if ($row['capacity'] > $capacity) {
        $capacity = $row['capacity'];
    }
}

foreach ($data as $location => $locationData) {
    $data[$location]['data'] =  implode(',', $data[$location]['data']);
    $data[$location]['labels'] =  implode(',', $data[$location]['labels']);
}

?>
<!doctype html>
<html lang="en" style="height: 100%;">
<head>
  <meta charset="utf-8">
  <title>Cliffs Tracker</title>
</head>
<body style="height: 100%; margin: 0;">
  <canvas id="chart" style="width: 100%; height: 100%;"></canvas>
  <script src="node_modules/moment/min/moment.min.js"></script>
  <script src="node_modules/chart.js/dist/Chart.min.js"></script>
  <script src="node_modules/chartjs-plugin-annotation/chartjs-plugin-annotation.min.js"></script>
  <script>
      var ctx = document.getElementById('chart');
      var myLineChart = new Chart(ctx, {
          type: 'line',
          labels: [
<?php for ($i = 0; $i <= 14; ++$i) { ?>
              new Date('<?php echo date('r', $day_start + $i * 3600); ?>'),
<?php } ?>
          ],
          data: {
              datasets: [
<?php if (isset($data['DUM']['data'])) { ?>
                  {
                      label: 'DUMBO',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['DUM']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(255, 0, 0)',
                      backgroundColor: 'rgba(255, 0, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['LIC']['data'])) { ?>
                  {
                      label: 'LIC',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['LIC']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(0, 255, 0)',
                      backgroundColor: 'rgba(0, 255, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['VAL']['data'])) { ?>
                  {
                      label: 'Valhalla',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['VAL']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(255, 165, 0)',
                      backgroundColor: 'rgba(255, 165, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['CAL']['data'])) { ?>
                  {
                      label: 'Callowhill',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['CAL']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(0, 0, 255)',
                      backgroundColor: 'rgba(0, 0, 255, 0.1)'
                  },
<?php } ?>
              ]
          },
          options: {
<?php if ($today) { ?>
              annotation: {
                  annotations: [
                      {
                          id: 'vline',
                          type: 'line',
                          mode: 'vertical',
                          scaleID: 'x-axis-0',
                          value: new Date(),
                          borderColor: 'black',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'black',
                              content: 'Now',
                              enabled: true
                          }
                      }
                  ]
              },
<?php } ?>
              elements: {
                  point: {
                      radius: 0,
                      hitRadius: 10
                  }
              },
              scales: {
                  xAxes: [{
                      type: 'time',
                      time: {
                          unit: 'hour'
                      }
                  }],
                  yAxes: [{
                      ticks: {
                        suggestedMin: 0,
                        suggestedMax: <?php echo $capacity; ?>
                      }
                  }]
              }
          }
      });
  </script>
</body>
</html>
