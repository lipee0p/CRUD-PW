<?php
$page_title = 'Dashboard';
$active_tab = 'dashboard';

// Inclui a conexão com o banco de dados
require_once __DIR__ . '/db.php';

// Inicializa as contagens padrões em caso de erro na conexão ou tabelas vazias
$total_usuarios = 0;
$total_cortes = 0;
$total_clientes = 0;
$total_funcionarios = 0;

try {
    // Busca estatísticas rápidas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $total_usuarios = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM cortes");
    $total_cortes = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
    $total_clientes = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM funcionarios");
    $total_funcionarios = $stmt->fetch()['total'];
} catch (PDOException $e) {
    // Se as tabelas ainda não existirem, ignora silenciosamente para não quebrar a página inicial
    // O usuário poderá rodar a criação no XAMPP ou via Docker
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Boas-vindas e Header da Página -->
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">Bem-vindo ao Style Barber</h1>
            <p class="text-neutral-400 mt-2">Painel de gerenciamento administrativo para controle de serviços, profissionais e agendamentos.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-gold-500/10 text-gold-400 border border-gold-500/20 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                Sistema Online
            </span>
        </div>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Cortes -->
    <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:border-gold-500/20 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                <i class="fa-solid fa-cut text-gold-500 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Cortes</span>
        </div>
        <h3 class="text-3xl font-extrabold text-white"><?= $total_cortes ?></h3>
        <p class="text-neutral-400 text-sm mt-1">Estilos de corte cadastrados</p>
    </div>

    <!-- Clientes (Agendamentos) -->
    <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:border-gold-500/20 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                <i class="fa-solid fa-calendar-check text-gold-500 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Agendamentos</span>
        </div>
        <h3 class="text-3xl font-extrabold text-white"><?= $total_clientes ?></h3>
        <p class="text-neutral-400 text-sm mt-1">Horários de clientes reservados</p>
    </div>

    <!-- Funcionários -->
    <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:border-gold-500/20 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                <i class="fa-solid fa-user-tie text-gold-500 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Funcionários</span>
        </div>
        <h3 class="text-3xl font-extrabold text-white"><?= $total_funcionarios ?></h3>
        <p class="text-neutral-400 text-sm mt-1">Colaboradores ativos</p>
    </div>

    <!-- Usuários -->
    <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:border-gold-500/20 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                <i class="fa-solid fa-users-cog text-gold-500 text-xl"></i>
            </div>
            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Usuários</span>
        </div>
        <h3 class="text-3xl font-extrabold text-white"><?= $total_usuarios ?></h3>
        <p class="text-neutral-400 text-sm mt-1">Acessos administrativos</p>
    </div>
</div>

<!-- Atalhos Rápidos por Módulo -->
<h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
    <i class="fa-solid fa-layer-group text-gold-500"></i>
    Gerenciamento do Sistema (CRUDs)
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <!-- CRUD CORTES -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white flex items-center gap-2.5 mb-2">
                <i class="fa-solid fa-cut text-gold-500"></i>
                Catálogo de Cortes
            </h3>
            <p class="text-neutral-400 text-sm mb-6">Cadastre estilos de corte de cabelo, defina seus valores padrão e determine acréscimos para designs extras personalizados (Freestyle).</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="cortes/read.php" class="px-4 py-2 bg-neutral-900 border border-neutral-800 text-neutral-200 hover:text-white hover:border-gold-500/30 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-list text-gold-500"></i> Listar Cortes
            </a>
            <a href="cortes/create.php" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Corte
            </a>
        </div>
    </div>

    <!-- CRUD AGENDAMENTOS (CLIENTES) -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white flex items-center gap-2.5 mb-2">
                <i class="fa-solid fa-calendar-check text-gold-500"></i>
                Agendamento de Clientes
            </h3>
            <p class="text-neutral-400 text-sm mb-6">Gerencie a agenda de clientes, defina os horários agendados, nomes e as respectivas formas de pagamento de maneira organizada.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="clientes/read.php" class="px-4 py-2 bg-neutral-900 border border-neutral-800 text-neutral-200 hover:text-white hover:border-gold-500/30 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-list text-gold-500"></i> Listar Clientes
            </a>
            <a href="clientes/create.php" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Agendamento
            </a>
        </div>
    </div>

    <!-- CRUD FUNCIONÁRIOS -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white flex items-center gap-2.5 mb-2">
                <i class="fa-solid fa-user-tie text-gold-500"></i>
                Equipe de Funcionários
            </h3>
            <p class="text-neutral-400 text-sm mb-6">Controle as informações dos funcionários da barbearia, suas funções/cargos, data de nascimento, data de admissão e remunerações.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="funcionarios/read.php" class="px-4 py-2 bg-neutral-900 border border-neutral-800 text-neutral-200 hover:text-white hover:border-gold-500/30 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-list text-gold-500"></i> Listar Funcionários
            </a>
            <a href="funcionarios/create.php" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Colaborador
            </a>
        </div>
    </div>

    <!-- CRUD USUÁRIOS -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white flex items-center gap-2.5 mb-2">
                <i class="fa-solid fa-users-cog text-gold-500"></i>
                Acessos Administrativos
            </h3>
            <p class="text-neutral-400 text-sm mb-6">Gerencie as credenciais dos usuários com permissão para acessar o painel Style Barber, incluindo cadastro e alteração de senhas com segurança.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="usuarios/read.php" class="px-4 py-2 bg-neutral-900 border border-neutral-800 text-neutral-200 hover:text-white hover:border-gold-500/30 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-list text-gold-500"></i> Listar Usuários
            </a>
            <a href="usuarios/create.php" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-neutral-950 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Usuário
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
