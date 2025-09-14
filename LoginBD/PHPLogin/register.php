<?php

require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    
    $tipo = 'professor';

    if (!empty($nome) && !empty($email) && !empty($senha)) {
        $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica->execute([$email]);

        if ($verifica->fetch()) {
            echo "Erro ao cadastrar: este email já está em uso.";
        }
        else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $hash, $tipo]);

            echo "Professor cadastrado com sucesso!";
        }
    }
    else {
        echo "Preencha todos os campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Professor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <h1>Cadastro de novo(a) professor(a)</h1>

        <form method="POST">

            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>E-mail:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <hr>

            <input type="submit" value="Cadastrar">
        </form>

        <p><a href="login.php">Já tem conta? Login</a></p>
    </div>
</body>
</html>