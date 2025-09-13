<?php

session_start();

require_once "db_connect.php"; //conexao db

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);//trim confere o ultimo espaço do email
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT id, nome, senha, tipo, turma FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    //confere informações
    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nome"] = $usuario["nome"];
        $_SESSION["usuario_email"] = $usuario["email"];
        $_SESSION["usuario_tipo"] = $usuario["tipo"];
        $_SESSION["usuario_turma"] = $usuario["turma"];

        //redireciona com base no tipo de usuário
        if ($usuario["tipo"] == 'professor') {
            header("Location: dashboardProfessor.php");
        }
        else {
            header("Location: dashboardAluno.php");
        }
        exit;
    }
    else {
        $erro = "Email ou senha incorretos";
    }
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

<form method="POST">
    <label for="email">Digite seu email:</label>
    <input type="email" id="email" name="email" placeholder="Ex: fulano@gmail.com" required> <!--colocar o fantsama na caixa de texto-->

    <label for="senha">Digite sua senha:</label>
    <input type="password" id="senha" name="senha" placeholder="Digite sua senha aqui" required>

    <hr>

    <input type="submit" value="Entrar">
</form>

        <!-- vazio -->
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

    </div>
</body>
</html>