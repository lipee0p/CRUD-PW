<?php
function getConexao(): PDO {
    $host    = 'db';
    $banco   = 'style_barber';
    $usuario = 'style_user';
    $senha   = 'style_pass';

    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$banco};charset=utf8mb4",
            $usuario,
            $senha
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die('Erro de conexão: ' . $e->getMessage());
    }
}