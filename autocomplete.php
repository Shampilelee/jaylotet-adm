
<?php
include("database.php");

if (isset($_GET['query'])) {
    $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS);
    $sql_code = "SELECT item_name FROM items WHERE item_name LIKE '%$query%'";
    $result = mysqli_query($conn, $sql_code);
    $suggestions = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $suggestions[] = $row['item_name'];
        }
    }

    echo json_encode($suggestions);
}

# CLOSE CONNECTION WITH MySQL
mysqli_close($conn);
?>


