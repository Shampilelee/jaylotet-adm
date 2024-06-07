
<?php
    session_start();
    include("database.php");


    // LOGOUT
    session_destroy();
    header("location: /JAYLOTET_SYS/Admin_Portal/src");
?>