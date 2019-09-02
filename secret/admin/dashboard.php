<?php
    if (!isset($_GET['access'])) header("Location: /");

    require '../includes/init.php';

    $settings = $database->query("SELECT * FROM `settings`")->single();

    if ($_GET['access'] != $settings['admin']) header("Location: /");

    $result = $database->query('SELECT * FROM `payments` WHERE `payee_email`=:email')->bind('email', $settings['paypal'])->resultset();

    $income = 0;
    $fees = 0;

    foreach ($result as $row) {
        $GLOBALS['income'] += $row['mc_gross'];
        $GLOBALS['fees'] += $row['mc_fee'];
    }

?>

<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.6
 * @link http://coreui.io
 * Copyright (c) 2017 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword"
          content="Bootstrap,Admin,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>CakeAlts Admin</title>

    <!-- Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css"
          rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="css/style.css" rel="stylesheet">
</head>

<!-- BODY options, add following classes to body to change options

// Header options
1. '.header-fixed'					- Fixed Header

// Brand options
1. '.brand-minimized'       - Minimized brand (Only symbol)

// Sidebar options
1. '.sidebar-fixed'					- Fixed Sidebar
2. '.sidebar-hidden'				- Hidden Sidebar
3. '.sidebar-off-canvas'		- Off Canvas Sidebar
4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
5. '.sidebar-compact'			  - Compact Sidebar

// Aside options
1. '.aside-menu-fixed'			- Fixed Aside Menu
2. '.aside-menu-hidden'			- Hidden Aside Menu
3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

// Breadcrumb options
1. '.breadcrumb-fixed'			- Fixed Breadcrumb

// Footer options
1. '.footer-fixed'					- Fixed footer

-->

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" id="body">
<script>
    var d = document.getElementById("body");
    d.className += " sidebar-hidden";

    function copyToClipboard(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            // IE specific code path to prevent textarea being shown while dialog is visible.
            return clipboardData.setData("Text", text);

        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy");  // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }

</script>
<header class="app-header navbar">
    <!--<button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
      <span class="navbar-toggler-icon"></span>
    </button>-->

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="/admin?access=<?= $_GET['access']; ?>">Dashboard</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="/admin/stock?access=<?= $_GET['access']; ?>">Stock</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="/admin/settings?access=<?= $_GET['access']; ?>">Settings</a>
        </li>
    </ul>

</header>

<div class="app-body">

    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb">
        </ol>

        <div class="container-fluid">
            <div id="ui-view">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0">
                                    <div class="btn-group float-right">
                                        <button type="button" class="btn btn-transparent dropdown-toggle p-0"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-settings"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                    <h4 class="mb-0"><?= $settings['paypal'] ?></h4>
                                    <p>Current PayPal</p>
                                </div>
                                <div class="chart-wrapper px-3" style="height:70px;">
                                    <canvas id="card-chart1" class="chart" height="70"></canvas>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->

                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-info">
                                <div class="card-body pb-0">
                                    <h4 class="mb-0"><?= money($income) ?></h4>
                                    <p>Current Income</p>
                                </div>
                                <div class="chart-wrapper px-3" style="height:70px;">
                                    <canvas id="card-chart2" class="chart" height="70"></canvas>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->

                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body pb-0">
                                    <h4 class="mb-0"><?= money($fees) ?></h4>
                                    <p>Current Fees<br><small>currently not calculated correctly</small></p>
                                </div>
                                <div class="chart-wrapper" style="height:70px;">
                                    <canvas id="card-chart3" class="chart" height="70"></canvas>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->

                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body pb-0">
                                    <h4 class="mb-0"><?= money($income - $fees) ?></h4>
                                    <p>Current Profits<br><small>currently not calculated correctly</small></p>
                                </div>
                                <div class="chart-wrapper px-3" style="height:70px;">
                                    <canvas id="card-chart4" class="chart" height="70"></canvas>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label for="accounts">Generate Replacement</label>

                                    <div class="form-row">

                                        <div class="col-md-10">
                                            <select class="form-control" id="select">
                                                <?php

                                                    $result = $database->query("SELECT * FROM `producttypes`")->resultset();

                                                    foreach ($result as $row) {
                                                        echo "<option value=\"$row[id]\">$row[name]</option>";
                                                    }

                                                ?>
                                            </select>
                                        </div>
                                        <br>
                                        <div class="col-md-2">
                                            <input id="amount" class="form-inline form-control" value="1" type="number">

                                        </div>
                                    </div>
                                    <br>
                                    <textarea class="form-control" id="field"></textarea>
                                </div>
                                <div class="card-footer">
                                    <div class="form-row">

                                        <div class="col-md-8">
                                            <button class="btn btn-info btn-block" onclick="fetch()">Generate</button>
                                        </div>

                                        <div class="col-md-4">
                                            <button class="btn btn-info btn-block"
                                                    onclick="copyToClipboard($('#field').val())">Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- /.conainer-fluid -->
    </main>

    <script>

        function fetch() {
            $.get("/admin/alt/" + $('#select').val() + "&access=<?= $_GET['access'] ?>&limit=" + $('#amount').val(), function (result) {

                result = JSON.parse(result);

                for (var row = 0; row < result.length; row++) {

                    $('#field').html($('#field').html() + result[row] + "\n")

                }
            });
        }

    </script>

</div>

<!-- Bootstrap and necessary plugins -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

<!-- Plugins and scripts required by all views -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

<!-- CoreUI main scripts -->
</body>
</html>