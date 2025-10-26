<?php
// Configurações básicas do banco
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "hack_arena";

// Cria a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica se houve erro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8 — evita erros de acentuação e aumenta a segurança
if (!mysqli_set_charset($conn, 'utf8mb4')) {
    die("Falha ao definir charset: " . mysqli_error($conn));
}
?>
