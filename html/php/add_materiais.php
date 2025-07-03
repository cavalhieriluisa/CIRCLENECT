<?php
session_start(); // Inicia a sessão para acessar $_SESSION

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "circlenect";

// Conexão com o banco
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Falha na conexão: " . $connection->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não autenticado.");
}
$id_usuario = $_SESSION['id_usuario'];

// Busca telefone e email do usuário autenticado
$sql = "SELECT email, telefone FROM usuarios WHERE id = ?";
$stmt_usuario = $connection->prepare($sql);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result = $stmt_usuario->get_result();

if ($result->num_rows === 0) {
    die("Usuário não encontrado.");
}
$usuario = $result->fetch_assoc();
$email = $usuario['email'];
$telefone = $usuario['telefone'];
$stmt_usuario->close();

// Dados do formulário
$nome = $_POST['nome_material'];
$quantidade = $_POST['quantidade'];
$unidade = $_POST['unidade'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];
$disponibilidade = $_POST['disponibilidade'];
$preco = $_POST['preco'];

$imagem_path = null;

// Upload da imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $pasta_destino = "../uploads/";
    if (!file_exists($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    $nome_arquivo = uniqid() . "-" . basename($_FILES['imagem']['name']);
    $caminho_completo = $pasta_destino . $nome_arquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
        $imagem_path = "uploads/" . $nome_arquivo;
    } else {
        echo "Erro ao fazer upload da imagem.";
        exit;
    }
}

// Inserção no banco
$stmt = $connection->prepare("INSERT INTO materiais (id_usuario, nome, quantidade, unidade, descricao, categoria, disponibilidade, preco, telefone, email, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isissssdsss", $id_usuario, $nome, $quantidade, $unidade, $descricao, $categoria, $disponibilidade, $preco, $telefone, $email, $imagem_path);

if ($stmt->execute()) {
    header("Location: ../materiais.php");
    exit;
} else {
    echo "Erro ao inserir material: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>
