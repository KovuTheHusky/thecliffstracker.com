<?php

date_default_timezone_set('America/New_York');

$day_page = true;

if (isset($_GET['year']) && isset($_GET['month'])  && isset($_GET['day'])) {
    $year = (int) $_GET['year'];
    $month = (int) $_GET['month'];
    $day = (int) $_GET['day'];
    $day_start = mktime(6, 0, 0, $month, $day, $year);
    $day_end = mktime(24, 0, 0, $month, $day, $year);
} else {
    if (date('G') < 6) {
        $day_start = mktime(6, 0, 0) - 86400;
        $day_end = mktime(24, 0, 0) - 86400;
    } else {
        $day_start = mktime(6, 0, 0);
        $day_end = mktime(24, 0, 0);
    }
}

if ($day_start == mktime(6, 0, 0) || (date('G') < 6 && $day_start == mktime(6, 0, 0) - 86400)) {
    $today = true;
    $page = 'today';
} else {
    $today = false;
    $page = 'browse';
}

$yesterday = $day_start - 86400;
$tomorrow = $day_start + 86400;

$db = new PDO('sqlite:db.sqlite');

if (isset($_GET['location'])) {
  $location = strtoupper($_GET['location']);
}

if (isset($location) && ($location == 'DUM' || $location == 'LIC' || $location == 'VAL' || $location == 'CAL')) {
    $rows = $db->query("SELECT * FROM data WHERE location = '{$location}' AND count > 0 AND time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll();
} else {
    $rows = $db->query("SELECT * FROM data WHERE count > 0 AND time > {$day_start} AND time < {$day_end} ORDER BY time ASC")->fetchAll();
}

$capacity = 170;
$capacities = array(
    'DUM' => 60,
    'HLM' => 98,
    'LIC' => 139,
    'VAL' => 52,
    'CAL' => 170
);
foreach($rows as $row) {
    $dt = date('r', $row['time']);
    $data[$row['location']]['data'][] = "{x: new Date('{$dt}'), y: {$row['count']}}";
    $data[$row['location']]['labels'][] = "'{$dt}'";
    $capacities[$row['location']] = $row['capacity'];
    if ($row['capacity'] > $capacity) {
        $capacity = $row['capacity'];
    }
}

foreach ($data as $location => $locationData) {
    $data[$location]['data'] =  implode(',', $data[$location]['data']);
    $data[$location]['labels'] =  implode(',', $data[$location]['labels']);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');

?>

<nav aria-label="..." style="margin-top: 16px;">
  <ul class="pagination justify-content-center">
    <li class="page-item<?php if ($year == 2020 && $month == 9 && $day == 7) { echo ' disabled'; } ?>"><a class="page-link" href="/browse/<?php echo date('Y/n/j', $yesterday); ?>">&laquo;</a></li>
<?php for ($i = $day_start - 172800; $i < $day_start; $i += 86400) { ?>
    <li class="page-item<?php if ($i < mktime(6, 0, 0, 9, 7, 2020)) { echo ' disabled'; } ?>"><a class="page-link" href="/browse/<?php echo date('Y/n/j', $i); ?>"><?php echo substr(str_replace('Thu', 'R', str_replace('Sun', 'U', date('D', $i))), 0, 1) . ' ' . date('n/j', $i); ?></a></li>
<?php } ?>
    <li class="page-item active"><a class="page-link" href="/browse/<?php echo date('Y/n/j', $day_start); ?>"><?php echo substr(str_replace('Thu', 'R', str_replace('Sun', 'U', date('D', $day_start))), 0, 1) . ' ' . date('n/j', $day_start); ?></a></li>
<?php for ($i = $day_start + 86400; $i <= $day_start + 172800; $i += 86400) { ?>
    <li class="page-item<?php if ($i > time()) { echo ' disabled'; } ?>"><a class="page-link" href="/browse/<?php echo date('Y/n/j', $i); ?>"><?php echo substr(str_replace('Thu', 'R', str_replace('Sun', 'U', date('D', $i))), 0, 1) . ' ' . date('n/j', $i); ?></a></li>
<?php } ?>
    <li class="page-item<?php if ($today || $day_start + 86400 > time()) { echo ' disabled'; } ?>"><a class="page-link" href="/browse/<?php echo date('Y/n/j', $tomorrow); ?>">&raquo;</a></li>
  </ul>
</nav>

  <div style="width: 100%; height: calc(100% - 126px);">
    <canvas id="chart"></canvas>
  </div>
  <script src="/node_modules/chart.js/dist/chart.min.js"></script>
  <script src="/node_modules/moment/min/moment.min.js"></script>
  <script src="/node_modules/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js"></script>
  <script src="/node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js"></script>
  <script>
      var ctx = document.getElementById('chart');
      var myLineChart = new Chart(ctx, {
          type: 'line',
          labels: [
<?php for ($i = 0; $i <= 18; ++$i) { ?>
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
<?php if (isset($data['GOW']['data'])) { ?>
                  {
                      label: 'Gowanus',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['GOW']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(0, 0, 255)',
                      backgroundColor: 'rgba(0, 0, 255, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['HLM']['data'])) { ?>
                  {
                      label: 'Harlem',
                      data: [
                          {x: new Date('<?php echo date('r', $day_start); ?>'), y: null}, 
                          <?php echo $data['HLM']['data']; ?>,
                          {x: new Date('<?php echo date('r', $day_end); ?>'), y: null}
                        ],
                      borderColor: 'rgb(0, 255, 255)',
                      backgroundColor: 'rgba(0, 255, 255, 0.1)'
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
                      borderColor: 'rgb(255, 0, 255)',
                      backgroundColor: 'rgba(255, 0, 255, 0.1)'
                  },
<?php } ?>
              ]
          },
          options: {
              plugins: {
              annotation: {
                  annotations: [
<?php if (isset($data['DUM']['data'])) { ?>
                      {
                          id: 'dumbo',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['DUM']; ?>,
                          borderColor: 'rgba(255, 0, 0, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(255, 0, 0)',
                              content: 'DUMBO',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if (isset($data['GOW']['data'])) { ?>
                      {
                          id: 'gowanus',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['GOW']; ?>,
                          borderColor: 'rgba(0, 0, 255, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(0, 0, 255)',
                              content: 'Gowanus',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if (isset($data['HLM']['data'])) { ?>
                      {
                          id: 'harlem',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['HLM']; ?>,
                          borderColor: 'rgba(0, 255, 255, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(0, 255, 255)',
                              content: 'Harlem',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if (isset($data['LIC']['data'])) { ?>
                      {
                          id: 'lic',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['LIC']; ?>,
                          borderColor: 'rgba(0, 255, 0, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(0, 255, 0)',
                              content: 'LIC',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if (isset($data['VAL']['data'])) { ?>
                      {
                          id: 'valhalla',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['VAL']; ?>,
                          borderColor: 'rgba(255, 165, 0, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(255, 165, 0)',
                              content: 'Valhalla',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if (isset($data['CAL']['data'])) { ?>
                      {
                          id: 'callowhill',
                          type: 'line',
                          drawTime: 'beforeDatasetsDraw',
                          scaleID: 'y',
                          value: <?php echo $capacities['CAL']; ?>,
                          borderColor: 'rgba(255, 0, 255, 0.1)',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'rgb(255, 0, 255)',
                              content: 'Callowhill',
                              enabled: true
                          }
                      },
<?php } ?>
<?php if ($today && date('G') >= 6) { ?>
                      {
                          id: 'vline',
                          type: 'line',
                          scaleID: 'x',
                          value: new Date(),
                          borderColor: 'black',
                          borderWidth: 3,
                          label: {
                              backgroundColor: 'black',
                              content: 'Now',
                              enabled: true
                          }
                      }
<?php } ?>
                  ]
              }
              },
              elements: {
                  point: {
                      radius: 0,
                      hitRadius: 10
                  }
              },
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                  x: {
                      type: 'time',
                      time: {
                          unit: 'hour'
                      }
                  },
                  y: {
                      ticks: {
                        suggestedMin: 0,
                        suggestedMax: <?php echo $capacity; ?>
                      }
                  }
              }
          }
      });
  </script>
</body>
</html>
