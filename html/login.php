<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="../img/logo2.png">
    <title>CIRCLENECT</title>


    <style>
       #login_button {
    display: block; /* Garante que o botão sempre apareça */
    background-color: #fff;
    border: 1px solid #026b6b;
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 1rem;
}

@media screen and (max-width: 768px) {
    #login_button {
        display: block; /* Mantém visível no mobile */
        width: 100px; /* Ajuste conforme necessário */
        text-align: center;
    }
}
body {
    min-height: 100vh;
    background-color: #E8F5E9;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; /* Centraliza o conteúdo na página */
}

#content {
    width: 100%;
    max-width: 400px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin: auto; /* Mantém centralizado */
}

.section-title {
    font-size: 22px;
    color: #026b6b;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

label {
    text-align: left;
    font-weight: bold;
    font-size: 14px;
    color: #333;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

input:focus {
    border-color:  #026b6b;
    outline: none;
}

button {
    background-color:#04bfbf;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background-color: #026b6b;
}

p {
    font-size: 14px;
}

p a {
    font-weight: bold;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

/* Estrutura para centralizar e manter o footer sempre no final */
.wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: 100%;
    align-items: center;
    justify-content: center;
}



@media screen and (max-width: 768px) {
    #navbar {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    #login_button {
        display: block !important; /* Garante que o botão seja visível */
        width: auto; 
        margin-top: 10px;
    }

    #mobile_menu {
        display: flex; /* Garante que o menu apareça */
        justify-content: center;
        width: 100%;
    }
}
footer {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 10px 0;
}






    </style>
</head>
<body>
    <header>
        <nav id="navbar">
            <img src="../img/logo.png" alt="Logo" id="nav_logo" style="height: 100px;">
            <span style="font-size: 1.5rem; color: antiquewhite;">CIRCLENECT</span>
             <button id="login_button" class="btn-default">
                <a href="index.html" style="color: rgb(40, 39, 39); text-decoration: none;">Inicio</a>
            </button>
        </nav>   

        
    </header>

    <main id="content">
        <h2 class="section-title">Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Email:</label>
            <input type="email" id="username" name="username" required>
            
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
        
        <?php
        session_start();
        require './php/config.php';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = mysqli_real_escape_string($conn, $_POST["username"]);
            $password = $_POST["password"];
            
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    $_SESSION["usuario"] = $user["email"];
                    header("Location: telaprincipal.php");
                    exit();
                } else {
                    echo "<p style='color:red;'>Senha incorreta!</p>";
                }
            } else {
                echo "<p style='color:red;'>Usuário não encontrado!</p>";
            }
            
            $stmt->close();
        }
        $conn->close();
        ?>
        
        <p>Não possui uma conta? <a href="cadastro.html" style="color: #04bfbf; text-decoration: none;">Cadastre-se</a></p>
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
   
</body>
</html>
