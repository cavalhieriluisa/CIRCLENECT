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
    die("Usuário não autenticado.");
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
    <link rel="php" href="php/list_materiais.php">
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

    .container table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

section {
    min-height: 100vh; 
    font-size: larger;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    text-align: center;
}

/* NOVO CSS para tabela */
.container table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    overflow-x: auto;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
  }

  .container th {
    background-color: #026b6b;
    color: #fff;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    position: sticky;
    top: 0;
    z-index: 1;
  }

  .container td {
    padding: 12px;
    font-size: 14px;
    border-bottom: 1px solid #eee;
    word-break: break-word;
    vertical-align: top;
    text-align: center;
  }

  .container tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .container tr:hover {
    background-color: #f1faff;
  }

/* Botões */
.btn_add {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

#open_form_button {
    background-color: #026b6b;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s ease;
}

#open_form_button:hover {
    background-color: #024f4f;
}

.main {
    width: 90%;
    max-width: 600px;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: left;
    margin-bottom: 100px;
}

#material_form label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
    color: #026b6b;
}

#material_form input,
#material_form select,
#material_form textarea {
    width: 100%;
    padding: 12px;
    font-size: 1.1rem;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

#material_form textarea {
    height: 80px;
    resize: none;
}

#material_form button {
    width: 100%;
    padding: 15px;
    margin-top: 15px;
    border: none;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
}

#material_form button[type="submit"] {
    background-color: #026b6b;
    color: white;
}

#material_form button[type="submit"]:hover {
    background-color: #024f4f;
}

.close-button {
    background-color: #d9534f;
    color: white;
}

.close-button:hover {
    background-color: #c9302c;
}

.delete-btn {
    background-color: #d9534f;
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 0.9rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s ease, transform 0.2s ease;
}

.delete-btn:hover {
    background-color: #c9302c;
    transform: scale(1.05);
}

.delete-btn i {
    margin-right: 5px;
}

footer {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 10px 0;
}

/* Botões de like (caso queira reaproveitar o estilo) */
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


    </style>
</head>
<body>
    <header>
        <nav id="navbar">
            <img src="../img/logo.png" alt="Logo" id="nav_logo" style="height: 100px;">
            <span style="font-size: 1.5rem; color: antiquewhite;">CIRCLENECT</span>

            <ul id="nav_list">
                <li class="nav-item">
                    <a href="telaprincipal.php">Início</a>
                </li>
                <li class="nav-item">
                    <a href="materiais.php">Materiais</a>
                </li>
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
        <section>
            <div class="btn_add">
                <button id="open_form_button">Adicionar Material</button>
               </div>
       
               <section class="main" id="material_form_section" style="display: none;">
               <h2>Adicionar Material</h2>
                 <form action="php/add_materiais.php" method="POST" id="material_form" enctype="multipart/form-data">

                <label for="nome_material">Nome do Material:</label>
                <input type="text" id="nome_material" name="nome_material" required>

                <label for="quantidade">Quantidade Disponível:</label>
                <input type="number" id="quantidade" name="quantidade" min="1" required>

                <label for="unidade">Unidade:</label>
                <select id="unidade" name="unidade" required>
                    <option value="kg">Kg</option>
                    <option value="litro">Litro</option>
                </select>

                <label for="descricao">Descrição Detalhada:</label>
                <textarea id="descricao" name="descricao" required></textarea>

                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <option value="plastico">Plástico</option>
                    <option value="papel">Papel</option>
                    <option value="metal">Metal</option>
                </select>

                <label for="disponibilidade">Disponibilidade:</label>
                <select id="disponibilidade" name="disponibilidade" required>
                    <option value="doacao">Doação</option>
                    <option value="troca">Troca</option>
                    <option value="venda">Venda</option>
                </select>

                <label for="preco">Preço por Unidade:</label>
                <input type="number" id="preco" name="preco" min="0" step="0.01">

                <label for="telefone">Telefone para Contato:</label>
<input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone_usuario); ?>" readonly>

<label for="email">Email para Contato:</label>
<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_usuario); ?>" readonly>


                <!-- Campo de imagem adicionado -->
                <label for="imagem">Imagem do Material:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">

                <button type="submit">Adicionar Material</button>
                <button type="button" id="close_form_button" class="close-button">Fechar</button>
                </form>

                </section>
            

        <div class="container">
                <h2>Lista de Materiais</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Disponibilidade</th>
                    <th>Preço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
   
$query = "SELECT * FROM materiais WHERE id_usuario = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantidade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['unidade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
            echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disponibilidade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['preco']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['imagem']) . "' alt='Imagem do Material' style='width: 80px; height: auto; border-radius: 5px;'></td>";
            echo "<td><button class='delete-btn' data-id='" . $row['id'] . "'>Excluir</button></td>";
            echo "</tr>";
        }
        
    } else {
        echo "<tr><td colspan='10'>Nenhum material encontrado</td></tr>";
    }

    $connection->close();
    ?>
            </tbody>
        </table>
        </div>
        </section>

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
        
        document.getElementById("open_form_button").addEventListener("click", function () {
        document.getElementById("material_form_section").style.display = "block";
    });

    document.getElementById("close_form_button").addEventListener("click", function () {
        document.getElementById("material_form_section").style.display = "none";
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".delete-btn");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const materialId = this.getAttribute("data-id");

                if (confirm("Tem certeza que deseja excluir este material?")) {
                    fetch("php/delete_materiais.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + materialId
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            alert("Material excluído com sucesso!");
                            location.reload();
                        } else {
                            alert("Erro ao excluir o material.");
                        }
                    })
                    .catch(error => console.error("Erro:", error));
                }
            });
        });
    });
</script>
<script>
        document.getElementById("mobile_btn").addEventListener("click", function() {
            document.getElementById("mobile_menu").classList.toggle("active");
        });
    </script>

</body>
</html>
