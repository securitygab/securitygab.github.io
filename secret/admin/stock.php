<?php
    if (!isset($_GET['access'])) header("Location: /");

    require '../includes/init.php';

    $settings = $database->query("SELECT * FROM `settings`")->single();

    if ($_GET['access'] != $settings['admin']) header("Location: /");

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

                    <div class="animated fadeIn">
                        <div class="card">
                            <div class="card-body">
                                <label for="accounts">Accounts</label>
                                <select id="type" class="form-control">
                                    <?php

                                        $result = $database->query("SELECT * FROM `producttypes`")->resultset();

                                        foreach ($result as $row) {
                                            echo "<option value=\"$row[id]\">$row[name]</option>";
                                        }

                                    ?>
                                </select>
                                <br>
                                <textarea class="form-control" name="accounts" id="accounts" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-info btn-block" onclick="submit()">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.conainer-fluid -->
    </main>

    <script>

        function submit() {

            $.post('/admin/stock/update?access=<?= $_GET['access'] ?>', {
                type: $('#type').val(),
                accounts: $('#accounts').val()
            }, function (result) {
                alert(result);
                location.reload();
            })

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