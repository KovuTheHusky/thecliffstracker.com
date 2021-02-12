<?php

$page = 'average';

date_default_timezone_set('America/New_York');

$locations = array('DUM', 'HLM', 'LIC', 'VAL', 'CAL');

$weekday = (int) $_GET['weekday'];
$start = mktime(6, 0, 0, 9, 6 + $weekday, 2020);
$end = $start + 64800;

$db = new PDO('sqlite:db.sqlite');

$capacity = 0;

$accumulator = array();
foreach ($locations as $location) {
    $accumulator[$location] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
}

$counter = array();
foreach ($locations as $location) {
    $counter[$location] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
}

for ($low = $start; $low < time(); $low += 604800) {
    $high = $low + 64800;
    $rows = $db->query("SELECT * FROM data WHERE time BETWEEN {$low} AND {$high}")->fetchAll();
    foreach ($rows as $row) {
        $location = $row['location'];
        $hour = date('G', $row['time']);
        $accumulator[$location][$hour] += $row['count'];
        ++$counter[$location][$hour];
        if ($row['capacity'] > $capacity) {
            $capacity = $row['capacity'];
        }
    }
}

$averages = array();
foreach($locations as $location) {
    for($i = 0; $i < 24; ++$i) {
        $averages[$location][$i] = $counter[$location][$i] != 0 ? $accumulator[$location][$i] / $counter[$location][$i] : 0;
    }
}

foreach ($averages as $loc => $val) {
    for ($i = 6; $i < 24; ++$i) {
        $dt = date('r', mktime($i, 30, 0));
        $data[$loc]['data'][] = "{x: new Date('{$dt}'), y: {$val[$i]}}";
        $data[$loc]['labels'][] = "'{$dt}'";
    }
}

foreach ($data as $location => $locationData) {
    $data[$location]['data'] =  implode(',', $data[$location]['data']);
    $data[$location]['labels'] =  implode(',', $data[$location]['labels']);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cliffs-tracker/includes/header.php');

?>

<nav aria-label="..." style="margin-top: 16px;">
  <ul class="pagination justify-content-center">
    <li class="page-item"><a class="page-link" href="/cliffs-tracker/average/<?php echo $weekday - 1 < 0 ? 6 : $weekday - 1; ?>">&laquo;</a></li>
    <li class="page-item<?php if ($weekday == 0) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/0">U</a></li>
    <li class="page-item<?php if ($weekday == 1) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/1">M</a></li>
    <li class="page-item<?php if ($weekday == 2) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/2">T</a></li>
    <li class="page-item<?php if ($weekday == 3) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/3">W</a></li>
    <li class="page-item<?php if ($weekday == 4) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/4">R</a></li>
    <li class="page-item<?php if ($weekday == 5) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/5">F</a></li>
    <li class="page-item<?php if ($weekday == 6) { echo ' active'; } ?>"><a class="page-link" href="/cliffs-tracker/average/6">S</a></li>
    <li class="page-item"><a class="page-link" href="/cliffs-tracker/average/<?php echo $weekday + 1 > 6 ? 0 : $weekday + 1; ?>">&raquo;</a></li>
  </ul>
</nav>

  <div style="width: 100%; height: calc(100% - 126px);">
    <canvas id="chart"></canvas>
  </div>
  <script src="/cliffs-tracker/node_modules/moment/min/moment.min.js"></script>
  <script src="/cliffs-tracker/node_modules/chart.js/dist/Chart.min.js"></script>
  <script src="/cliffs-tracker/node_modules/chartjs-plugin-annotation/chartjs-plugin-annotation.min.js"></script>
  <script>
      var ctx = document.getElementById('chart');
      var myLineChart = new Chart(ctx, {
          type: 'line',
          labels: [
<?php for ($i = 0; $i <= 18; ++$i) { ?>
              new Date('<?php echo date('r', $start + $i * 3600); ?>'),
<?php } ?>
          ],
          data: {
              datasets: [
<?php if (isset($data['DUM']['data'])) { ?>
                  {
                      label: 'DUMBO',
                      data: [
                          {x: new Date('<?php echo date('r', mktime(6, 0, 0)); ?>'), y: 0},
                          <?php echo $data['DUM']['data']; ?>,
                          {x: new Date('<?php echo date('r', mktime(24, 0, 0)); ?>'), y: 0}
                        ],
                      borderColor: 'rgb(255, 0, 0)',
                      backgroundColor: 'rgba(255, 0, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['HLM']['data'])) { ?>
                  {
                      label: 'Harlem',
                      data: [
                          {x: new Date('<?php echo date('r', mktime(6, 0, 0)); ?>'), y: 0},
                          <?php echo $data['HLM']['data']; ?>,
                          {x: new Date('<?php echo date('r', mktime(24, 0, 0)); ?>'), y: 0}
                        ],
                      borderColor: 'rgb(0, 255, 255)',
                      backgroundColor: 'rgba(0, 255, 255, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['LIC']['data'])) { ?>
                  {
                      label: 'LIC',
                      data: [
                          {x: new Date('<?php echo date('r', mktime(6, 0, 0)); ?>'), y: 0},
                          <?php echo $data['LIC']['data']; ?>,
                          {x: new Date('<?php echo date('r', mktime(24, 0, 0)); ?>'), y: 0}
                        ],
                      borderColor: 'rgb(0, 255, 0)',
                      backgroundColor: 'rgba(0, 255, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['VAL']['data'])) { ?>
                  {
                      label: 'Valhalla',
                      data: [
                          {x: new Date('<?php echo date('r', mktime(6, 0, 0)); ?>'), y: 0},
                          <?php echo $data['VAL']['data']; ?>,
                          {x: new Date('<?php echo date('r', mktime(24, 0, 0)); ?>'), y: 0}
                        ],
                      borderColor: 'rgb(255, 165, 0)',
                      backgroundColor: 'rgba(255, 165, 0, 0.1)'
                  },
<?php } ?>
<?php if (isset($data['CAL']['data'])) { ?>
                  {
                      label: 'Callowhill',
                      data: [
                          {x: new Date('<?php echo date('r', mktime(6, 0, 0)); ?>'), y: 0},
                          <?php echo $data['CAL']['data']; ?>,
                          {x: new Date('<?php echo date('r', mktime(24, 0, 0)); ?>'), y: 0}
                        ],
                      borderColor: 'rgb(255, 0, 255)',
                      backgroundColor: 'rgba(255, 0, 255, 0.1)'
                  },
<?php } ?>
              ]
          },
          options: {
<?php if (date('D') == date('D', $start) && date('G') >= 6) { ?>
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
              responsive: true,
              maintainAspectRatio: false,
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
