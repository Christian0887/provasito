<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "miosito";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>