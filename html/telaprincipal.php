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
?>

<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/essencial.css" />
  <link rel="icon" type="image/png" href="../img/logo2.png" />
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
  #mapa_interativo {
    width: 100%;
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #mapa_interativo iframe {
    width: 100%;
    height: 100%;
    border: none;
  }
</style>

</head>
<body>
  <header>
    <nav id="navbar">
      <img src="../img/logo.png" alt="Logo" id="nav_logo" style="height: 100px;" />
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

        <h3>Encontre Empresas ou Materiais</h3>
       <form method="POST" style="margin-bottom: 20px;">
        <input type="text" name="search" id="campoBusca" placeholder="Buscar por empresa, material, município..." style="padding: 8px; width: 70%;">
        <button type="submit" id="buscar" class="btn">Buscar</button>
    </form>
        <div id="resultado">
          <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "circlenect";

          $connection = new mysqli($servername, $username, $password, $dbname);
          if ($connection->connect_error) {
              die("Conexão falhou: " . $connection->connect_error);    
          }

          $search = isset($_POST['search']) ? $connection->real_escape_string($_POST['search']) : '';
$query = "SELECT * FROM pesquisa_unificada";

if (!empty($search)) {
    $query .= " WHERE 
        company_name LIKE '%$search%' 
        OR nome_material LIKE '%$search%' 
        OR categoria LIKE '%$search%' 
        OR municipio LIKE '%$search%' 
        OR estado LIKE '%$search%'";
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
    $id_material = $row['id'];
$sqlCurtida = "SELECT 1 FROM curtidas WHERE id_usuario = $id_usuario AND id_material = $id_material";
$resultCurtida = $connection->query($sqlCurtida);
$curtido = $resultCurtida->num_rows > 0 ? 'curtido' : '';

    $icone = $curtido ? '❤' : '♡';

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
  <button class='like-btn $curtido' data-id='{$row['id']}' onclick='toggleCurtida(this)'>
    " . ($curtido ? '❤' : '♡') . "
  </button>
</td>
          </tr>";
}

              echo "</tbody></table>";
          } else {
              echo "<p>Nenhum resultado encontrado.</p>";
          }

          $connection->close();
          ?>
        </div>
      </div>
    </section>

    <section id="mapa">
      <h3>Mapa Interativo</h3>
      <div id="mapa_interativo">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3656.954143383376!2d-46.63330868502168!3d-23.55051998468239!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59c6c46f1fb9%3A0x39d1c74dfc52ec3!2sSão%20Paulo%2C%20SP!5e0!3m2!1spt-BR!2sbr!4v1706800000000" loading="lazy" allowfullscreen></iframe>
      </div>
    </section>
  </main>

  <footer>
    <div id="footer_items">
      <span>&copy; 2025 CIRCLENECT</span>
      <div class="social-media-buttons">
        <a href="https://github.com/cavalhieriluisa"><i class="fa-brands fa-github"></i></a>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById("mobile_btn").addEventListener("click", function () {
      document.getElementById("mobile_menu").classList.toggle("active");
    });

    document.getElementById("buscar").addEventListener("click", function () {
      var searchTerm = document.getElementById("campoBusca").value.trim();
      if (searchTerm.length > 0) {
        fetch('php/buscar.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'search=' + encodeURIComponent(searchTerm)
        })
        .then(response => response.text())
        .then(data => {
          document.getElementById("resultado").innerHTML = data;
        })
        .catch(error => console.error('Erro:', error));
      }
    });
function toggleCurtida(button) {
  const id = button.getAttribute('data-id');
  const donoId = button.getAttribute('data-dono');
  const estaCurtido = button.classList.contains('curtido');
  const status = estaCurtido ? 0 : 1;

  fetch('php/atualizar_notificacao.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&status=${status}&curtido_por=${donoId}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.sucesso) {
      button.classList.toggle('curtido');
      button.innerText = button.classList.contains('curtido') ? '❤' : '♡';
    } else {
      alert('Erro: ' + data.mensagem);
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    alert('Erro ao processar curtida.');
  });
}



  </script>
</body>
</html>
