<?php

$server = "newton.coltec.ufmg.br";
$username = "a2024951923@teiacoltec.org"; // usuario
$password = "";
$dbname = "atividade"; //nome do seu banco de dados

//Tenta estabelecer conexao com o phpmyadmin
// Senao ele so encerra o processo
try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $username, $password);

    //modo do db
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}