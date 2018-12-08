<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "sys");
#Quits if failed to connect
if ($conn == false)
{
    echo "Connection Failed!";
    die();
}

#Login if connected
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    
    $results = mysqli_query($conn, "SELECT password, first_name, last_name FROM Users WHERE email_addr = '$email'");
    $row = mysqli_fetch_row($results);
    
    #success
    if ($password == $row[0])
    {
        echo "Successful Login!";
        $_SESSION['email'] = $email;
        $_SESSION['first'] = $row[1];
        $_SESSION['last'] = $row[2];
        $_SESSION['password'] = $row[0];
        mysqli_close($conn);
        header("Location: index.php");
        exit;
    }
    
    #fail
    else
    {
        echo "Failed Login!";
        mysqli_close($conn);
        #header("Location: login.php");
        exit;
    }  
    
}

else
    echo "Can't get data";

?>