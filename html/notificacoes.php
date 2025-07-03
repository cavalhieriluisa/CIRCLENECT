<!DOCTYPE html>
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "circlenect";

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Conexão falhou: " . $connection->connect_error);
}


$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header('Location: index.html');
    exit();
}

$sql = "SELECT email, telefone FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Usuário não encontrado.");
}

$usuario = $result->fetch_assoc();
$email_usuario = $usuario['email'];
$telefone_usuario = $usuario['telefone'];

$stmt->close();

?>

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
        .container table {
    width: 100%;
    min-width: 1400px; /* Garante largura mínima para scroll */
    table-layout: auto; /* Colunas se ajustam conforme conteúdo */
    border-collapse: separate;
    border-spacing: 0;
    overflow-x: auto;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}

.tabela-scroll {
    overflow-x: auto;
    width: 100%;
}

.container th,
.container td {
    padding: 10px 12px;
    font-size: 13px;
    border-bottom: 1px solid #eee;
    white-space: nowrap; /* Evita quebra de linha */
    text-align: left;
    vertical-align: top;
    word-break: break-word;
}

.container td.email,
.container td.responsavel {
    white-space: normal; /* Permite quebra apenas nessas */
}

.container th {
    background-color: #026b6b;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    position: sticky;
    top: 0;
    z-index: 1;
}

.container tr:nth-child(even) {
    background-color: #f9f9f9;
}

.container tr:hover {
    background-color: #f1faff;
}

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

  #busca {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 10px;
  }

  #campoBusca {
    width: 40%;
    padding: 8px;
    font-size: 1rem;
    border: 2px solid #04bfbf;
    border-radius: 8px;
    outline: none;
    transition: 0.3s;
  }

  #campoBusca:focus {
    border-color: #04bfbf;
    box-shadow: 0 0 8px  #04bfbf;
  }

  #buscar {
    margin-top: 10px;
    padding: 6px 12px;
    font-size: 1rem;
    color: white;
    background-color: #04bfbf;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
  }

  #buscar:hover {
    background-color: #04bfbf;
    transform: scale(1.05);
  }
.like-btn {
    background: none;
    color: #ccc;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
}

.like-btn:hover {
    color: red;
    transform: scale(1.2);
}

.like-btn.curtido {
    color: red;
}

.curtidores {
    font-size: 0.75rem;
    margin-top: 8px;
    color: #333;
}

@media screen and (max-width: 768px) {
    .container table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
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
    
    <form method="POST" style="margin-bottom: 20px;">
        <input type="text" name="search" id="campoBusca" placeholder="Buscar por empresa, material, município..." style="padding: 8px; width: 70%;">
        <button type="submit" id="buscar" class="btn">Buscar</button>
    </form>

    <div id="resultado">
         <div class="tabela-scroll">
    <table>
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
        <tbody>
        <?php
        $search = isset($_POST['search']) ? $connection->real_escape_string($_POST['search']) : '';
     $query = "SELECT DISTINCT id, company_name, cnpj, responsavel, telefone_empresa, municipio, estado, email, nome_material, quantidade, unidade, descricao, categoria, disponibilidade, preco 
          FROM notificacoes 
          WHERE id_usuario = $id_usuario AND (
              company_name LIKE '%$search%' 
              OR nome_material LIKE '%$search%' 
              OR categoria LIKE '%$search%' 
              OR municipio LIKE '%$search%' 
              OR estado LIKE '%$search%'
          )";


        $result = $connection->query($query);

        if ($result && $result->num_rows > 0) {
           while ($row = $result->fetch_assoc()) {
    $id_notificacao = $row['id']; // <-- Defina isso primeiro
    $curtidores = [];

    // Buscar os usuários que curtiram essa notificação
    $sqlCurtidas = "SELECT u.company_name, u.email 
                    FROM curtidas c 
                    JOIN usuarios u ON u.id = c.id_usuario 
                    WHERE c.id_notificacao = $id_notificacao";
    $resCurtidas = $connection->query($sqlCurtidas);


while ($curtidor = $resCurtidas->fetch_assoc()) {
    $curtidores[] = $curtidor['company_name'] . " (" . $curtidor['email'] . ")";
}

$listaCurtidores = implode("<br>", $curtidores);

                $curtidoQuery = "SELECT 1 FROM curtidas WHERE id_usuario = $id_usuario AND id_notificacao = $id_notificacao LIMIT 1";
                $isCurtido = $connection->query($curtidoQuery)->num_rows > 0;
                $curtidoClass = $isCurtido ? 'curtido' : '';

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
                        <td>
    <button class='like-btn $curtidoClass' data-id='$id_notificacao'>❤</button>
    <br>
    <div class='curtidores' style='font-size: 0.8rem; margin-top: 10px; text-align: left;'>
        <strong>Interessados:</strong><br>$listaCurtidores
    </div>
</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='15'>Nenhuma notificação encontrada.</td></tr>";
        }

        $connection->close();
        ?>
        </tbody>
    </table>
    </div>
    </div>
</div>
</section>
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
            
    </main>
    <footer>
        <div id="footer_items">
            <span id="copyright">
                &copy 2025 CIRCLENECT
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
