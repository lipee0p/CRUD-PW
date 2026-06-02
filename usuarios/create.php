<?php
$page_title = 'Cadastrar Novo Usuário';
$active_tab = 'usuarios';

// Inclui conexão com o banco de dados
require_once __DIR__ . '/../db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        $error_message = 'Todos os campos são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Formato de e-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $error_message = 'A senha deve conter no mínimo 6 caracteres.';
    } else {
        try {
            // Verifica se o e-mail já está cadastrado
            $checkStmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $checkStmt->execute([$email]);
            if ($checkStmt->rowCount() > 0) {
                $error_message = 'Este e-mail já está sendo utilizado.';
            } else {
                // Criptografa a senha por motivos de segurança e boas práticas
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $email, $senha_hash]);
                
                header('Location: read.php?status=created');
                exit();
            }
        } catch (PDOException $e) {
            $error_message = 'Erro ao salvar usuário: ' . $e->getMessage();
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
        <i class="fa-solid fa-user-plus text-gold-500"></i>
        Novo Acesso Administrativo
    </h1>
    <p class="text-neutral-400 mt-1 text-sm">Cadastre um novo usuário para gerenciar a barbearia.</p>
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
            <label for="nome" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Nome Completo *</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: Roberto Silva" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all placeholder:text-neutral-600">
        </div>

        <!-- E-mail -->
        <div>
            <label for="email" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">E-mail de Acesso *</label>
            <input type="email" id="email" name="email" required placeholder="Ex: roberto@stylebarber.com" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all placeholder:text-neutral-600">
        </div>

        <!-- Senha -->
        <div>
            <label for="senha" class="block text-sm font-bold text-neutral-300 uppercase tracking-wider mb-2">Senha Provisória *</label>
            <input type="password" id="senha" name="senha" required placeholder="Mínimo 6 caracteres" class="w-full bg-neutral-900 border border-neutral-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/50 transition-all placeholder:text-neutral-600">
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
