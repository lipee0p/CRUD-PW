<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: style-barber/index.php');
    exit();
}

require_once 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

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

            header('Location: style-barber/index.php');
            exit();
        } else {
            $erro = 'E-mail ou senha inválidos. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Hanken+Grotesk:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: { extend: {
    colors: {
      "on-primary": "#233148", "secondary-container": "#41474e",
      "on-primary-fixed-variant": "#39475f", "on-secondary-fixed-variant": "#41474e",
      "inverse-surface": "#e4e2e4", "tertiary-fixed-dim": "#e7bf99",
      "tertiary-fixed": "#ffdcbd", "inverse-on-surface": "#303032",
      "on-secondary": "#2b3137", "on-surface-variant": "#c5c6cd",
      "tertiary": "#e7bf99", "error-container": "#93000a",
      "surface-container": "#1f1f21", "surface-tint": "#b9c7e4",
      "outline-variant": "#44474d", "on-primary-fixed": "#0d1c32",
      "surface-container-highest": "#343536", "primary": "#b9c7e4",
      "on-secondary-container": "#afb6bd", "on-surface": "#e4e2e4",
      "surface-container-high": "#2a2a2c", "on-tertiary": "#432b10",
      "on-tertiary-fixed-variant": "#5d4124", "secondary": "#c1c7cf",
      "inverse-primary": "#515f78", "error": "#ffb4ab",
      "primary-fixed": "#d6e3ff", "on-background": "#e4e2e4",
      "surface-variant": "#343536", "background": "#131315",
      "surface-dim": "#131315", "surface-container-low": "#1b1b1d",
      "secondary-fixed": "#dde3eb", "surface": "#131315",
      "on-error": "#690005", "tertiary-container": "#281400",
      "outline": "#8f9097", "primary-container": "#0a192f",
      "on-primary-container": "#74829d", "primary-fixed-dim": "#b9c7e4",
      "on-secondary-fixed": "#161c22", "surface-container-lowest": "#0e0e10",
      "on-tertiary-fixed": "#2b1701", "surface-bright": "#39393b",
      "on-error-container": "#ffdad6", "secondary-fixed-dim": "#c1c7cf",
      "on-tertiary-container": "#9d7b5a"
    },
    borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
    spacing: {
      "container-max-width": "1280px", "margin-desktop": "64px",
      "unit": "8px", "gutter": "24px", "margin-mobile": "16px"
    },
    fontFamily: {
      "headline-lg-mobile": ["newsreader"], "body-md": ["hankenGrotesk"],
      "headline-lg": ["newsreader"], "headline-sm": ["newsreader"],
      "headline-md": ["newsreader"], "display-lg": ["newsreader"],
      "body-lg": ["hankenGrotesk"], "label-md": ["hankenGrotesk"]
    },
    fontSize: {
      "headline-lg-mobile": ["36px", {"lineHeight": "44px", "fontWeight": "500"}],
      "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
      "headline-lg": ["48px", {"lineHeight": "56px", "fontWeight": "500"}],
      "headline-sm": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
      "headline-md": ["32px", {"lineHeight": "40px", "fontWeight": "500"}],
      "display-lg": ["64px", {"lineHeight": "72px", "letterSpacing": "-0.02em", "fontWeight": "600"}],
      "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
      "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600"}]
    }
  }}
}
</script>
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  body { background-color: #131315; color: #e4e2e4; }
</style>
</head>
<body class="antialiased overflow-hidden">

<header class="fixed top-0 w-full z-50 flex justify-between items-center h-20 px-margin-desktop max-w-container-max-width mx-auto bg-background/0 backdrop-blur-none pointer-events-none">
  <div class="font-headline-sm text-headline-sm uppercase tracking-widest text-secondary pointer-events-auto">THE STYLE BARBER</div>
</header>

<main class="min-h-screen grid grid-cols-1 md:grid-cols-2">

  <section class="flex flex-col justify-center items-center px-margin-mobile md:px-margin-desktop bg-background z-10">
    <div class="w-full max-w-md space-y-12">
      </div>

      <!-- ✅ CORRIGIDO: method="POST" adicionado -->
      <form class="space-y-8" method="POST" action="">

        <!-- ✅ Exibe erro -->
        <?php if (!empty($erro)): ?>
          <p style="color:#ffb4ab; font-size:14px;"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <div class="space-y-6">

          <div class="group border-b border-outline-variant/30 focus-within:border-secondary transition-colors duration-300">
            <label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider block mb-2" for="email">E-mail</label>
            <!-- ✅ CORRIGIDO: name="email" adicionado -->
            <input class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-outline/50 focus:ring-0 font-body-md text-body-md"
              id="email" name="email" placeholder="nome@exemplo.com" type="email"/>
          </div>

          <div class="group border-b border-outline-variant/30 focus-within:border-secondary transition-colors duration-300">
            <div class="flex justify-between items-end mb-2">
              <label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider block" for="password">Senha</label>
              <a class="font-label-md text-label-md text-secondary hover:text-primary transition-colors duration-200" href="#">Esqueceu a senha?</a>
            </div>
            <!-- ✅ CORRIGIDO: name="senha" adicionado -->
            <input class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-outline/50 focus:ring-0 font-body-md text-body-md"
              id="password" name="senha" placeholder="••••••••" type="password"/>
          </div>

        </div>

        <button class="w-full h-14 bg-secondary text-on-secondary font-label-md text-label-md uppercase tracking-widest hover:bg-primary transition-all duration-300 active:opacity-80" type="submit">
          Entrar no sistema
        </button>

      </form>

    </div>
  </section>

  <section class="hidden md:block relative overflow-hidden bg-surface-container-low">
    <div class="absolute inset-0 bg-gradient-to-r from-background to-transparent z-10 w-32"></div>
    <img alt="Barber shop interior" class="w-full h-full object-cover object-center grayscale-[0.2] contrast-[1.1]"
      src="https://lh3.googleusercontent.com/aida-public/AB6AXuD91yb5uFbwglMM7W1d6xgHyLcQOh4GXLWHYP-3Uoog_pal7BHkF-loVGa_ROrszQcuLZIo6CgDu4wTqoRkH-fciMya8XnSxWGXRclmUHmSrqotjiociNlAad2x66YuzVkYKCePRPo3Tc9jxpW6f0Qo_UipSo7XAR-4ba5KvuMD9fqe5cShe9XFs58-Gko4rSmLlk35IE3pXhLJ5LlZOEcQ4yE0EjgDtUNpMrvnBUZfj-S4gqQuCty3RcQ4E7W0xnmxbkrW_oKCEjFo"/>
    <div class="absolute bottom-20 left-20 z-20 max-w-sm space-y-4">
      <div class="h-1 w-12 bg-secondary"></div>
      <p class="font-display-lg text-display-lg italic text-secondary/90 leading-tight">A arte da precisão em cada detalhe.</p>
    </div>
    <div class="absolute inset-0 bg-primary-container/20 mix-blend-overlay"></div>
  </section>

</main>

<footer class="fixed bottom-0 w-full z-50 flex justify-center items-center h-16 px-margin-desktop md:justify-start pointer-events-none">
  <p class="font-label-md text-label-md text-on-surface-variant/40 tracking-tight uppercase pointer-events-auto">
    © 2026 THE STYLE BARBER. FAÇA JÁ SEU AGENDAMENTO.
  </p>
</footer>

</body>
</html>