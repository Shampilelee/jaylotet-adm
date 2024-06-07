
<?php
    session_start();
    include("database.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <link rel="icon" type="image/png" href="assets/images/favicon-bg.png">
    <link rel="stylesheet" href="dhope_style.css">
</head>
<body>
    <main>
        <div class="border">
            <h2>Enter Portal 👨‍💻</h2>

            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="text" placeholder="Username" minlength="3" maxlength="15" name="user_name" required><br>
                <input type="password" placeholder="Password" minlength="4" name="user_pwd" required><br>
                <input type="submit" name="submit" value="login"><br>
            </form>
            
            <div class="links">
                <a href="forgetpassword.php">forgot password</a>
            </div>
            
        </div>
    </main>
</body>
</html>



<?php

    # CHECKING IF FORM HAS BEING SUBMITED.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        # RECEIVE AND SANITAICES RAW DATA FROM USER
        $username = filter_input(INPUT_POST, "user_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "user_pwd", FILTER_SANITIZE_SPECIAL_CHARS);

        // CAN BE USED OUTSIDE THIS FILE
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;

        # THIS IS THE SQL QEURY TO RETRIVE ALL DATA IN A ROW THAT, 
        # THE '$username' ENTERED BY THE USER MATCHES WHAT IS IN THE 'username' OF THAT ROW IN MySQL.
        $sql_code = "SELECT * FROM `admin_users` WHERE username = '$username' ";
        
        # EXECUTE CODE AND STORE RESULTS IN '$get_ID'
        $get_ID = mysqli_query($conn, $sql_code);
        
        # TO BE USED OUTSIDE THIS APP
        $_SESSION["usr_name"] = "";
        
        # CHECKING IF '$username' ENTERED BY USER HAS AN ID,
        # IF YES THEN THE USERNAME IS VALID(IN OUR DATABASE).
        if (mysqli_num_rows($get_ID) > 0) {
            
            # FETCH ALL DATA IN ROW FROM '$get_ID' IN STORE IT IN '$row'
            $row = mysqli_fetch_assoc($get_ID);
            echo "usrnam = $username <br> pwd = $password <br> ID -> " . $row["user_id"] . "<br>" . "StoredPWD =".$row["password"] . "<br>" . "userSTORED =".$row["username"]  . "<br>" . "user pin =".$row["userpin"];

            # CHECKING WETHER $password PROVERDED BY USER, 
            # BELONG TO THE SAME ID OF THE USERNAME($username) PROVIDED BY THE USER,
            # AND MATCHES THE HASHED PASSWORD IN THAT SAME ID.
            if (password_verify($password, $row["password"]) ) {
                echo "<br>";
                echo "--- PASSWORD MATCHES 🔓 ---" . "<br>";

                $_SESSION["usr_name"] = $row["username"];
                
                header("location: find_index.php");
            }
            else {
                echo "<br>";
                echo "PASSWORD IS NOT VALID, TRY AGAIN 🔐🔴" . "<br>";
            }
        }
        else{
            echo "Sorry, Username Not In Our Database";
        }

    }












    # CLOSE CONNECTION WITH MySQL
    try {
        // Check if $conn is a mysqli object
        if ($conn instanceof mysqli) {
            mysqli_close($conn);
            echo "<br> Database Connected ☁";
        } else {
            # THIS 'new Exception' WILL BE DISPLAYED AS THE NEW ERROR MESSAGE.
            throw new Exception("<br> You're Not Connected To Database. Please try again later.");
        }
    } catch (Exception $e) {
        // Display a user-friendly error message
        echo $e->getMessage();
    }
?>


