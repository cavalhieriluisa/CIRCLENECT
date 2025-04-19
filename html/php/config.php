<?php
$host = "localhost";
$user = "root"; // Usuário padrão do MySQL (altere se necessário)
$password = ""; // Senha (deixe vazio se não houver)
$dbname = "simbiose_industrial";

// Criar conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
