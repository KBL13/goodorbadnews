<?php
    if (isset($_POST['firstName']) &&  isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password1']) &&  isset($_POST['password2']))
    {
        $first = $_POST['firstName'];
        $last = $_POST['lastName'];
        $email = $_POST['email'];
        $pass1 = $_POST['password1'];
        $pass2 = $_POST['password2'];
        
        if ($pass1 == $pass2)
        {
            $connection = mysqli_connect("localhost", "root", "", "sys");
            if ($connection == false)
            {
                echo "Connection Failed!";
                die();
            }
            
            $results = mysqli_query($connection, "SELECT email FROM Users WHERE email_addr='$email'");
            if (mysqli_num_rows($results) == 0)
            {
                $pass1 = md5($pass1);
                $success = mysqli_query($connection, "INSERT INTO Users (first_name, last_name, email_addr, password) VALUES ('$first', '$last', '$email', '$pass1')");
                
                #Account ADDED!!!
                if ($success)
                {
                    mysqli_close($connection);
                    header("Location: login.php");
                    exit;
                }
            }
            
            #username or email already in  use
            else
            {
                mysqli_close($connection);
                header("Location: createaccount.php");
                exit;
            }
        }
        
        #else return to creation page
        header("Location: createaccount.php");
        exit;
        
    }
?>