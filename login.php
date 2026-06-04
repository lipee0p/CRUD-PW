<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['password'] ?? '');

    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha o e-mail e a senha.';
    } else {
        $pdo = getConexao();

        $stmt = $pdo->prepare(
            "SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];

            header('Location: index.php');
            exit();
        } else {
            $erro = 'E-mail ou senha inválidos. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-br">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
<title>Acesso Seguro | Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "surface-variant": "#e2e2e3", "on-secondary-fixed-variant": "#474747",
                "on-secondary": "#ffffff", "tertiary-container": "#33e4ff",
                "on-tertiary-container": "#006270", "surface-container-lowest": "#ffffff",
                "outline-variant": "#d1c6ab", "surface": "#f9f9fa",
                "secondary-fixed-dim": "#c6c6c6", "primary-fixed": "#ffe083",
                "inverse-on-surface": "#f0f1f2", "on-tertiary-fixed": "#001f25",
                "on-error-container": "#93000a", "on-background": "#1a1c1d",
                "on-primary-fixed": "#231b00", "on-error": "#ffffff",
                "primary-container": "#facc15", "secondary-fixed": "#e2e2e2",
                "on-secondary-fixed": "#1b1b1b", "inverse-primary": "#eec200",
                "error": "#ba1a1a", "inverse-surface": "#2f3132",
                "background": "#f9f9fa", "surface-dim": "#dadadb",
                "surface-container-high": "#e8e8e9", "surface-tint": "#735c00",
                "surface-container-highest": "#e2e2e3", "surface-container": "#eeeeef",
                "on-primary-container": "#6c5700", "secondary": "#5e5e5e",
                "on-tertiary-fixed-variant": "#004e59", "outline": "#7f7660",
                "error-container": "#ffdad6", "tertiary-fixed-dim": "#15daf4",
                "on-secondary-container": "#646464", "on-surface-variant": "#4d4632",
                "tertiary-fixed": "#a0efff", "surface-bright": "#f9f9fa",
                "on-surface": "#1a1c1d", "on-primary": "#ffffff", "on-tertiary": "#ffffff",
                "surface-container-low": "#f3f3f4", "on-primary-fixed-variant": "#574500",
                "primary": "#735c00", "secondary-container": "#e2e2e2",
                "tertiary": "#006876", "primary-fixed-dim": "#eec200"
            },
            borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
            spacing: {
                "section-gap": "32px", "max-width-login": "400px",
                "container-padding-mobile": "24px", "container-padding-desktop": "40px",
                "base": "8px", "stack-gap": "16px"
            },
            fontFamily: {
                "body-lg": ["Hanken Grotesk"], "label-sm": ["Hanken Grotesk"],
                "body-md": ["Hanken Grotesk"], "headline-lg-mobile": ["Hanken Grotesk"],
                "label-md": ["Hanken Grotesk"], "headline-md": ["Hanken Grotesk"],
                "headline-lg": ["Hanken Grotesk"]
            },
            fontSize: {
                "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                "headline-lg-mobile": ["28px", {"lineHeight": "36px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}]
            }
        }
    }
}
</script>
<style>
    body { font-family: 'Hanken Grotesk', sans-serif; -webkit-font-smoothing: antialiased; min-height: max(884px, 100dvh); }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .login-input:focus { box-shadow: 0 0 0 2px #1a1c1d, 0 0 0 4px #facc15; }
</style>
</head>
<body class="bg-on-background text-surface min-h-screen flex flex-col items-center justify-center">

<header class="w-full top-0 flex items-center justify-between px-container-padding-mobile py-4 bg-on-background sticky z-50">
    <a class="text-primary-container hover:opacity-80 transition-transform active:scale-95" href="index.php">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-label-md text-label-md uppercase tracking-widest text-primary-container">ACESSO SEGURO</h1>
    <div class="w-6"></div>
</header>

<main class="flex-grow w-full flex flex-col items-center justify-start pt-12 px-container-padding-mobile md:justify-center md:pt-0">

    <div class="w-full max-w-max-width-login mb-section-gap text-center md:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-container rounded-xl mb-6 transform rotate-3">
            <span class="material-symbols-outlined text-on-background text-[32px]" style="font-variation-settings: 'FILL' 1;">lock</span>
        </div>
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-surface mb-2">Bem-vindo de volta</h2>
        <p class="font-body-md text-body-md text-surface-variant/60">Insira suas credenciais para acessar seu painel seguro.</p>
    </div>

    <div class="w-full max-w-max-width-login bg-on-background rounded-xl md:max-w-[450px] md:border md:border-surface-variant/10 md:p-8">

        <!-- ✅ action corrigido para login.php -->
        <form action="login.php" class="flex flex-col gap-stack-gap" method="POST">

            <!-- ✅ Exibe erro -->
            <?php if (!empty($erro)): ?>
                <div style="background:#93000a33; border:1px solid #ffb4ab55; color:#ffb4ab; padding:12px 16px; border-radius:8px; font-size:14px;">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-surface-variant/80 ml-1" for="email">E-mail</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-surface-variant/40 group-focus-within:text-primary-container transition-colors">mail</span>
                    <input class="login-input w-full bg-surface-container-highest/10 border-2 border-transparent text-surface px-12 py-4 rounded-xl font-body-md outline-none transition-all placeholder:text-surface-variant/20 focus:bg-on-background focus:border-primary-container"
                        id="email" name="email" placeholder="nome@empresa.com" required type="email"/>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-surface-variant/80 ml-1" for="password">Senha</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-surface-variant/40 group-focus-within:text-primary-container transition-colors">key</span>
                    <input class="login-input w-full bg-surface-container-highest/10 border-2 border-transparent text-surface px-12 py-4 rounded-xl font-body-md outline-none transition-all placeholder:text-surface-variant/20 focus:bg-on-background focus:border-primary-container"
                        id="password" name="password" placeholder="••••••••" required type="password"/>
                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-surface-variant/40 hover:text-surface transition-colors" id="togglePassword" type="button">
                        <span class="material-symbols-outlined">visibility</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input class="w-5 h-5 rounded-md border-2 border-surface-variant/20 bg-transparent checked:bg-primary-container checked:border-primary-container focus:ring-0 transition-all cursor-pointer" type="checkbox"/>
                    <span class="font-label-sm text-label-sm text-surface-variant/60 group-hover:text-surface transition-colors">Lembrar de mim</span>
                </label>
                <a class="font-label-sm text-label-sm text-primary-container hover:opacity-80 border-b-2 border-primary-container/30 pb-0.5" href="#">Esqueceu a senha?</a>
            </div>

            <button class="w-full bg-primary-container text-on-background font-label-md text-label-md uppercase tracking-wider py-5 rounded-xl mt-4 active:scale-[0.98] transition-all hover:brightness-110 flex items-center justify-center gap-2" type="submit">
                ENTRAR
                <span class="material-symbols-outlined text-[20px]">login</span>
            </button>

        </form>
    </div>

    <div class="mt-12 text-center pb-20 md:mt-8">
        <p class="font-body-md text-body-md text-surface-variant/40 mb-4">
            Ainda não tem uma conta?
            <a class="text-surface font-bold border-b-2 border-primary-container ml-1" href="cadastro.php">Cadastre-se</a>
        </p>
        <a class="inline-flex items-center gap-2 font-label-sm text-label-sm text-surface-variant/60 hover:text-primary-container transition-colors" href="index.php">
            <span class="material-symbols-outlined text-[18px]">home</span>
            Voltar ao Início
        </a>
    </div>

</main>

<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    toggleBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleBtn.querySelector('span').textContent = type === 'password' ? 'visibility' : 'visibility_off';
    });

    document.querySelectorAll('button, a').forEach(el => {
        el.addEventListener('mousedown', () => el.classList.add('scale-95'));
        el.addEventListener('mouseup', () => el.classList.remove('scale-95'));
        el.addEventListener('mouseleave', () => el.classList.remove('scale-95'));
    });
</script>
</body>
</html>