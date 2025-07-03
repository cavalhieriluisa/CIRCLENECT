<?php
session_start();

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "circlenect";

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão.']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'] ?? null;
$id_material = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id_usuario || !$id_material || !is_numeric($status)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos.']);
    exit();
}

// Verifica se o material tem notificação ativada
$sqlNotif = "SELECT * FROM pesquisa_unificada WHERE id = ?";
$stmt = $connection->prepare($sqlNotif);
$stmt->bind_param("i", $id_material);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();

if (!$material) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Material não encontrado.']);
    exit();
}

$id_dono = $material['id_usuario'];
$notificacoes = $material['notificacoes'];

// Se status = 1 (curtir)
if ($status == 1) {
    // Verifica se já curtiu
    $check = $connection->prepare("SELECT * FROM curtidas WHERE id_usuario = ? AND id_material = ?");
    $check->bind_param("ii", $id_usuario, $id_material);
    $check->execute();
    $existe = $check->get_result()->num_rows > 0;

    if (!$existe) {
        // Se a notificação estiver ativada pelo dono, cria notificação e salva ID
        $id_notificacao = null;
        if ($notificacoes == 1) {
            $sqlNotif = "INSERT INTO notificacoes (
                id_referencia, empresa, material, data_interesse, company_name, cnpj, responsavel,
                telefone_empresa, municipio, estado, email, nome_material, quantidade, unidade,
                descricao, categoria, disponibilidade, preco, notificacoes, id_usuario
            ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $connection->prepare($sqlNotif);
            $stmt->bind_param("issssssssssssdsssii",
                $material['id'], $material['company_name'], $material['nome_material'],
                $material['company_name'], $material['cnpj'], $material['responsavel'],
                $material['telefone'], $material['municipio'], $material['estado'],
                $material['email'], $material['nome_material'], $material['quantidade'],
                $material['unidade'], $material['descricao'], $material['categoria'],
                $material['disponibilidade'], $material['preco'], $material['notificacoes'],
                $id_dono
            );
            $stmt->execute();
            $id_notificacao = $connection->insert_id;
        }

        // Inserir curtida
        $insert = $connection->prepare("INSERT INTO curtidas (id_usuario, id_material, id_notificacao) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $id_usuario, $id_material, $id_notificacao);
        $insert->execute();
    }

    echo json_encode(['sucesso' => true, 'mensagem' => 'Curtida registrada.']);
    exit();
}

// Se status = 0 (remover curtida)
if ($status == 0) {
    $delete = $connection->prepare("DELETE FROM curtidas WHERE id_usuario = ? AND id_material = ?");
    $delete->bind_param("ii", $id_usuario, $id_material);
    $delete->execute();

    echo json_encode(['sucesso' => true, 'mensagem' => 'Curtida removida.']);
    exit();
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Status inválido.']);
?>
