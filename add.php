<?php
    session_start();

    function callAPI($method, $url, $data){
        $curl = curl_init();

        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                              
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'AppKey: 7bc14f1aea4e45f1ad8e4d9f1fcaad85',
        'Accept: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
    }

    if (isset($_POST['search']))
    {                        
        $search = $_POST['search'];
        $email = $_SESSION['email'];

        $connection = mysqli_connect("localhost", "root", "", "sys");
        $duplicate = mysqli_query($connection, "SELECT co_name FROM Sentiments WHERE co_name='$search'");

        if (mysqli_num_rows($duplicate) == 0)
        {
            $get_data = callAPI('GET', 'https://feed.finsents.com/search_company?query='.$search, false);
            $response = json_decode($get_data, true);
            $c_id = $response ['Result'][0]['nameid'];

            $get_sentiment = callAPI('GET', 'https://feed.finsents.com/idata/get_sentiment_history?nameid='.$c_id.'&timezone=America/New_York', false);
            $big_data = json_decode($get_sentiment, true);

            $result_arr = $big_data['Result']['data'][0]['sentiment'];
            $company_name = $big_data['Result']['data'][0]['name'];

            foreach ($result_arr as $value) 
            {
                $volume_val = (string)$value['volume'];
                $sentiment_val = (string)$value['sentiment'];
                $high_val = (string)$value['high'];
                $low_val = (string)$value['low'];
                $temp_val = (string)$value['date'];

                $slice = explode("-", $temp_val);
                $date_val = (string) ($slice[1].'-'.$slice[0].'-'.$slice[2]);

                $success = mysqli_query($connection, "INSERT INTO Sentiments (co_name, vol_val, sent_val, hi_val, lo_val, dat_val) VALUES ('$search', '$volume_val', '$sentiment_val', '$high_val', '$low_val', '$date_val')");
                
                #Account ADDED!!!
                /*if ($success)
                {
                    echo "Record-Insert updated successfully";
                }*/
            }

            $email = $_SESSION['email'];
            $results = mysqli_query($connection, "SELECT first_name,last_name,portfolio FROM Users WHERE email_addr ='$email'");
            $row = mysqli_fetch_row($results);
            if($row[2] != NULL || $row[2] != '')
            {
                //echo $row[2];
                $phrase = (string)($row[2]).",".(string)($search);
                //echo $phrase;
                if (mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'")) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . mysqli_error($connection);
                }

                //echo "test1 ran";
            }
            else{
                $phrase = $search;
                //update userloginstats set logouttime= sysdate where logouttime is null;
                #$add_list = mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'");
                if (mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'")) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . mysqli_error($connection);
                }

                //echo $email;
                //echo "test2 ran";
            }
            ob_clean();
            header("Location: index.php");
            exit;

        }
        else
        {
            //When adding a company name, checks if name already exits in database.
            //If it does itll delete old records and if it also exists in the users portfolio then it will remove it also.
            //This will be used if user wants to update his portfolio with the company he wants to update the record for.
            mysqli_query($connection, "UNLOCK TABLES");
            mysqli_query($connection, "DELETE FROM Sentiments WHERE co_name='$search'");  

            $update_port= mysqli_query($connection, "SELECT * FROM Users WHERE email_addr = '$email'");
            $row = mysqli_fetch_row($update_port);

            if(strpos($row[4],$search))
            {
                echo "nothing changed";   
            }else{
                $email = $_SESSION['email'];
                $results = mysqli_query($connection, "SELECT first_name,last_name,portfolio FROM Users WHERE email_addr ='$email'");
                $row = mysqli_fetch_row($results);
                if($row[2] != NULL || $row[2] != '')
                {
                    //echo $row[2];
                    $phrase = (string)($row[2]).",".(string)($search);
                    //echo $phrase;
                    if (mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'")) {
                        echo "Record updated successfully";
                    } else {
                        echo "Error updating record: " . mysqli_error($connection);
                    }

                    //echo "test1 ran";
                }
                else{
                    $phrase = $search;
                    //update userloginstats set logouttime= sysdate where logouttime is null;
                    #$add_list = mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'");
                    if (mysqli_query($connection, "UPDATE Users SET portfolio='$phrase' WHERE email_addr = '$email'")) {
                        echo "Record updated successfully";
                    } else {
                        echo "Error updating record: " . mysqli_error($connection);
                    }

                //echo $email;
                //echo "test2 ran";
                }
            }
            
            //If user typed in the same company that already exists in his portfolio, it will delete records of it and reinsert them into DB
            //If it doesnt exist for the user but it exists in the DB for someone else then it will delete and reinsert.
            //Everytime someone querys for a company it will inturn update the DB.

            $get_data = callAPI('GET', 'https://feed.finsents.com/search_company?query='.$search, false);
            $response = json_decode($get_data, true);
            $c_id = $response ['Result'][0]['nameid'];

            $get_sentiment = callAPI('GET', 'https://feed.finsents.com/idata/get_sentiment_history?nameid='.$c_id.'&timezone=America/New_York', false);
            $big_data = json_decode($get_sentiment, true);

            $result_arr = $big_data['Result']['data'][0]['sentiment'];
            $company_name = $big_data['Result']['data'][0]['name'];

            foreach ($result_arr as $value) 
            {
                $volume_val = (string)$value['volume'];
                $sentiment_val = (string)$value['sentiment'];
                $high_val = (string)$value['high'];
                $low_val = (string)$value['low'];
                $temp_val = (string)$value['date'];

                $slice = explode("-", $temp_val);
                $date_val = (string) ($slice[1].'-'.$slice[0].'-'.$slice[2]);

                $success = mysqli_query($connection, "INSERT INTO Sentiments (co_name, vol_val, sent_val, hi_val, lo_val, dat_val) VALUES ('$search', '$volume_val', '$sentiment_val', '$high_val', '$low_val', '$date_val')");
                
                #Account ADDED!!!
                /*if ($success)
                {
                    echo "Record-Insert updated successfully";
                }*/
            }

            ob_clean();
            header("Location: index.php");
            exit;

        }
    }        

?>