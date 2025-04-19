<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST["company_name"];
    $cnpj = $_POST["cnpj"];
    $responsavel = $_POST["responsavel"];
    $telefone = $_POST["telefone"];
    $rua = $_POST["rua"];
    $municipio = $_POST["municipio"];
    $estado = $_POST["estado"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Senha criptografada

    // Verifica se o e-mail já existe no banco
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Erro: Este e-mail já está cadastrado!";
    } else {
        $stmt->close();

        // Verifica se o CNPJ já existe no banco
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE cnpj = ?");
        $stmt->bind_param("s", $cnpj);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Erro: Este CNPJ já está cadastrado!";
        } else {
            $stmt->close();

            // Insere o novo usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (company_name, cnpj, responsavel, telefone, rua, municipio, estado, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $company_name, $cnpj, $responsavel, $telefone, $rua, $municipio, $estado, $email, $password);

            if ($stmt->execute()) {
                header("Location: ../login.php"); // Redireciona para login após cadastro
                exit();
            } else {
                echo "Erro no cadastro: " . $stmt->error;
            }
        }
    }

    $stmt->close();
}
$conn->close();
?>
