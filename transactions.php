
<?php
    include("database.php");

    $message = "";
    $time_Stamp = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $item_name = trim(filter_input(INPUT_POST, "item_name", FILTER_SANITIZE_SPECIAL_CHARS));
        $item_cost = trim(filter_input(INPUT_POST, "item_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

        $item_quantity = trim(filter_input(INPUT_POST, "item_quantity", FILTER_SANITIZE_NUMBER_INT));
        $date_Sold = trim(filter_input(INPUT_POST, "date_sold", FILTER_SANITIZE_SPECIAL_CHARS));
        $time_Sold = trim(filter_input(INPUT_POST, "time_sold", FILTER_SANITIZE_SPECIAL_CHARS));
        
        $time_Stamp = "$date_Sold $time_Sold:34";
        
        # strpos() CHECKS IF SOMETHING CONTAINS SOMETHING.
        if (strpos($item_cost, ".")) {

            # MAKE SURE IT'S A FLOAT
            $item__cost = floatval($item_cost);
            
            if ($item_name && $item__cost !== false && $item_quantity) {
                $sql_code = "INSERT INTO `transactions` (item_sold, item_cost, quantity_sold, day_sold)
                             VALUES ('$item_name', '$item__cost', '$item_quantity', '$time_Stamp')";
    
                try {
                    mysqli_query($conn, $sql_code);
                    $message = "Transaction Registered!";
                } catch (mysqli_sql_exception $e) {
                    $message = "You have a problem adding transaction: " . $e->getMessage();
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






<style>
    /* Add this to your style.css */
    #suggestions {
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        list-style-type: none;
        padding: 0;
        margin: 0;
        position: absolute;
        width: 18vw;
        background-color: grey;
        color: white;
    }

    #suggestions li {
        padding: 10px;
        cursor: pointer;
    }

    #suggestions li:hover {
        background-color: #eee;
        color: black;
    }
</style>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="update.css">
    <title>Portal</title>
</head>
<body>
    <header>
        <h1>ADD TRANSACTIONS</h1>
    </header>
    <nav class="header_navbar">
        <a href="find_index.php">Find Products</a>
        <a href="add_index.php">Add Products</a>
        <a href="upd_index.php">Update</a>
        <a href="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">Transactions</a>
    </nav>
    
    <?php echo $message ?>

    <main>

        <div class="container">
        
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
                
                <input type="text" name="item_name" id="user_input" placeholder="Enter Item Name" required>
                <br>
                <ul id="suggestions"></ul>

                <input type="number" name="item_cost" step="0.01" placeholder="Enter Item Price ex: 5.0" required>
                <br>

                <input type="number" name="item_quantity" placeholder="Enter Quantity" required>
                <br>

                <input type="date" name="date_sold" required><br>

                <input type="time" name="time_sold" required>

                <!-- THIS IS A RESET BUTTON -->
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
        &copy;<span id="show_Date"></span> copyright reserved<br>
        <small><a href="mailto:yeshua.t137@gmail.com">yeshua.t137@gmail.com</a></small>
    </footer>





    <script>
        
        // Footer copyright YEAR
        const show = document.getElementById("show_Date");
        const now = new Date().getFullYear();
        show.textContent = now;

        // auto-complete
        let debounceTimeout;
        const debounce = (func, delay) => {
            return function(...args) {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => func.apply(this, args), delay);
            };
        };

        // HIDE SUGGESTION LIST FROM DISPLAYING
        document.getElementById('suggestions').style.display = "none";

        const fetchSuggestions = debounce(function() {
            const query = this.value;
            
            // CHECKING IF USER HAS ENTERED A VALUE INTO "user_input"
            if (query.length > 0) {

                // DISPLAY SUGGESTION LIST WHEN USER ENTERS VALUE.
                document.getElementById('suggestions').style.display = "block";


                // FETCHING FROM "autocomplete.php"
                fetch(`autocomplete.php?query=${encodeURIComponent(query)}`)
                    // THEN CONVERT RETRIVED DATA INTO JSON FORMAT.
                    .then(response => response.json())
                    // THEM WORK WITH RETRIVED DATA
                    .then(data => {
                        const suggestions = document.getElementById('suggestions');
                        suggestions.innerHTML = '';

                        // CHECKING IF DATA HAS BEEN RETRIVED FROM "autocomplete.php".
                        if (data.length > 0) {
                            
                            // FOR EACH ITEM/DATA RETRIVED FROM "autocomplete.php", CREATE A LIST(li),
                            // ASSIGN THE RETRIVED ITEM TO THE LIST.
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item;

                                li.addEventListener('click', () => {
                                    document.getElementById('user_input').value = item;
                                    suggestions.innerHTML = '';
                                    
                                    // HIDE SUGGESTION LIST FROM DISPLAYING
                                    document.getElementById('suggestions').style.display = "none";
                                });
                                suggestions.appendChild(li);
                            });

                        } else {
                            const li = document.createElement('li');
                            li.textContent = 'No results found';
                            suggestions.appendChild(li);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                    });
                    
            } else {
                document.getElementById('suggestions').innerHTML = '';

                // HIDE SUGGESTION LIST FROM DISPLAYING
                document.getElementById('suggestions').style.display = "none";
            }

        }, 300);

        // LET THE "user_input" FIELD LISTEN FOR INPUT FROM USER.
        document.getElementById('user_input').addEventListener('input', fetchSuggestions);

    </script>


    
    
</body>
</html>






<?php


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










