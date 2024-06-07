
<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset</title>
    <link rel="icon" type="image/png" href="assets/images/favicon-bg.png">
    <link rel="stylesheet" href="dhope_style.css">
</head>
<body>
    <main>
        <div class="border">
            <h2>Reset Password 👨‍💻</h2>

            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="text" placeholder="username" name="usr_name" required><br>
                <input type="number" placeholder="user pin" name="usr_pin" minlength="4" maxlength="4" required><br>
                <input type="password" placeholder="New Password" name="new_pwd" required><br>
                <input type="submit" name="submitBtn" value="Recover"><br>
            </form>

            <div class="links">          
                <a href="index.php" >Sign In</a>
            </div>

        </div>
    </main>
</body>
</html>



<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $username = filter_input(INPUT_POST, "usr_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_pin = filter_input(INPUT_POST, "usr_pin", FILTER_SANITIZE_NUMBER_INT);
        $new_password = filter_input(INPUT_POST, "new_pwd", FILTER_SANITIZE_SPECIAL_CHARS);

        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        echo "<br> $username <br> $new_password <br>";

        $sql_code = "SELECT * FROM `admin_users` WHERE username = '$username' && userpin = '$user_pin' ";
        $get_ID = mysqli_query($conn, $sql_code);


        if (mysqli_num_rows($get_ID) > 0) {
            
            $row = mysqli_fetch_assoc($get_ID);
            $row["username"];
            $id = $row["user_id"];
            echo $id;

            if ($row["username"] == $username && $row["userpin"] == $user_pin) {
                $sql_code_update_pwd = "UPDATE `admin_users` SET `password` = '$new_hashed_password' WHERE `user_id` = $id";
                mysqli_query($conn, $sql_code_update_pwd);
                echo "<br> PASSWORD UPDATED, ACCOUNT RECOVERED, TRY LOGGING IN. <br>";
            }
            
        }
        else{
            echo "<br> Email Not in Our Database <br>";
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