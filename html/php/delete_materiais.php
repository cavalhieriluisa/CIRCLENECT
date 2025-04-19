<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simbiose_industrial";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Conexão falhou: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    
    $query = "DELETE FROM materiais WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Erro ao excluir material.";
    }

    $stmt->close();
}

$connection->close();
?>
