<?php

$servidor = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "atividade"; // Certifique-se que o nome do banco está correto

// Criar a conexão usando o estilo orientado a objetos do mysqli
$conexao = new mysqli($servidor, $usuario_db, $senha_db, $banco);

// Definir o charset da conexão para evitar problemas com acentos
$conexao->set_charset("utf8mb4");

// Verificar se a conexão falhou
if ($conexao->connect_error) {
    // A função die() interrompe a execução do script e exibe uma mensagem.
    // É importante não mostrar detalhes técnicos do erro em um site em produção.
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

?>