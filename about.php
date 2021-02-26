<?php

$page = 'about';

date_default_timezone_set('America/New_York');

$db = new PDO('sqlite:db.sqlite');

require_once($_SERVER['DOCUMENT_ROOT'] . '/cliffs-tracker/includes/header.php');

?>

<div class="container">

<h1 style="margin: 16px 0;">About</h1>

<p>Cliffs Tracker is an open-source PHP project that queries <a href="https://thecliffsclimbing.com">The Cliffs</a> website for the current amount of climbers at each gym and logs it to a SQLite database. Then that information is exposed via charts and graphs on this website.</p>

<p>If you would like to help out please feel free to open <a href="https://github.com/KovuTheHusky/Cliffs-Tracker/issues">issues</a> or <a href="https://github.com/KovuTheHusky/Cliffs-Tracker">fork us on GitHub</a> and open a <a href="https://github.com/KovuTheHusky/Cliffs-Tracker/pulls">pull request</a>.</p>

<h1 style="margin: 16px 0;">License</h1>

<p>The MIT License (MIT)</p>

<p>Copyright (c) 2020-<?php echo date('Y'); ?> KovuTheHusky</p>

<p>Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:</p>

<p>The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.</p>

<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.</p>

</div>

</body>
</html>
