<?php
    session_start();
    $email = $_SESSION['email'];
    if (isset($_POST['firstName']) ||  isset($_POST['lastName']) || isset($_POST['currentPassword']) || isset($_POST['newPassword']) || isset($_POST['confirmnewPassword']))
    {
        $var1 = $_POST['firstName'];
        $var2 = $_POST['lastName'];

        $var4 = $_POST['currentPassword'];
        $var5 = $_POST['newPassword'];
        $var6 = $_POST['confirmnewPassword'];

        $connection = mysqli_connect("localhost", "root", "", "sys");

        $passhashCopy = $_SESSION['password'];
        if ($connection == false)
        {
            echo "Connection Failed!";
            die();
        }
            
        if ($var1 == null)
        {
            
        }
        else
        {
            $test1 = mysqli_query($connection, "UPDATE Users SET first_name='$var1' WHERE email_addr='$email'");
            $_SESSION['first'] = $var1;
            
        }

        if ($var2 == null)
        {
            
        }
        else
        {
            $test2 = mysqli_query($connection, "UPDATE Users SET last_name='$var2' WHERE email_addr='$email'");
            $_SESSION['last'] = $var2;
        }

        if ($var4 != null)
        {
            if (md5($var4) == $passhashCopy) 
            {
                if (md5($var5) == md5($var6))
                {
                    $newPassHash = md5($var5);
                    $test3 = mysqli_query($connection, "UPDATE Users SET password='$newPassHash' WHERE email_addr='$email'");
                    $_SESSION['password'] = $newPassHash;

                    header("refresh:3, url=account.php");
                    //echo "<script type='text/javascript'>alert('Password successfully updated.')</script>";
                    echo "Password successfully updated, redirecting back to account page.";
                    exit;

                }
                else
                {
                    header("refresh:3; url=updateAccount.php");
                    //echo "<script type='text/javascript'>alert('Error: New password entered don't match.')</script>";
                    echo "Error: New password entered doesn't match, redirecting back to edit account page.";
                    exit;
                }
            }
            else
            {
                header("refresh:3; url=updateAccount.php");
                //echo "<script type='text/javascript'>alert('Error: User entered in wrong password.')</script>";
                echo "Error: User entered in wrong current password, redirecting back to edit account page.";
                exit;
            }
        }
        mysqli_close($connection);
        header("Location: account.php");
        exit;

    }
?>