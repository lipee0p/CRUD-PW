<?php
// ============================================================
// CONEXÃO COM O BANCO DE DADOS
// ============================================================

function getConexao(): PDO {
    $host   = 'localhost';
    $banco  = 'style-barber';
    $usuario = 'root';
    $senha  = '';  // ← tente vazio primeiro; se não funcionar, coloque 'local'

    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$banco};charset=utf8mb4",
            $usuario,
            $senha
        );

        // Mostra erros do banco como exceções PHP
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retorna arrays associativos por padrão
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOException $e) {
        // Em produção, nunca mostre o erro real ao usuário
        die('Erro de conexão: ' . $e->getMessage());
    }
}