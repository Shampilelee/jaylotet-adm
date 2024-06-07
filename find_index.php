
<?php
    session_start();
    include("database.php");

    # TO BE USED FOR ERROR AND RESULT DISPLAY
    $message = "";
    
    # CHECKING IF FORM HAS BEING SUBMITED.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        
        
        # RECEIVE AND SANITAICES RAW DATA FROM USER
        $user_input = filter_input(INPUT_POST, "user_input", FILTER_SANITIZE_SPECIAL_CHARS);

        # THIS IS THE SQL QEURY TO RETRIVE ALL DATA IN A ROW THAT, 
        # THE '$username' ENTERED BY THE USER MATCHES WHAT IS IN THE 'username' OF THAT ROW IN MySQL.
        $sql_code = "SELECT * FROM `items` WHERE item_name = '$user_input'";
        
        # EXECUTE CODE AND STORE RESULTS IN '$get_ID'
        $get_ID = mysqli_query($conn, $sql_code);
        
        # CHECKING IF '$username' ENTERED BY USER HAS AN ID,
        # IF YES THEN THE USERNAME IS VALID(IN OUR DATABASE).
        if (mysqli_num_rows($get_ID) > 0) {
            
            # FETCH ALL DATA IN ROW FROM '$get_ID' IN STORE IT IN '$row'
            $row = mysqli_fetch_assoc($get_ID);
            $message = "item name = " .$row["item_name"] . "<br>" . "item cost = ".$row["item_cost"]  . "<br>" . "item category = ".$row["item_category"];

            /*
            # CHECKING WETHER $password PROVERDED BY USER, 
            # BELONG TO THE SAME ID OF THE USERNAME($username) PROVIDED BY THE USER,
            # AND MATCHES THE HASHED PASSWORD IN THAT SAME ID.
            if (password_verify($password, $row["password"]) ) {
                echo "<br>";
                echo "--- PASSWORD MATCHES 🔓 ---" . "<br>";
                
                header("location: home_page/land.php");
            }
            else {
                echo "<br>";
                echo "PASSWORD IS NOT VALID, TRY AGAIN 🔐🔴" . "<br>";
            }
            */
        }
        else{
            $message = "Sorry, Item Not In Our Database";
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
    <link rel="stylesheet" href="find_style.css">
    <title>Portal</title>
</head>
<body>
    <header>
        <?php echo $_SESSION["usr_name"]; ?>
        <h1>FIND PRODUCTS</h1>
    </header>
    <nav>
        <a href="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">Find Products</a>
        <a href="add_index.php">Add Products</a>
        <a href="upd_index.php">Update</a>
        <a href="logout_index.php" id="logout">Logout</a>
    </nav>

    <?php echo $message ?>

    <main>
        <div class="container">
            <h1>Enter The Item Name</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" placeholder="item name" name="user_input" id="user_input" required>
                <ul id="suggestions"></ul>
                <input type="submit" id="submit_Btn" value="Find">
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
            # echo "<br> Database Connected ☁";
        } else {
            # THIS 'new Exception' WILL BE DISPLAYED AS THE NEW ERROR MESSAGE.
            throw new Exception("<br> You're Not Connected To Database. Please try again later.");
        }
    } catch (Exception $e) {
        // Display a user-friendly error message
        echo $e->getMessage();
    }
?>



