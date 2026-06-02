<?php
$page_title = 'Lista de Usuários';
$active_tab = 'usuarios';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$usuarios = [];
$error_message = '';
$success_message = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'created') {
        $success_message = 'Usuário cadastrado com sucesso!';
    } elseif ($_GET['status'] == 'updated') {
        $success_message = 'Usuário atualizado com sucesso!';
    } elseif ($_GET['status'] == 'deleted') {
        $success_message = 'Usuário excluído com sucesso!';
    } elseif ($_GET['status'] == 'error') {
        $error_message = 'Ocorreu um erro ao processar a requisição.';
    }
}

try {
    // Busca todos os usuários (Sem filtros, conforme solicitado)
    $stmt = $pdo->query("SELECT id, nome, email, criado_em FROM usuarios ORDER BY id DESC");
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Erro ao buscar usuários: ' . $e->getMessage();
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header do Módulo -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-users-cog text-gold-500"></i>
            Acessos Administrativos
        </h1>
        <p class="text-neutral-400 mt-1 text-sm">Gerencie os usuários do sistema com acesso ao painel Style Barber.</p>
    </div>
    <div>
        <a href="create.php" class="px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Cadastrar Novo Usuário
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

<!-- Tabela de Usuários -->
<div class="glass-card rounded-2xl overflow-hidden">
    <?php if (empty($usuarios)): ?>
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-neutral-900 border border-neutral-800 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-user-shield text-neutral-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">Nenhum usuário cadastrado</h3>
            <p class="text-neutral-500 text-sm max-w-sm mx-auto mb-6">Crie um usuário administrativo para poder logar e testar o gerenciamento.</p>
            <a href="create.php" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 border border-neutral-800 text-gold-500 hover:text-gold-400 rounded-xl text-sm font-semibold transition-all">
                Cadastrar Primeiro Usuário
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-800 bg-neutral-900/40">
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider">Criado Em</th>
                        <th class="px-6 py-4 text-xs font-bold text-neutral-400 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60">
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="hover:bg-neutral-900/25 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-semibold text-neutral-500">#<?= $usuario['id'] ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-white"><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td class="px-6 py-4 text-sm text-neutral-300"><?= htmlspecialchars($usuario['email']) ?></td>
                            <td class="px-6 py-4 text-sm text-neutral-400"><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <a href="update.php?id=<?= $usuario['id'] ?>" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-neutral-900 hover:bg-neutral-800 text-neutral-400 hover:text-white border border-neutral-800 hover:border-neutral-700 transition-all" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete.php?id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir o usuário \'<?= htmlspecialchars($usuario['nome']) ?>\'?')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/20 transition-all" title="Excluir">
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
