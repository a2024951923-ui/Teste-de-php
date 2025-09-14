<?php

session_start();//inica seção

//confere tipo de usuario
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'aluno') {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$aluno_id = $_SESSION['usuario_id'];
$turma_aluno = $_SESSION['usuario_turma'];

//busca as atividades da turma do aluno
$sql_atividades = "SELECT titulo, descricao, data_criacao FROM atividades WHERE id_turma = ?";
$stmt_atividades = $pdo->prepare($sql_atividades);
$stmt_atividades->execute([$turma_aluno]); //executa comando sql

//busca as linhas e retorna array
$atividades = $stmt_atividades->fetchAll(PDO::FETCH_ASSOC);

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
        <!-- htmlspecialchars tira caractere especial-->
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
        <!--Exibição -->
        <p>Sua turma: <strong><?php echo htmlspecialchars($turma_aluno); ?></strong></p>

        <hr>

        <h2>Suas Atividades</h2>

        <?php if (!empty($atividades)): ?>
            <div class="atividades-list">
                <?php foreach ($atividades as $atividade): ?>
                    <div class="atividade-card">
                        <h3><?php echo htmlspecialchars($atividade['titulo']); ?></h3>
                        <p><strong>Data:</strong> <?php echo htmlspecialchars($atividade['data_criacao']); ?></p>
                        <p><?php echo htmlspecialchars($atividade['descricao']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma atividade encontrada</p>
        <?php endif; ?>

        <a href="logout.php">Sair</a>
    </div>
</body>
</html>