<?php
// ============================================================
// CONEXÃO COM O BANCO DE DADOS
// ============================================================

function getConexao(): PDO {
    $host   = 'db';
    $banco  = 'style_barber';
    $usuario = 'style_user';
    $senha  = 'style_pass';  // ← tente vazio primeiro; se não funcionar, coloque 'local'

    try {
        <?php
$host = 'db'; // Nome do serviço que está no seu docker-compose
$banco = 'style_barber'; // Nome do seu banco de dados
$usuario = 'style_user'; // Mude de 'root' para 'style_user'
$senha = 'style_pass'; // Mude para 'style_pass'

try {
    // Linha do PDO corrigida e limpa
    $pdo = new PDO("mysql:host={$host};dbname={$banco};charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}
        $pdo = new PDO("mysql:host={$host};dbname={$banco};charset=utf8mb4", $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Erro de conexão: ' . $e->getMessage());
    }
