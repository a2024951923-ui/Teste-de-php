<?php
session_start(); // Sempre no topo do arquivo

require_once "db_connect.php";

$erro = '';

// Verifica se há uma mensagem de status na URL (vindo do cadastro)
if (isset($_GET['status']) && $_GET['status'] == 'sucesso') {
    $sucesso = "Cadastro realizado com sucesso! Faça o login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (!empty($email) && !empty($senha)) {
        // Usa prepared statements para segurança
        $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verifica se a senha digitada corresponde à senha criptografada no banco
            if (password_verify($senha, $usuario['senha'])) {
                // Senha correta, cria a sessão
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nome'];
                $_SESSION['user_type'] = $usuario['tipo'];

                // Redireciona com base no tipo de usuário
                if ($usuario['tipo'] == 'professor') {
                    header("Location: dashboardProfessor.php");
                } else {
                    header("Location: dashboardAluno.php");
                }
                exit();

            } else {
                $erro = "E-mail ou senha incorretos.";
            }
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
        $stmt->close();
    } else {
        $erro = "Preencha todos os campos.";
    }
    $conexao->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($sucesso); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            
            <input type="submit" value="Entrar">
        </form>
        <p><a href="register.php">Não tem uma conta? Cadastre-se</a></p>
    </div>
</body>
</html>