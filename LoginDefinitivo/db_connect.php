
<?php
/*
 * Configurações para a base de dados LOCAL (XAMPP) usando PDO
 */

// Adicione estas linhas para sempre mostrar os erros durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- DADOS DA LIGAÇÃO LOCAL ---
$host = 'localhost';        // Para o XAMPP, use sempre 'localhost' ou '127.0.0.1'
$db   = 'atividade';       // O nome da base de dados que criámos no phpMyAdmin
$user = 'root';            // O utilizador padrão do XAMPP
$pass = '';                // A palavra-passe padrão do XAMPP é vazia
$charset = 'utf8mb4';      // Charset recomendado

// DSN (Data Source Name) - A string de configuração para o PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções do PDO para a ligação
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,            // Retorna os resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                       // Usa 'prepared statements' nativos
];

// Tenta estabelecer a ligação
try {
     // Cria o objeto PDO, que será usado em todos os outros ficheiros
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Se a ligação falhar, interrompe o script e mostra uma mensagem de erro clara
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>