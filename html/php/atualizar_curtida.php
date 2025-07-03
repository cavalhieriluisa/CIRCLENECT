<?php
session_start();
include 'config.php';

// Garante que os valores sejam inteiros
$id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : null;
$id_notificacao = isset($_POST['id']) ? intval($_POST['id']) : null;
$curtido = isset($_POST['curtido']) ? intval($_POST['curtido']) : null;

if (!$id_usuario || !$id_notificacao) {
    echo "Erro: dados incompletos.";
    exit();
}

if ($curtido === 1) {
    // Curtir
    $stmt = $connection->prepare("INSERT IGNORE INTO curtidas (id_usuario, id_notificacao) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_usuario, $id_notificacao);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "erro ao curtir: " . $stmt->error;
    }

} else {
    // 1. Remover curtida
    $stmt1 = $connection->prepare("DELETE FROM curtidas WHERE id_usuario = ? AND id_notificacao = ?");
    $stmt1->bind_param("ii", $id_usuario, $id_notificacao);
    $ok1 = $stmt1->execute();

    // 2. Remover notificação associada
    $stmt2 = $connection->prepare("DELETE FROM notificacoes WHERE id = ?");
    $stmt2->bind_param("i", $id_notificacao);
    $ok2 = $stmt2->execute();

    if ($ok1 && $ok2) {
        echo "success";
    } else {
        echo "erro ao remover: " . $stmt1->error . ' | ' . $stmt2->error;
    }
}
?>
