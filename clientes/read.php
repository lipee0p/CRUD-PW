<?php
$page_title = 'Lista de Agendamentos';
$active_tab = 'clientes';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$agendamentos = [];
$error_message = '';
$success_message = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'created') {
        $success_message = 'Agendamento cadastrado com sucesso!';
    } elseif ($_GET['status'] == 'updated') {
        $success_message = 'Agendamento atualizado com sucesso!';
    } elseif ($_GET['status'] == 'deleted') {
        $success_message = 'Agendamento excluído com sucesso!';
    } elseif ($_GET['status'] == 'error') {
        $error_message = 'Ocorreu um erro ao processar a requisição.';
    }
}

try {
    // Busca os clientes e faz LEFT JOIN com cortes para trazer informações do serviço agendado
    $query = "SELECT c.*, co.nome_corte, co.valor, co.adicional_freestyle 
              FROM clientes c 
              LEFT JOIN cortes co ON c.corte_id = co.id 
              ORDER BY c.horario_agendamento ASC";
    $stmt = $pdo->query($query);
    $agendamentos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Erro ao buscar agendamentos: ' . $e->getMessage();
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header do Módulo -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-gold-500"></i>
            Agendamentos de Clientes
        </h1>
        <p class="text-neutral-400 mt-1 text-sm">Gerencie horários agendados, clientes atendidos e fluxos de pagamentos.</p>
    </div>
    <div>
        <a href="create.php" class="px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Novo Agendamento
        </a>
    </div>
</div>

<!-- Alertas de Feedback -->
<?php if (!empty($success_message)): ?>
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span class="text-sm font-semibold"><?= htmlspecialchars($success_message) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
        <span class="text-sm font-semibold"><?= htmlspecialchars($error_message) ?></span>
    </div>
<?php endif; ?>

<!-- Tabela de Agendamentos -->
<div class="glass-card rounded-2xl overflow-hidden">
    <?php if (empty($agendamentos)): ?>
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-neutral-900 border border-neutral-800 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-calendar-times text-neutral-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">Nenhum agendamento cadastrado</h3>
            <p class="text-neutral-500 text-sm max-w-sm mx-auto mb-6">Comece agendando o horário de um cliente na barbearia.</p>
            <a href="create.php" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 border border-neutral-800 text-gold-500 hover:text-gold-400 rounded-xl text-sm font-semibold transition-all">
                Criar Primeiro Agendamento
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-800 bg-neutral-900/40">
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Horário Agendamento</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Corte Escolhido</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Forma de Pagamento</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60">
                    <?php foreach ($agendamentos as $agenda): ?>
                        <tr class="hover:bg-neutral-900/25 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-semibold text-neutral-500">#<?= $agenda['id'] ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-white"><?= htmlspecialchars($agenda['nome']) ?></td>
                            <td class="px-6 py-4 text-sm text-neutral-300 font-medium">
                                <i class="fa-regular fa-clock mr-1.5 text-gold-500"></i>
                                <?= date('d/m/Y - H:i', strtotime($agenda['horario_agendamento'])) ?>hs
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-300">
                                <?php if ($agenda['corte_id']): ?>
                                    <span class="block font-bold text-white"><?= htmlspecialchars($agenda['nome_corte']) ?></span>
                                    <span class="block text-xs text-neutral-500 mt-0.5">
                                        Total: <strong class="text-emerald-400">R$ <?= number_format($agenda['valor'] + $agenda['adicional_freestyle'], 2, ',', '.') ?></strong>
                                    </span>
                                <?php else: ?>
                                    <span class="text-neutral-500 italic">Não associado</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-neutral-900 border border-neutral-800 text-amber-500">
                                    <?= htmlspecialchars($agenda['forma_pagamento']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <a href="update.php?id=<?= $agenda['id'] ?>" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-neutral-900 hover:bg-neutral-800 text-neutral-400 hover:text-white border border-neutral-800 hover:border-neutral-700 transition-all" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete.php?id=<?= $agenda['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir o agendamento de \'<?= htmlspecialchars($agenda['nome']) ?>\'?')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/20 transition-all" title="Excluir">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
