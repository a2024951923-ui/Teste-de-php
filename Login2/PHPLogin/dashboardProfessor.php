<?php
session_start();

// Verifica se o usuário está logado E se é um professor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'professor') {
    header("Location: login.php"); // Se não for, expulsa para o login
    exit();
}

require_once 'db_connect.php';

// Pega o nome do professor da sessão para personalizar a página
$nome_professor = $_SESSION['user_name'];

// Lógica para adicionar atividade
$mensagem_atividade = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $turma_id = trim($_POST['turma_id']);
    $id_professor = $_SESSION['user_id'];

    if (!empty($titulo) && !empty($turma_id)) {
        $sql_insert = "INSERT INTO atividades (titulo, descricao, id_professor, id_turma) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conexao->prepare($sql_insert);
        $stmt_insert->bind_param("ssis", $titulo, $descricao, $id_professor, $turma_id);
        if ($stmt_insert->execute()) {
            $mensagem_atividade = "Atividade adicionada com sucesso!";
        } else {
            $mensagem_atividade = "Erro ao adicionar atividade.";
        }
        $stmt_insert->close();
    }
}


// Lógica para buscar os alunos cadastrados
$sql_alunos = "SELECT id, nome, email, turma FROM usuarios WHERE tipo = 'aluno' ORDER BY nome ASC";
$resultado_alunos = $conexao->query($sql_alunos); // Query simples, sem input do usuário, então é segura

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
        <h1>Bem-vindo, Professor <?php echo htmlspecialchars($nome_professor); ?>!</h1>
        <p>Esta é a sua área administrativa. <a href="logout.php">Sair</a></p>
        
        <hr>

        <h2>Adicionar Nova Atividade</h2>
        <?php if (!empty($mensagem_atividade)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($mensagem_atividade); ?></p>
        <?php endif; ?>
        <form action="dashboardProfessor.php" method="POST">
            <label for="titulo">Título da Atividade:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>

            <label for="turma_id">Para a Turma:</label>
            <input type="text" id="turma_id" name="turma_id" placeholder="Ex: 3A" required>

            <input type="submit" value="Salvar Atividade">
        </form>

        <hr>

        <h2>Alunos Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Turma</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado_alunos && $resultado_alunos->num_rows > 0): ?>
                    <?php while($aluno = $resultado_alunos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">Nenhum aluno cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>