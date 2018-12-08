<!DOCTYPE html>
<html lang="en">

<?php
   session_start();
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
        <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header" style="text-align: center">Create an Account</h1>
                <?php 
                    if(isset($_SESSION['takenErr']))
                    {
                        echo "Username taken";
                    }
                ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- ALL OF THESE NEEDS IDs -->
                <form action = "accountcreation.php" method = "post" style="font-size:120%;">
                    <div class="form-group">
                        <label for="firstname">First name:</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required="required" oninvalid="this.setCustomValidity('This field is required.' )" oninput="setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last name:</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required="required" oninvalid="this.setCustomValidity('This field is required.' )" oninput="setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="emailaddress">Email Address:</label>
                        <input type="email" id="emailAddress" name="email" class="form-control" required="required" oninvalid="this.setCustomValidity('Please enter a valid email address.' )" oninput="setCustomValidity('')"/>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password1" class="form-control" required="required" oninvalid="this.setCustomValidity('This field is required.' )" oninput="setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password:</label>
                        <input type="password" id="confirmPassword" name="password2" onkeyup="check(); return false;" class="form-control" required="required" oninvalid="this.setCustomValidity('This field is required.' )" oninput="setCustomValidity('')">
                        <span id="confirmMessage" class="confirmMessage"></span>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-offset-5 col-sm-2">
                                <button class="btn btn-lg btn-success btn-block" type = "submit" name = "create_acc">Create Account</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /#container --> 
    <script>
        function check(){
            var pass = document.getElementById('password');
            var confirmpass = document.getElementById('confirmPassword');
            var message = document.getElementById('confirmMessage');
            var green = "#66cc66";
            var red = "#ff6666";
            if(pass.value == confirmpass.value){
                confirmpass.style.backgroundColor = green;
                message.style.color = green;
                message.innerHTML = "Passwords Match!"
            }
            else{
                confirmpass.style.backgroundColor = red;
                message.style.color = red;
                message.innerHTML = "Passwords Do Not Match!"
            }
        }
    </script>
    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>