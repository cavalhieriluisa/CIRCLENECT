<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simbiose_industrial";

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Conexão falhou: " . $connection->connect_error);
}

$search = isset($_POST['search']) ? $connection->real_escape_string($_POST['search']) : '';

$query = "SELECT pu.*, m.telefone AS telefone_material FROM pesquisa_unificada pu
          LEFT JOIN materiais m ON pu.email = m.email";

if (!empty($search)) {
    $query .= " WHERE pu.company_name LIKE '%$search%' 
                OR pu.nome_material LIKE '%$search%' 
                OR pu.categoria LIKE '%$search%' 
                OR pu.municipio LIKE '%$search%' 
                OR pu.estado LIKE '%$search%'";
}

$result = $connection->query($query);

if ($result->num_rows > 0) {
    echo "<table>
            <thead>
              <tr>
                <th>Empresa</th><th>Responsável</th><th>Município</th><th>Estado</th><th>Email</th><th>Material</th><th>Quantidade</th><th>Unidade</th><th>Descrição</th><th>Categoria</th><th>Disponibilidade</th><th>Preço</th><th>Interesse</th>
              </tr>
            </thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        $curtido = $row['notificacoes'] == 1 ? 'curtido' : '';
        echo "<tr>
                <td>" . htmlspecialchars($row['company_name']) . "</td>
                <td>" . htmlspecialchars($row['responsavel']) . "</td>
                <td>" . htmlspecialchars($row['municipio']) . "</td>
                <td>" . htmlspecialchars($row['estado']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['nome_material']) . "</td>
                <td>" . htmlspecialchars($row['quantidade']) . "</td>
                <td>" . htmlspecialchars($row['unidade']) . "</td>
                <td>" . htmlspecialchars($row['descricao']) . "</td>
                <td>" . htmlspecialchars($row['categoria']) . "</td>
                <td>" . htmlspecialchars($row['disponibilidade']) . "</td>
                <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                 <td>
                            <button class='like-btn $curtido' data-id='" . $row['id'] . "' onclick='toggleCurtida(this)'>" . ($curtido ? '❤' : '♡') . "</button>
                          </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>Nenhum resultado encontrado.</p>";
}

$connection->close();
?>
