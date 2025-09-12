<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'aluno') {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// 1. Buscar as informações do aluno, incluindo a turma
$sql_aluno = "SELECT turma FROM usuarios WHERE id = ?";
$stmt_aluno = $conexao->prepare($sql_aluno);
$stmt_aluno->bind_param("i", $user_id);
$stmt_aluno->execute();
$result_aluno = $stmt_aluno->get_result();
$aluno = $result_aluno->fetch_assoc();

if (!$aluno) {
    // Se não encontrar o aluno, destrói a sessão e volta para o login
    session_destroy();
    header("Location: login.php");
    exit;
}
$turma_aluno = $aluno['turma'];
$stmt_aluno->close();

// 2. Buscar as atividades da turma do aluno
$sql_atividades = "SELECT titulo, descricao, data_criacao FROM atividades WHERE id_turma = ? ORDER BY data_criacao DESC";
$stmt_atividades = $conexao->prepare($sql_atividades);
$stmt_atividades->bind_param("s", $turma_aluno);
$stmt_atividades->execute();
$result_atividades = $stmt_atividades->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Sua turma: <strong><?php echo htmlspecialchars($turma_aluno); ?></strong></p>
        <p><a href="logout.php">Sair</a></p>

        <hr>

        <h2>Suas Atividades</h2>

        <?php if ($result_atividades->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data de Publicação</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($atividade = $result_atividades->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($atividade['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($atividade['descricao']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($atividade['data_criacao'])); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma atividade encontrada para a sua turma.</p>
        <?php endif; ?>
        <?php 
            $stmt_atividades->close(); 
            $conexao->close();
        ?>
    </div>
</body>
</html>