
<?php
    include("database.php");
    session_start();


    $message = "";
    $_SESSION["usr_name"] = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $item_name = trim(filter_input(INPUT_POST, "item_name", FILTER_SANITIZE_SPECIAL_CHARS));
        $item_cost = trim(filter_input(INPUT_POST, "item_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

        $item_quantity = filter_input(INPUT_POST, "item_quantity", FILTER_SANITIZE_NUMBER_INT);
        $item_category = filter_input(INPUT_POST, "item_category", FILTER_SANITIZE_SPECIAL_CHARS);
        $item_type = filter_input(INPUT_POST, "item_type", FILTER_SANITIZE_SPECIAL_CHARS);
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);

        # strpos() CHECKS IF SOMETHING CONTAINS SOMETHING.
        if (strpos($item_cost, ".")) {

            # MAKE SURE IT'S A FLOAT
            $item__cost = floatval($item_cost);

            
            if ($item_name && $item__cost !== false && $item_category && $user) {
                $sql_code = "INSERT INTO `items` (item_name, item_cost, item_quantity, item_type, item_category, user)
                             VALUES ('$item_name', '$item__cost', '$item_quantity', '$item_type', '$item_category', '$user')";
    
                try {
                    mysqli_query($conn, $sql_code);
                    $message = "Item Registered!";
                } catch (mysqli_sql_exception $e) {
                    $message = "You have a problem adding product: " . $e->getMessage();
                }
            } else {
                $message = "Please fill in all fields correctly.";
            }

        } else {
            $message = "item cost MUST BE FLOAT";
        }
        
    } else {
        $message = "fill form";
    }

?>





<!-- HELPS IN  -->
<style>
    #choices {
        display: none;
    }
</style>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_item.css">
    <title>Portal</title>
</head>
<body>


    <header>
        <?php echo $_SESSION["usr_name"]; ?>
        <h1>ADD PRODUCT</h1>
    </header>
    
    <nav>
        <a href="find_index.php">Find Products</a>
        <a href="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">Add Products</a>
        <a href="upd_index.php">Update</a>
        <a href="logout_index.php" id="logout">Logout</a>
    </nav>

    <?php echo $message ?>

    <main>


        <div class="container">
            <h2>Add Item To Database</h2>
            
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">

                <div class="options">
                    <div id="opt_one">
                        <input type="text" name="item_name" placeholder="Item Name" minlength="3" maxlength="150" required>
                        <br>
                        <input type="number" name="item_cost" placeholder="Item Cost ex: 1.0" step="0.01" required><!-- step="0.01" will allow the form to accecpt decimal-point -->
                        <br>
                        <input type="number" name="item_quantity" placeholder="Quantity" required>
                        <br>
                    </div>

                    <div id="opt_two">
                        <select id="item_type" name="item_type" placeholder="type" required>
                            <option value="">Item Type</option>
                            <option value="Hair">Hair</option>
                            <option value="Perfume">Perfume</option>
                        </select>
                        <br>
    
                        <label for="category" id="show_catgory_lbel">Category</label><br>
                        <div id="choices">
                            <select id="hair" name="item_category" required>
                                <option value="">select</option>
                                <option value="Braid">Braid</option>
                                <option value="Lydia">Lydia</option>
                                <option value="Pony">Pony</option>
                                <option value="Twist">Twist</option>
                                <option value="Dread">Dread</option>
                                <option value="Locs">Locs</option>
                                <option value="Kinky">Kinky</option>
                                <option value="Bundle Hair">Bundle Hair</option>
                            </select>
    
                            <select id="spray" name="item_category" required>
                                <option value="">select</option>
                                <option value="Body Splash">body splash</option>
                                <option value="Deodorant">deo-dorant</option>
                                <option value="SURE">SURE</option>
                                <option value="Body Spray">body-spray</option>
                                <option value="Spray">Spray</option>
                            </select>
                        </div>
    
    
                        <select id="user" name="user" required>
                            <option value="">User</option>
                            <option value="DHOPE">Dhope</option>
                            <option value="JENN">Jayloo</option>
                        </select>
                        <br>
                    </div>
                </div>
                
                
                <div class="buttons">
                    <input type="reset" id="reset_Btn">
                    <input type="submit" id="submit_Btn">
                </div>
                
            </form>

        </div>


    </main>

    <footer>
        <h2>CONTACT</h2>
        <hr>
        author: Dhope Nation<br>
        &copy; copyright reserved<br>
        <small><a href="mailto:yeshua.t137@gmail.com">yeshua.t137@gmail.com</a></small>
    </footer>

    

    <script>
        const item_type = document.getElementById("item_type");

        const container = document.getElementById("choices");
        const category_label = document.getElementById("show_catgory_lbel");

        const hair = document.getElementById("hair");
        const spray = document.getElementById("spray");



        setInterval(() => {

            if (item_type.value == "Hair") {
                console.log("entered hair");
                category_label.textContent = "Category";

                // TURN DISPLAY ON
                container.style.display = "flex";
                container.style.justifyContent = "center";
                container.style.alignItems = "center";

                // HIDE THIS
                spray.style.display = "none";
                spray.required = false;
                spray.name = "asd";
                spray.value = "";

                hair.required = true;
                hair.name = "item_category";
                hair.style.display = "flex";
                console.log("passed hair");
            
            } else if (item_type.value == "Perfume") {
                console.log("entered perfume");
                category_label.textContent = "Category";

                container.style.display = "flex";
                container.style.justifyContent = "center";
                container.style.alignItems = "center";

                hair.style.display = "none";
                hair.required = false;
                hair.name = "dsa";
                hair.value = "";

                spray.required = true;
                spray.name = "item_category";
                spray.style.display = "flex";
                console.log("passed perfume");
            } else {
                // TURN DISPLAY OFF
                container.style.display = "none";
                console.log("container display off");
                category_label.textContent = "...";
            }

        }, 500);



    </script>
</body>
</html>






<?php

    # CLOSING CONNECTION
    try {
        if ($conn instanceof mysqli) {
            mysqli_close($conn);
            $message = "<br> Database Connected ☁";
        } else {
            throw new Exception("<br> You're Not Connected To Database. Please try again later.");
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
?>



