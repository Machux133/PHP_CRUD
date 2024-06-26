<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $kontrahent = trim($_POST['kontrahent']);
    $kontrahent = strip_tags( addslashes( $kontrahent ) );
    $kontrahent = ((isset($conn) && is_object($conn)) ? mysqli_real_escape_string($conn,  $kontrahent ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $rodzajTowaru = trim($_POST['rodzajTowaru']);
    $rodzajTowaru = strip_tags( addslashes( $rodzajTowaru ) );
    $rodzajTowaru = ((isset($conn) && is_object($conn)) ? mysqli_real_escape_string($conn,  $rodzajTowaru ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $ilosc = trim($_POST['ilosc']);
    $ilosc = strip_tags( addslashes( $ilosc ) );
    $ilosc = ((isset($conn) && is_object($conn)) ? mysqli_real_escape_string($conn,  $ilosc ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $status = trim($_POST['status']);
    $status = strip_tags( addslashes( $status ) );
    $status = ((isset($conn) && is_object($conn)) ? mysqli_real_escape_string($conn,  $status ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $data = date("Y-m-d"); 
    $data = strip_tags( addslashes( $data ) );
    $data = ((isset($conn) && is_object($conn)) ? mysqli_real_escape_string($conn,  $data ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

     $data = preg_replace( '/<(.)s(.)c(.)r(.)i(.)p(.)t/i', '', $data );
     $status = preg_replace( '/<(.)s(.)c(.)r(.)i(.)p(.)t/i', '', $status );
     $ilosc = preg_replace( '/<(.)s(.)c(.)r(.)i(.)p(.)t/i', '', $ilosc );
     $rodzajTowaru = preg_replace( '/<(.)s(.)c(.)r(.)i(.)p(.)t/i', '', $rodzajTowaru );
     $kontrahent = preg_replace( '/<(.)s(.)c(.)r(.)i(.)p(.)t/i', '', $kontrahent );

    $sql = "INSERT INTO zamowienia (Kontrahent, RodzajTowaru, Ilosc, Status, Data) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $kontrahent, $rodzajTowaru, $ilosc, $status, $data);
    if ($stmt->execute()) {
        echo "Dodano nowy rekord";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    } 
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $kontrahent = $_POST['kontrahent'];
        $rodzajTowaru = $_POST['rodzajTowaru'];
        $ilosc = $_POST['ilosc'];
        $status = $_POST['status'];
        
        $sql = "UPDATE zamowienia SET Kontrahent=?, RodzajTowaru=?, Ilosc=?, Status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $kontrahent, $rodzajTowaru, $ilosc, $status, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        
        $stmt->close();

    } 
    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM zamowienia WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM zamowienia";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        #logo {
            width: 100px;
            height: auto;
        }

        h2 {
            text-align: center;
        }

        form {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Dashboard</h2>
    <form action="" method="post">
        <label for="kontrahent">Kontrahent:</label><br>
        <input type="text" id="kontrahent" name="kontrahent" required><br>
        <label for="rodzajTowaru">Rodzaj Towaru:</label><br>
        <input type="text" id="rodzajTowaru" name="rodzajTowaru" required><br>
        <label for="ilosc">Ilość (szt):</label><br>
        <input type="number" id="ilosc" name="ilosc" required><br>
        <label for="status">Status:</label><br>
        <input type="text" id="status" name="status" required><br><br>
        <input type="submit" name="submit" value="Add Order">
    </form>
    
    <h3>Orders:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Kontrahent</th>
            <th>Rodzaj Towaru</th>
            <th>Ilość (szt)</th>
            <th>Status</th>
            <th>Data</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["Kontrahent"]."</td>";
                echo "<td>".$row["RodzajTowaru"]."</td>";
                echo "<td>".$row["Ilosc"]."</td>";
                echo "<td>".$row["Status"]."</td>";
                echo "<td>".$row["Data"]."</td>";
                echo "<td>
                        <form action='' method='post'>
                            <input type='hidden' name='id' value='".$row["id"]."'>
                            <input type='text' name='kontrahent' value='".$row["Kontrahent"]."'>
                            <input type='text' name='rodzajTowaru' value='".$row["RodzajTowaru"]."'>
                            <input type='number' name='ilosc' value='".$row["Ilosc"]."'>
                            <input type='text' name='status' value='".$row["Status"]."'>
                            <input type='submit' name='edit' value='Edit'>
                            <input type='submit' name='delete' value='Delete'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>0 results</td></tr>";
        }
        mysqli_close($conn);
        ?>
    </table>

</body>


</html>
