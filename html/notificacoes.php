<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/essencial.css">
    <link rel="icon" type="image/png" href="../img/logo2.png">
    <title>CIRCLENECT</title>
    <style>
            #mobile_btn {
      display: none;
      background: none;
      border: none;
      font-size: 24px;
      color: white;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      #nav_list {
        display: none;
        flex-direction: column;
        width: 100%;
      }

      #nav_list.active {
        display: flex;
      }

      #mobile_btn {
        display: block;
      }
    }
    section {
            height: 100vh; 
            font-size: larger;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            text-align: center;
        }

        #resultado {
  margin-top: 30px;
  width: 100%;
  overflow-x: auto;
}

#resultado table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 0.95rem;
}

#resultado th, #resultado td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
  word-break: break-word;
}

#resultado th {
  background-color: #007bff;
  color: white;
  position: sticky;
  top: 0;
  z-index: 1;
}

#resultado tr:nth-child(even) {
  background-color: #f9f9f9;
}

#resultado tr:hover {
  background-color: #f1f1f1;
}

    </style>
</head>
<body>
    <header>
        <nav id="navbar">
            <img src="../img/logo.png" alt="Logo" id="nav_logo" style="height: 100px;">
            <span style="font-size: 1.5rem; color: antiquewhite;">CIRCLENECT</span>

            <ul id="nav_list">
                <li class="nav-item"><a href="telaprincipal.php">Início</a></li>
                <li class="nav-item"><a href="materiais.php">Materiais</a></li>
                <li class="nav-item"><a href="notificacoes.php">Notificações</a></li>
                <li class="nav-item"><a href="biblioteca.html">Biblioteca de Resíduos</a></li>
            </ul>

            <button id="login_button" class="btn-default">
                <a href="index.html" style="color: inherit; text-decoration: none;">Sair</a>
            </button>

            <button id="mobile_btn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </nav>

        <div id="mobile_menu">
            <ul id="mobile_nav_list">
                <li class="nav-item"><a href="telaprincipal.php">Início</a></li>
                <li class="nav-item"><a href="materiais.php">Materiais</a></li>
                <li class="nav-item"><a href="notificacoes.php">Notificações</a></li>
                <li class="nav-item"><a href="biblioteca.html">Biblioteca de Resíduos</a></li>
            </ul>
            <button id="login_button" class="btn-default">
                <a href="index.html" style="color: inherit; text-decoration: none;">Sair</a>
            </button>
        </div>
    </header>


 
    <main id="content">
        <section id="busca">
        <div class="container">
            <h3>Encontre Notificações</h3>
            <div id="resultado"></div> 
        <table>
            
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
$query = "SELECT n.*, m.telefone AS telefone_material FROM notificacoes n
          LEFT JOIN materiais m ON n.email = m.email 
          WHERE n.company_name LIKE '%$search%' 
          OR n.nome_material LIKE '%$search%' 
          OR n.categoria LIKE '%$search%' 
          OR n.municipio LIKE '%$search%' 
          OR n.estado LIKE '%$search%'";

$result = $connection->query($query);

if ($result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>CNPJ</th>
                    <th>Responsável</th>
                    <th>Telefone</th>
                    <th>Município</th>
                    <th>Estado</th>
                    <th>Email</th>
                    <th>Material</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Disponibilidade</th>
                    <th>Preço</th>
                    <th>Interesse</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($row = $result->fetch_assoc()) {
        $curtido = $row['notificacoes'] == 1 ? 'curtido' : '';
        echo "<tr>
                <td>" . htmlspecialchars($row['company_name']) . "</td>
                <td>" . htmlspecialchars($row['cnpj']) . "</td>
                <td>" . htmlspecialchars($row['responsavel']) . "</td>
                <td>" . htmlspecialchars($row['telefone_empresa']) . "</td>
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
                <td><button class='like-btn $curtido' data-id='" . $row['id'] . "'>❤</button></td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>Nenhum resultado encontrado.</p>";
}

$connection->close();
?>

<script>
document.getElementById("buscar").addEventListener("click", function() {
    var searchTerm = document.getElementById("campoBusca").value.trim(); // Pega o valor do campo de busca

    if (searchTerm.length > 0) {
        fetch('php/buscar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'search=' + encodeURIComponent(searchTerm) // Envia o valor da pesquisa
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("resultado").innerHTML = data; // Atualiza o conteúdo da página com os resultados
        })
        .catch(error => console.error('Erro:', error));
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.dataset.id;
            let isLiked = this.classList.contains('curtido'); // Verifica se já foi curtido
            let newStatus = isLiked ? 0 : 1; // Alterna entre curtido e não curtido

            this.disabled = true; // Evita cliques repetidos rápidos

            fetch('atualizar_curtida.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&curtido=${newStatus}`
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    this.classList.toggle('curtido'); // Atualiza visualmente
                } else {
                    console.error("Erro ao atualizar curtida:", data);
                }
            })
            .catch(error => console.error("Erro na requisição:", error))
            .finally(() => {
                this.disabled = false; // Reativa o botão após resposta
            });
        });
    });
});
</script>
            </tbody>
        </table>
        </div>
        </section>
    </main>
    <footer>
        <div id="footer_items">
            <span id="copyright">
                &copy 2025 Simbiose Industrial
            </span>

            <div class="social-media-buttons">
                <a href="https://github.com/cavalhieriluisa">
                    <i class="fa-brands fa-github"></i>
                </a>
            </div>
        </div>
    </footer>
    <script src="../html/javascript/script.js"></script>
    <script>
        document.getElementById("mobile_btn").addEventListener("click", function() {
            document.getElementById("mobile_menu").classList.toggle("active");
        });
    </script>
</body>
</html>
