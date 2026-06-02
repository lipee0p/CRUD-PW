<?php
// Configurações de conexão compatíveis com XAMPP e Docker
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_DATABASE') ?: 'style_barber';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    // Tenta conectar usando as configurações padrão (XAMPP local)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Se falhar e estiver em 'localhost', tenta o fallback para o ambiente Docker ('db')
    if ($host === 'localhost') {
        try {
            $host = 'db';
            $username = 'style_user';
            $password = 'style_pass';
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e2) {
            die("Erro de conexão com o banco de dados (XAMPP & Docker falharam): " . $e2->getMessage());
        }
    } else {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
    }
}
?>
