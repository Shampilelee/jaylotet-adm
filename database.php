<?php
# ANYTHING RELATED TO CONNECTING TO OUR DATABASE WILL BE HANDLED HERE.

# THIS HOLDS THE NAME OF THE SERVER(Host name)
$db_server = "localhost";

# THIS HOLDS THE NAME OF THE USER(User name)
$db_user = "root";

# THIS HOLDS THE PASSWORD
$db_pwd = "";

# THIS HOLDS THE NAME OF THE DATABASE
$db_name = "jaylotet_db";

# THIS IS THE CONNECTION VARIABLE
$conn = "";

// Enable mysqli exception mode
/*
    THE CODE BELOW ENABLE MySQLi TO THROW EXCEPTIONS WHEN CONNECTION ERROR OCCURS.
*/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);



try {
    # TO ESTABLISH A CONNECTION TO THE MySQL DATABASE, USE EXAMPLE BELOW.
    # [mysqli_connect()] IS AN EXTENTION IN VSCODE, IT TAKES FOUR(4) ARGUMENTS.
    # !! [mysqli_connect()] WAS GIVING ME ERRORS SO I USED [mysqli] !!
    # The @ symbol before new mysqli() suppresses any warnings or errors that might occur during the connection attempt
    # If an exception occurs during the connection attempt or if the connection fails, 
    # it will be caught by the catch block, and only the error message from the catch block will be displayed.
    # This should prevent any duplicate error message from being displayed.
    
    // Attempt to establish connection
    $conn = @new mysqli(
        $db_server, 
        $db_user, 
        $db_pwd, 
        $db_name
    );

    // Check Connection successful, do further processing here
    if ($conn){
        #echo "YOU ARE CONNECTED ! & Open \n";
    }
    
} 
/*
    catch(
        THEN PUT THE NAME OF THE ERROR HERE,
        IT USUALLY SHOW AFTER Warning:, Fatal error: AND SO ON,
        IF NOT WORKING FIND THE NAME OF THE ERROR ON GOOGLE AND PUT IT HERE. 
    )
*/
catch (Exception $e) {
    #echo "SORRY, COULD NOT CONNECT: ". $e->getMessage();
}


?>