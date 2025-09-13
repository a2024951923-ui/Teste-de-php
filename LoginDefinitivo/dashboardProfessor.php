<?php
session_start();

//confere professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

$nome_professor = $_SESSION['usuario_nome'];
$id_professor = $_SESSION['usuario_id'];
$mensagem = ''; //feedback ao professor

//logica para adicionar uma nova atividade
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'atividade') {

    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $id_turma = trim($_POST['id_turma']);

    //inserir banco de dados
    if (!empty($titulo) && !empty($id_turma)) {
        $sql_insert = "INSERT INTO atividades (titulo, descricao, id_professor, id_turma) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        if ($stmt_insert->execute([$titulo, $descricao, $id_professor, $id_turma])) {
            $mensagem = "Atividade adicionada com sucesso!";
        }
        else {
            $mensagem = "Erro ao adicionar atividade.";
        }
    }
    else {

        $mensagem = "Por favor, preencha todos os campos.";
    }
}

//logica para registrar um novo aluno
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'aluno') {

    $nome_aluno = trim($_POST['nome_aluno']);
    $email_aluno = trim($_POST['email_aluno']);
    $senha_aluno = trim($_POST['senha_aluno']);
    $turma_aluno = trim($_POST['turma_aluno']);
    $tipo_aluno = 'aluno';

    if (!empty($nome_aluno) && !empty($email_aluno) && !empty($senha_aluno) && !empty($turma_aluno)) {

        $verifica_email = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica_email->execute([$email_aluno]);


        if ($verifica_email->fetch()) {
            $mensagem = "Erro: O e-mail já está em uso.";
        }
        else {
            //criptografar senha
            $hash = password_hash($senha_aluno, PASSWORD_DEFAULT);

            //insert
            $sql_insert = "INSERT INTO usuarios (nome, email, senha, tipo, turma) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            if ($stmt_insert->execute([$nome_aluno, $email_aluno, $hash, $tipo_aluno, $turma_aluno])) {
                $mensagem = "Aluno " . htmlspecialchars($nome_aluno) . " cadastrado com sucesso!";
            }
            else {
                $mensagem = "Erro ao cadastrar aluno.";
            }
        }
    }
    else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}

//busca todos os alunos para exibição
$sql_alunos = "SELECT id, nome, email, turma FROM usuarios WHERE tipo = 'aluno' ORDER BY turma, nome ASC";

//consulta SQL
$stmt_alunos = $pdo->query($sql_alunos);
$alunos = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Professor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <h1>Bem-vindo(a), Professor(a) <?php echo htmlspecialchars($nome_professor); ?>!</h1>
        <p>Esta é a sua área.</p>

        <p><a href="logout.php">Sair</a></p>


        <hr>
        
        <!-- Estilo com IA (nao consegui aprender CSS a tempo :( ) -->
        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <h2>Adicionar Novo Aluno</h2>
        <form action="dashboardProfessor.php" method="POST">
            <input type="hidden" name="form_type" value="aluno">
            <label for="nome_aluno">Nome do Aluno:</label>
            <input type="text" id="nome_aluno" name="nome_aluno" required>

            <label for="email_aluno">Email do Aluno:</label>
            <input type="email" id="email_aluno" name="email_aluno" required>

            <label for="senha_aluno">Senha do Aluno:</label>
            <input type="password" id="senha_aluno" name="senha_aluno" required>
            
            <label for="turma_aluno">Turma do aluno:</label>
            <input type="text" id="turma_aluno" name="turma_aluno" required>

            <input type="submit" value="Cadastrar Aluno">
        </form>

        <hr>

        <h2>Adicionar Nova Atividade</h2>
        <form action="dashboardProfessor.php" method="POST">
            <input type="hidden" name="form_type" value="atividade">
            <label for="titulo">Título da Atividade:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" placeholder="Ex: Atividade de PDO"></textarea>

            <label for="id_turma">Turma:</label>
            <input type="text" id="id_turma" name="id_turma" placeholder="Ex: 3A" required>

            <input type="submit" value="Salvar Atividade">
        </form>

        <hr>

        <h2>Alunos Cadastrados</h2>
        <!-- Tabela HTML (chato) -->
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Turma</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alunos)): ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">Nenhum aluno cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>