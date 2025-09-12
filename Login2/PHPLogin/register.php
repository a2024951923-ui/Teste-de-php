<?php
// Inclui o arquivo de conexão
require_once "db_connect.php";

$mensagem = ''; // Variável para guardar mensagens de erro ou sucesso

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // trim() remove espaços em branco do início e do fim da string
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $turma = trim($_POST["turma"]); // Campo novo para a turma do aluno

    // Validação simples para ver se os campos não estão vazios
    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($turma)) {
        
        // 1. VERIFICAR SE O E-MAIL JÁ EXISTE (usando Prepared Statements)
        $sql_verifica = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_verifica = $conexao->prepare($sql_verifica);
        $stmt_verifica->bind_param("s", $email);
        $stmt_verifica->execute();
        $stmt_verifica->store_result(); // Armazena o resultado para checar o número de linhas

        if ($stmt_verifica->num_rows > 0) {
            $mensagem = "Erro: Este e-mail já está cadastrado!";
        } else {
            // 2. CRIPTOGRAFAR A SENHA
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            // 3. INSERIR O NOVO USUÁRIO NO BANCO DE DADOS (usando Prepared Statements)
            $sql_insert = "INSERT INTO usuarios (nome, email, senha, tipo, turma) VALUES (?, ?, ?, 'aluno', ?)";
            $stmt_insert = $conexao->prepare($sql_insert);
            // 'ssss' indica que todos os 4 parâmetros são strings
            $stmt_insert->bind_param("ssss", $nome, $email, $hash, $turma);

            if ($stmt_insert->execute()) {
                // Redireciona para o login com uma mensagem de sucesso
                header("Location: login.php?status=sucesso");
                exit();
            } else {
                $mensagem = "Erro ao realizar o cadastro. Tente novamente.";
            }
            $stmt_insert->close();
        }
        $stmt_verifica->close();

    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
    $conexao->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Novo Aluno</h1>

        <?php if (!empty($mensagem)): ?>
            <p class="erro"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            
            <label for="turma">Sua Turma (ex: 3A, 2B):</label>
            <input type="text" id="turma" name="turma" required>

            <input type="submit" value="Cadastrar">
        </form>
        
        <p><a href="login.php">Já tem uma conta? Faça o login</a></p>
    </div>
</body>
</html>