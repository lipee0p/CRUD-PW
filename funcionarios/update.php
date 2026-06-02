<?php
$page_title = 'Editar Funcionário';
$active_tab = 'funcionarios';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$error_message = '';
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: read.php?status=error');
    exit();
}

// Busca o funcionário atual
try {
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = ?");
    $stmt->execute([$id]);
    $funcionario = $stmt->fetch();

    if (!$funcionario) {
        header('Location: read.php?status=error');
        exit();
    }
} catch (PDOException $e) {
    $error_message = 'Erro ao buscar funcionário: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $funcao = trim($_POST['funcao'] ?? '');
    $data_admissao = $_POST['data_admissao'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $salario = floatval(str_replace(',', '.', $_POST['salario'] ?? 0));

    if (empty($nome) || empty($funcao) || empty($data_admissao) || empty($data_nascimento)) {
        $error_message = 'Todos os campos obrigatórios (*) devem ser preenchidos.';
    } elseif ($salario < 0) {
        $error_message = 'O salário não pode ser um valor negativo.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE funcionarios SET nome = ?, funcao = ?, data_admissao = ?, data_nascimento = ?, salario = ? WHERE id = ?");
            $stmt->execute([$nome, $funcao, $data_admissao, $data_nascimento, $salario, $id]);
            
            header('Location: read.php?status=updated');
            exit();
        } catch (PDOException $e) {
            $error_message = 'Erro ao salvar alterações do funcionário: ' . $e->getMessage();
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
        Editar Funcionário
    </h1>
    <p class="text-neutral-400 mt-1 text-sm">Atualize os dados cadastrais do colaborador selecionado.</p>
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
        <!-- Nome Completo -->
        <div>
            <label for="nome" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Nome Completo *</label>
            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($funcionario['nome'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
        </div>

        <!-- Função/Cargo -->
        <div>
            <label for="funcao" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Função / Cargo *</label>
            <input type="text" id="funcao" name="funcao" required value="<?= htmlspecialchars($funcionario['funcao'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Data de Nascimento -->
            <div>
                <label for="data_nascimento" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Data de Nascimento *</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required value="<?= htmlspecialchars($funcionario['data_nascimento'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
            </div>

            <!-- Data de Admissão -->
            <div>
                <label for="data_admissao" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Data de Admissão *</label>
                <input type="date" id="data_admissao" name="data_admissao" required value="<?= htmlspecialchars($funcionario['data_admissao'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
            </div>
        </div>

        <!-- Salário -->
        <div>
            <label for="salario" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Salário Mensal (R$) *</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm font-bold">R$</span>
                <input type="number" step="0.01" min="0" id="salario" name="salario" required value="<?= htmlspecialchars($funcionario['salario'] ?? '') ?>" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
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
