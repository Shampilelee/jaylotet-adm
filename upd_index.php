
<?php
    include("database.php");


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $item_name = filter_input(INPUT_POST, "item_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $usr_chose = filter_input(INPUT_POST, "usr_chose", FILTER_SANITIZE_SPECIAL_CHARS);
        $usr_chg = filter_input(INPUT_POST, "usr_chg", FILTER_SANITIZE_SPECIAL_CHARS);

        
        $sql_code = "SELECT * FROM `items` WHERE item_name = '$item_name' ";
        $get_ID = mysqli_query($conn, $sql_code);


        if (mysqli_num_rows($get_ID) > 0) {
            
            $row = mysqli_fetch_assoc($get_ID);
        
            $id = $row["item_id"];

            if ($row["item_name"] == $item_name) {
                $sql_code_update_item = "UPDATE `items` SET `$usr_chose` = '$usr_chg' WHERE `items`.`item_id` = $id";
                mysqli_query($conn, $sql_code_update_item);
                echo "<br> $usr_chose UPDATED. <br>";
            }
            
        }
        else{
            echo "<br> Item Name Not in Our Database <br>";
        }

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
        <h1>UPDATE ITEMS</h1>
    </header>
    <nav class="header_navbar">
        <a href="find_index.php">Find Products</a>
        <a href="add_index.php">Add Products</a>
        <a href="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">Update</a>
        <a href="transactions.php">Transactions</a>
    </nav>


    <main>

        <div class="container">
        
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
                
                <input type="text" name="item_name" id="user_input" placeholder="Enter Item Name" required>
                <br>
                <ul id="suggestions"></ul>
                <br>

                <label for="title" id="title">Chose One:</label><br>

                <div class="chose">
                    <div id="num1">
                        <label for="cost">Cost</label>
                        <input type="radio" name="usr_chose" id="cost" value="item_cost" required>
                        
                        <label for="quantity">Quantity</label>
                        <input type="radio" name="usr_chose" id="quantity" value="item_quantity">
                    </div>

                    <div id="num2">
                        <label for="category">Category</label>
                        <input type="radio" name="usr_chose" id="category" value="item_category">

                        <label for="user">User</label>
                        <input type="radio" name="usr_chose" id="user" value="user">
                        <br>
                    </div>
                </div>
                
                
                
                <input type="text" name="usr_chg" placeholder="Enter Change Here" minlength="3" maxlength="50" required>
                <br>

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










