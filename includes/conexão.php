<?php
$host = "http://localhost/phpmyadmin/index.php?route=/database/structure&db=clinicacare";      // Servidor
$usuario = "root";        // Usuário padrão do XAMPP/WAMP
$senha = "";              // Senha (geralmente vazia no XAMPP)
$banco = "ClinicaCare-Rafael";    // Nome do seu banco de dados

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>