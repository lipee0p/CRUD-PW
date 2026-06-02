<?php
$page_title = 'Editar Corte';
$active_tab = 'cortes';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$error_message = '';
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: read.php?status=error');
    exit();
}

// Busca o corte a ser editado
try {
    $stmt = $pdo->prepare("SELECT * FROM cortes WHERE id = ?");
    $stmt->execute([$id]);
    $corte = $stmt->fetch();

    if (!$corte) {
        header('Location: read.php?status=error');
        exit();
    }
} catch (PDOException $e) {
    $error_message = 'Erro ao buscar corte: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_corte = trim($_POST['nome_corte'] ?? '');
    $valor = floatval(str_replace(',', '.', $_POST['valor'] ?? 0));
    $adicional_freestyle = floatval(str_replace(',', '.', $_POST['adicional_freestyle'] ?? 0));

    if (empty($nome_corte)) {
        $error_message = 'O nome do corte é obrigatório.';
    } elseif ($valor <= 0) {
        $error_message = 'O valor base deve ser maior que zero.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE cortes SET nome_corte = ?, valor = ?, adicional_freestyle = ? WHERE id = ?");
            $stmt->execute([$nome_corte, $valor, $adicional_freestyle, $id]);
            header('Location: read.php?status=updated');
            exit();
        } catch (PDOException $e) {
            $error_message = 'Erro ao salvar no banco de dados: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header do Módulo -->
<div class="mb-8">
    <a href="read.php" class="text-xs font-bold text-gold-500 hover:text-gold-400 uppercase tracking-widest flex items-center gap-2 mb-2 transition-all">
        <i class="fa-solid fa-arrow-left"></i> Voltar para a lista
    </a>
    <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
        <i class="fa-solid fa-pen-to-square text-gold-500"></i>
        Editar Estilo de Corte
    </h1>
    <p class="text-neutral-400 mt-1 text-sm">Atualize os valores ou o nome do corte selecionado.</p>
</div>

<!-- Alertas de Erro -->
<?php if (!empty($error_message)): ?>
    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
        <span class="text-sm font-semibold"><?= htmlspecialchars($error_message) ?></span>
    </div>
<?php endif; ?>

<!-- Formulário -->
<div class="max-w-2xl mx-auto glass-card rounded-2xl p-6 sm:p-8">
    <form action="update.php?id=<?= $id ?>" method="POST" class="space-y-6">
        <!-- Nome do Corte -->
        <div>
            <label for="nome_corte" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Nome do Corte de Cabelo *</label>
            <input type="text" id="nome_corte" name="nome_corte" required value="<?= htmlspecialchars($corte['nome_corte'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Valor Base -->
            <div>
                <label for="valor" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Valor Base (R$) *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm font-bold">R$</span>
                    <input type="number" step="0.01" min="0" id="valor" name="valor" required value="<?= htmlspecialchars($corte['valor'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
                </div>
            </div>

            <!-- Adicional Freestyle -->
            <div>
                <label for="adicional_freestyle" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Adicional Freestyle (R$)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm font-bold">R$</span>
                    <input type="number" step="0.01" min="0" id="adicional_freestyle" name="adicional_freestyle" value="<?= htmlspecialchars($corte['adicional_freestyle'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
                </div>
                <span class="block text-xs text-neutral-500 mt-1">Valor extra caso o cliente adicione desenho/freestyle no corte.</span>
            </div>
        </div>

        <hr class="border-neutral-900 my-6">

        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-2">
            <a href="read.php" class="px-5 py-3 border border-neutral-800 text-neutral-400 hover:text-white rounded-xl text-sm font-semibold transition-all text-center">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold shadow-lg shadow-gold-500/10 hover:shadow-gold-500/20 hover:-translate-y-[1px] transition-all">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
