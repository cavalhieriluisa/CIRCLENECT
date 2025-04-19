<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simbiose_industrial";

// Conexão com o banco
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Falha na conexão: " . $connection->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome_material'];
$quantidade = $_POST['quantidade'];
$unidade = $_POST['unidade'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];
$disponibilidade = $_POST['disponibilidade'];
$preco = $_POST['preco'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

$imagem_path = null;

// Upload da imagem (se enviado)
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $pasta_destino = "../uploads/";
    if (!file_exists($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    $nome_arquivo = uniqid() . "-" . basename($_FILES['imagem']['name']);
    $caminho_completo = $pasta_destino . $nome_arquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
        $imagem_path = $caminho_completo;
    } else {
        echo "Erro ao fazer upload da imagem.";
        exit;
    }
}

// Inserir os dados no banco
$stmt = $connection->prepare("INSERT INTO materiais (nome, quantidade, unidade, descricao, categoria, disponibilidade, preco, telefone, email, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissssdsss", $nome, $quantidade, $unidade, $descricao, $categoria, $disponibilidade, $preco, $telefone, $email, $imagem_path);

if ($stmt->execute()) {
    header("Location: ../materiais.php"); // Redireciona após adicionar
    exit;
} else {
    echo "Erro ao inserir material: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>
