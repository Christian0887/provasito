<?php
$host = "sql100.infinityfree.com";
$user = "if0_41743335";
$password = "F58KZ1R5MhqqK0";
$db = "if0_41743335_miosito";

// // --- 1. CONNESSIONE AL DATABASE ---
// $host = "localhost";
// $user = "root"; 
// $pass = "";     
// $db   = "miosito";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


?>