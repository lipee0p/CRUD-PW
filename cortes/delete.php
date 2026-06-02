<?php
// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cortes WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: read.php?status=deleted');
        exit();
    } catch (PDOException $e) {
        header('Location: read.php?status=error');
        exit();
    }
} else {
    header('Location: read.php?status=error');
    exit();
}
?>
