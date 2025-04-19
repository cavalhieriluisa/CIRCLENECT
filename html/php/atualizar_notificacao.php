<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simbiose_industrial";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Falha na conexão.']);
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? intval($_POST['status']) : 0;

if ($id > 0) {
    $sqlUpdate = "UPDATE pesquisa_unificada SET notificacoes = $status WHERE id = $id";
    if ($conn->query($sqlUpdate)) {

        if ($status == 1) {
            // Recupera os dados do registro curtido
            $sqlSelect = "SELECT * FROM pesquisa_unificada WHERE id = $id";
            $result = $conn->query($sqlSelect);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Busca dados do usuário que publicou o material
                $email = $conn->real_escape_string($row['email']);
                $sqlUsuario = "SELECT * FROM usuarios WHERE email = '$email'";
                $resUsuario = $conn->query($sqlUsuario);
                $usuario = $resUsuario->fetch_assoc();

                // Evita duplicações
                $check = "SELECT * FROM notificacoes WHERE id_referencia = $id";
                $checkResult = $conn->query($check);

                if ($checkResult->num_rows === 0) {
                    $empresa     = $conn->real_escape_string($usuario['company_name']);
                    $material    = $conn->real_escape_string($row['nome_material']);
                    $cnpj        = $conn->real_escape_string($usuario['cnpj']);
                    $responsavel = $conn->real_escape_string($usuario['responsavel']);
                    $telefone    = $conn->real_escape_string($usuario['telefone']);
                    $municipio   = $conn->real_escape_string($usuario['municipio']);
                    $estado      = $conn->real_escape_string($usuario['estado']);
                    $quantidade  = $conn->real_escape_string($row['quantidade']);
                    $unidade     = $conn->real_escape_string($row['unidade']);
                    $descricao   = $conn->real_escape_string($row['descricao']);
                    $categoria   = $conn->real_escape_string($row['categoria']);
                    $disp        = $conn->real_escape_string($row['disponibilidade']);
                    $preco       = $conn->real_escape_string($row['preco']);
                    $id_user     = intval($usuario['id']);

                    // Verifica se a pessoa curtiu o material
                    $curtidoPor = isset($_POST['curtido_por']) ? intval($_POST['curtido_por']) : 0;

                    // Verifica se o material foi curtido por outra pessoa
                    if ($curtidoPor > 0 && $curtidoPor != $id_user) {
                        // Envia a notificação apenas para o usuário que recebeu a curtida
                        $sqlInsert = "INSERT INTO notificacoes (
                            id_referencia, empresa, material, data_interesse, company_name, cnpj,
                            responsavel, telefone_empresa, municipio, estado, email,
                            nome_material, quantidade, unidade, descricao, categoria,
                            disponibilidade, preco, notificacoes, id_usuario
                        ) VALUES (
                            $id, '$empresa', '$material', NOW(), '$empresa', '$cnpj',
                            '$responsavel', '$telefone', '$municipio', '$estado', '$email',
                            '$material', '$quantidade', '$unidade', '$descricao', '$categoria',
                            '$disp', '$preco', 0, $id_user
                        )";

                        $conn->query($sqlInsert);
                    }
                }
            }
        } else {
            // Remove notificação
            $sqlDelete = "DELETE FROM notificacoes WHERE id_referencia = $id";
            $conn->query($sqlDelete);
        }

        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar banco.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
}

$conn->close();
?>
