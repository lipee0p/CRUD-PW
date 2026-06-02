<?php
// Configura o caminho base dinamicamente para evitar problemas de links quebrados nas subpastas
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$projectName = 'style-barber';

if (strpos($uri, '/' . $projectName) !== false) {
    $base_url = $protocol . $host . '/' . $projectName . '/';
} else {
    $base_url = $protocol . $host . '/';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - Style Barber' : 'Style Barber' ?></title>
    
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        gold: {
                            50: '#fdfbeb',
                            100: '#fbf3c9',
                            200: '#f7e593',
                            300: '#f2cf53',
                            400: '#ebb925',
                            500: '#d99f16',
                            600: '#bc7f10',
                            700: '#965e10',
                            800: '#7a4b12',
                            900: '#643d14',
                            950: '#3a2007',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Efeito de desfoque de vidro personalizado */
        .glass-panel {
            background: rgba(23, 23, 23, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(251, 191, 36, 0.1);
        }
        .glass-card {
            background: rgba(30, 30, 30, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }
        ::-webkit-scrollbar-thumb {
            background: #262626;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #d99f16;
        }
    </style>
</head>
<body class="bg-neutral-950 text-neutral-100 min-h-screen flex flex-col font-sans selection:bg-gold-500 selection:text-neutral-950">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 glass-panel border-b border-neutral-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?= $base_url ?>index.php" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-gold-600 to-amber-400 flex items-center justify-center shadow-lg shadow-gold-600/20 group-hover:scale-105 transition-transform duration-300">
                            <i class="fa-solid fa-scissors text-neutral-950 text-xl font-bold"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-wider bg-gradient-to-r from-white via-neutral-200 to-gold-400 bg-clip-text text-transparent">STYLE BARBER</span>
                            <span class="block text-[10px] text-gold-500 font-bold uppercase tracking-widest leading-none">Management</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="<?= $base_url ?>index.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 <?= !isset($active_tab) || $active_tab == 'dashboard' ? 'bg-gold-500/10 text-gold-400 border border-gold-500/20' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/60' ?>">
                        <i class="fa-solid fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="<?= $base_url ?>cortes/read.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 <?= isset($active_tab) && $active_tab == 'cortes' ? 'bg-gold-500/10 text-gold-400 border border-gold-500/20' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/60' ?>">
                        <i class="fa-solid fa-cut mr-2"></i>Cortes
                    </a>
                    <a href="<?= $base_url ?>clientes/read.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 <?= isset($active_tab) && $active_tab == 'clientes' ? 'bg-gold-500/10 text-gold-400 border border-gold-500/20' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/60' ?>">
                        <i class="fa-solid fa-calendar-check mr-2"></i>Agendamentos
                    </a>
                    <a href="<?= $base_url ?>funcionarios/read.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 <?= isset($active_tab) && $active_tab == 'funcionarios' ? 'bg-gold-500/10 text-gold-400 border border-gold-500/20' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/60' ?>">
                        <i class="fa-solid fa-user-tie mr-2"></i>Funcionários
                    </a>
                    <a href="<?= $base_url ?>usuarios/read.php" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 <?= isset($active_tab) && $active_tab == 'usuarios' ? 'bg-gold-500/10 text-gold-400 border border-gold-500/20' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/60' ?>">
                        <i class="fa-solid fa-users-cog mr-2"></i>Usuários
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-lg text-neutral-400 hover:text-white hover:bg-neutral-900 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Abrir menu</span>
                        <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="hidden md:hidden border-t border-neutral-900/80 bg-neutral-950/95" id="mobile-menu">
            <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
                <a href="<?= $base_url ?>index.php" class="block px-4 py-2.5 rounded-lg text-base font-medium <?= !isset($active_tab) || $active_tab == 'dashboard' ? 'bg-gold-500/10 text-gold-400' : 'text-neutral-400 hover:text-white hover:bg-neutral-900' ?>">
                    <i class="fa-solid fa-chart-line mr-3"></i>Dashboard
                </a>
                <a href="<?= $base_url ?>cortes/read.php" class="block px-4 py-2.5 rounded-lg text-base font-medium <?= isset($active_tab) && $active_tab == 'cortes' ? 'bg-gold-500/10 text-gold-400' : 'text-neutral-400 hover:text-white hover:bg-neutral-900' ?>">
                    <i class="fa-solid fa-cut mr-3"></i>Cortes
                </a>
                <a href="<?= $base_url ?>clientes/read.php" class="block px-4 py-2.5 rounded-lg text-base font-medium <?= isset($active_tab) && $active_tab == 'clientes' ? 'bg-gold-500/10 text-gold-400' : 'text-neutral-400 hover:text-white hover:bg-neutral-900' ?>">
                    <i class="fa-solid fa-calendar-check mr-3"></i>Agendamentos
                </a>
                <a href="<?= $base_url ?>funcionarios/read.php" class="block px-4 py-2.5 rounded-lg text-base font-medium <?= isset($active_tab) && $active_tab == 'funcionarios' ? 'bg-gold-500/10 text-gold-400' : 'text-neutral-400 hover:text-white hover:bg-neutral-900' ?>">
                    <i class="fa-solid fa-user-tie mr-3"></i>Funcionários
                </a>
                <a href="<?= $base_url ?>usuarios/read.php" class="block px-4 py-2.5 rounded-lg text-base font-medium <?= isset($active_tab) && $active_tab == 'usuarios' ? 'bg-gold-500/10 text-gold-400' : 'text-neutral-400 hover:text-white hover:bg-neutral-900' ?>">
                    <i class="fa-solid fa-users-cog mr-3"></i>Usuários
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
