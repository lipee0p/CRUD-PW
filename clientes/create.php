<?php
$page_title = 'Cadastrar Novo Agendamento';
$active_tab = 'clientes';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$error_message = '';
$cortes = [];

// Busca a lista de cortes para alimentar o select dinamicamente
try {
    $stmt = $pdo->query("SELECT id, nome_corte, valor, adicional_freestyle FROM cortes ORDER BY nome_corte ASC");
    $cortes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Erro ao buscar catálogo de cortes: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $horario_agendamento = $_POST['horario_agendamento'] ?? '';
    $forma_pagamento = trim($_POST['forma_pagamento'] ?? '');
    $corte_id = !empty($_POST['corte_id']) ? intval($_POST['corte_id']) : null;

    if (empty($nome) || empty($horario_agendamento) || empty($forma_pagamento)) {
        $error_message = 'Todos os campos com asterisco (*) são obrigatórios.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, horario_agendamento, forma_pagamento, corte_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $horario_agendamento, $forma_pagamento, $corte_id]);
            
            header('Location: read.php?status=created');
            exit();
        } catch (PDOException $e) {
            $error_message = 'Erro ao salvar agendamento: ' . $e->getMessage();
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
        <i class="fa-solid fa-calendar-plus text-gold-500"></i>
        Novo Agendamento
    </h1>
    <p class="text-neutral-400 mt-1 text-sm">Agende o horário de um cliente associado a um estilo de corte.</p>
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
    <form action="create.php" method="POST" class="space-y-6">
        <!-- Nome Completo -->
        <div>
            <label for="nome" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Nome do Cliente *</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: Gabriel Santos" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all placeholder:text-neutral-600">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Data e Hora do Agendamento -->
            <div>
                <label for="horario_agendamento" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Data e Hora do Horário *</label>
                <input type="datetime-local" id="horario_agendamento" name="horario_agendamento" required class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
            </div>

            <!-- Forma de Pagamento -->
            <div>
                <label for="forma_pagamento" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Forma de Pagamento *</label>
                <select id="forma_pagamento" name="forma_pagamento" required class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
                    <option value="" disabled selected class="bg-neutral-900">Selecione uma opção</option>
                    <option value="Dinheiro" class="bg-neutral-900">Dinheiro</option>
                    <option value="Pix" class="bg-neutral-900">Pix</option>
                    <option value="Cartão de Crédito" class="bg-neutral-900">Cartão de Crédito</option>
                    <option value="Cartão de Débito" class="bg-neutral-900">Cartão de Débito</option>
                </select>
            </div>
        </div>

        <!-- Corte de Cabelo (Relacionamento elegante) -->
        <div>
            <label for="corte_id" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Corte / Serviço Escolhido</label>
            <?php if (empty($cortes)): ?>
                <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-500 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Nenhum corte cadastrado. <a href="../cortes/create.php" class="font-bold underline text-white">Cadastre um corte primeiro</a> para poder selecioná-lo aqui.</span>
                </div>
            <?php else: ?>
                <select id="corte_id" name="corte_id" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all">
                    <option value="" class="bg-neutral-900">Nenhum corte (Apenas agendamento de horário)</option>
                    <?php foreach ($cortes as $corte): ?>
                        <?php $valor_total = $corte['valor'] + $corte['adicional_freestyle']; ?>
                        <option value="<?= $corte['id'] ?>" class="bg-neutral-900">
                            <?= htmlspecialchars($corte['nome_corte']) ?> (Base: R$<?= number_format($corte['valor'], 2, ',', '.') ?> + Extra: R$<?= number_format($corte['adicional_freestyle'], 2, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>

        <hr class="border-neutral-900 my-6">

        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-2">
            <a href="read.php" class="px-5 py-3 border border-neutral-800 text-neutral-400 hover:text-white rounded-xl text-sm font-semibold transition-all text-center">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold shadow-lg shadow-gold-500/10 hover:shadow-gold-500/20 hover:-translate-y-[1px] transition-all">
                Gravar Registro
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
