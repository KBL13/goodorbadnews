<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
    if (!isset($_SESSION['email']))
    {
        header("Location: login.php");
        exit;
    }
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GoodOrBad News</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- CUSTOM CSS -->
    <link href="../customcss/custom.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top fixedtop" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">GoodOrBad News</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="account.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar fixed" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        
                        <li>
                            <a href="search.php"><i class="fa fa-search fa-fw"></i> Search</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <br/>
        <br/>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center">
                            <i style="text-align: center">
                                <?php
                                    $var_value = $_GET['varname'];
                                    echo $var_value;
                                    //SELECT * FROM `Sentiments` WHERE co_name = 'WMT' ORDER BY `dat_val` ASC

                                    $data_high = array();
                                    $data_sent = array();
                                    $data_low = array();
                                    $data_date = array();
                                    $data_vol = array();

                                    $connection = mysqli_connect("localhost", "root", "", "sys");
                                    $data_pull = mysqli_query($connection, "SELECT * FROM Sentiments WHERE co_name = '$var_value' ORDER BY dat_val ASC");
                                    while ($data_entry = mysqli_fetch_assoc($data_pull)) 
                                    { 
                                        $temp_hi = $data_entry["hi_val"];
                                        $temp_sent = $data_entry["sent_val"];
                                        $temp_lo = $data_entry["lo_val"];
                                        $temp_dat = $data_entry["dat_val"];
                                        $temp_vol = $data_entry["vol_val"];

                                        $a = array("y" => $temp_hi, "label" => $temp_dat);
                                        $b = array("y" => $temp_sent, "label" => $temp_dat);
                                        $c = array("y" => $temp_lo, "label" => $temp_dat);
                                        $d = array("y" => $temp_dat, "label" => $temp_dat);
                                        $e = array("y" => $temp_vol, "label" => $temp_dat);

                                        array_push($data_high,$a);
                                        array_push($data_sent,$b);
                                        array_push($data_low,$c);
                                        array_push($data_date,$d);
                                        array_push($data_vol,$e);
                                    } 
                                ?>       
                            </i>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <script>
                                    window.onload = function () {
 
                                    var chart = new CanvasJS.Chart("chartContainer", {
                                    title: {
                                        text: "Sentiment History Average Score"
                                    },
                                    axisY: {
                                        title: "Sentiment Value",
                                        minimum: 0.0,
                                        maximum: 12.0
                                    },
                                    data: [
                                        {
                                            type: "line",
                                            dataPoints: <?php echo json_encode($data_high, JSON_NUMERIC_CHECK); ?>
                                        }]
                                    });
                                    chart.render();
 
                                    }
                                </script>

                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            </div>

                            <div class="list-group">
                                <?php
                                    echo'<style>
                                    table, th, td {
                                        border: 1px solid black;
                                    }
                                    </style>';
                                    echo'<table style="width:100%">';
                                        echo'<tr style="text-align: center">';
                                            echo'<th style="text-align: center">Date</th>';
                                            echo'<th style="text-align: center">News Volume</th>';
                                            echo'<th style="text-align: center">Sentiment High</th>';
                                            echo'<th style="text-align: center">Sentiment Avg</th>';
                                            echo'<th style="text-align: center">Sentiment Low</th>';
                                        echo'</tr>';
                                    for ($x = sizeof($data_high)-1; $x >- 0; $x--) 
                                    {
                                        echo'<tr style="text-align: center">';
                                            echo'<td>';
                                                echo $data_date[$x]["y"];
                                            echo'</td>';
                                            echo'<td>';
                                                echo $data_vol[$x]["y"];
                                            echo'</td>';
                                            echo'<td>';
                                                echo $data_high[$x]["y"];
                                            echo'</td>';
                                            echo'<td>';
                                                echo $data_sent[$x]["y"];
                                            echo'</td>';
                                            echo'<td>';
                                                echo $data_low[$x]["y"];
                                            echo'</td>';
                                        echo'</tr>';
                                    }
                                    echo'</table>';
                                ?>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
