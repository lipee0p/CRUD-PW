<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: style-barber/index.php');
    exit();
}

require_once 'conexao.php';

$erro  = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirma = trim($_POST['confirma'] ?? '');

    if (empty($nome) || empty($email) || empty($senha) || empty($confirma)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirma) {
        $erro = 'As senhas não coincidem.';
    } else {
        $pdo = getConexao();

        // Verifica se o e-mail já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)"
            );
            $stmt->execute([
                ':nome'  => $nome,
                ':email' => $email,
                ':senha' => $senhaCriptografada,
            ]);

            $sucesso = 'Conta criada com sucesso!';
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
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: { extend: {
      colors: {
        "on-secondary": "#2b3137", "secondary": "#c1c7cf", "primary": "#b9c7e4",
        "on-surface": "#e4e2e4", "on-surface-variant": "#c5c6cd",
        "background": "#131315", "outline-variant": "#44474d",
        "outline": "#8f9097", "error": "#ffb4ab", "surface-container-low": "#1b1b1d",
        "primary-container": "#0a192f", "secondary-fixed-dim": "#c1c7cf",
      },
      fontFamily: { "headline-lg": ["Newsreader"], "body-lg": ["Hanken Grotesk"], "label-md": ["Hanken Grotesk"] },
      fontSize: {
        "headline-lg": ["48px", { lineHeight: "56px", fontWeight: "500" }],
        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
        "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.05em", fontWeight: "600" }],
        "headline-sm": ["24px", { lineHeight: "32px", fontWeight: "600" }],
      }
    }}
  }
</script>
<style>
  body { background-color: #131315; color: #e4e2e4; }
</style>
</head>
<body class="antialiased">

<header class="fixed top-0 w-full z-50 flex justify-between items-center h-20 px-16 pointer-events-none">
  <div class="font-headline-sm text-headline-sm uppercase tracking-widest text-secondary pointer-events-auto">THE STYLE BARBER</div>
</header>

<main class="min-h-screen grid grid-cols-1 md:grid-cols-2">

  <!-- Formulário -->
  <section class="flex flex-col justify-center items-center px-6 md:px-16 bg-background z-10">
    <div class="w-full max-w-md space-y-10">

      <div class="space-y-3">
        <h1 class="font-headline-lg text-headline-lg text-on-surface">Criar conta</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Preencha seus dados para começar a agendar.</p>
      </div>

      <!-- Mensagens -->
      <?php if (!empty($erro)): ?>
        <p style="color:#ffb4ab; font-size:14px;"><?= htmlspecialchars($erro) ?></p>
      <?php endif; ?>

      <?php if (!empty($sucesso)): ?>
        <p style="color:#81c995; font-size:14px;">
          <?= htmlspecialchars($sucesso) ?>
          — <a href="login.php" style="text-decoration:underline;">Fazer login</a>
        </p>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-6">

        <!-- Nome -->
        <div class="border-b border-[#44474d]/30 focus-within:border-[#c1c7cf] transition-colors duration-300">
          <label class="text-[#c5c6cd] text-xs uppercase tracking-wider block mb-2">Nome completo</label>
          <input name="nome" type="text" placeholder="Seu nome"
            class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-[#8f9097]/50 focus:ring-0 text-base"/>
        </div>

        <!-- Email -->
        <div class="border-b border-[#44474d]/30 focus-within:border-[#c1c7cf] transition-colors duration-300">
          <label class="text-[#c5c6cd] text-xs uppercase tracking-wider block mb-2">E-mail</label>
          <input name="email" type="email" placeholder="nome@exemplo.com"
            class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-[#8f9097]/50 focus:ring-0 text-base"/>
        </div>

        <!-- Senha -->
        <div class="border-b border-[#44474d]/30 focus-within:border-[#c1c7cf] transition-colors duration-300">
          <label class="text-[#c5c6cd] text-xs uppercase tracking-wider block mb-2">Senha</label>
          <input name="senha" type="password" placeholder="Mínimo 6 caracteres"
            class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-[#8f9097]/50 focus:ring-0 text-base"/>
        </div>

        <!-- Confirmar Senha -->
        <div class="border-b border-[#44474d]/30 focus-within:border-[#c1c7cf] transition-colors duration-300">
          <label class="text-[#c5c6cd] text-xs uppercase tracking-wider block mb-2">Confirmar senha</label>
          <input name="confirma" type="password" placeholder="Repita a senha"
            class="w-full bg-transparent border-none p-0 pb-4 text-on-surface placeholder:text-[#8f9097]/50 focus:ring-0 text-base"/>
        </div>

        <button type="submit"
          class="w-full h-14 bg-secondary text-on-secondary font-label-md text-label-md uppercase tracking-widest hover:bg-primary transition-all duration-300 active:opacity-80">
          Criar Conta
        </button>

      </form>

      <div class="pt-6 border-t border-[#44474d]/10 flex flex-col items-center gap-3">
        <p class="text-[#c5c6cd] text-base">Já tem uma conta?</p>
        <a href="login.php" class="text-secondary text-xs uppercase tracking-widest hover:underline underline-offset-8 transition-all">
          Fazer login
        </a>
      </div>

    </div>
  </section>

  <!-- Imagem lateral -->
  <section class="hidden md:block relative overflow-hidden bg-surface-container-low">
    <div class="absolute inset-0 bg-gradient-to-r from-background to-transparent z-10 w-32"></div>
    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD91yb5uFbwglMM7W1d6xgHyLcQOh4GXLWHYP-3Uoog_pal7BHkF-loVGa_ROrszQcuLZIo6CgDu4wTqoRkH-fciMya8XnSxWGXRclmUHmSrqotjiociNlAad2x66YuzVkYKCePRPo3Tc9jxpW6f0Qo_UipSo7XAR-4ba5KvuMD9fqe5cShe9XFs58-Gko4rSmLlk35IE3pXhLJ5LlZOEcQ4yE0EjgDtUNpMrvnBUZfj-S4gqQuCty3RcQ4E7W0xnmxbkrW_oKCEjFo"
      alt="Barber shop" class="w-full h-full object-cover grayscale-[0.2] contrast-[1.1]"/>
    <div class="absolute bottom-20 left-20 z-20 max-w-sm space-y-4">
      <div class="h-1 w-12 bg-secondary"></div>
      <p class="text-[64px] leading-tight italic text-[#c1c7cf]/90" style="font-family:Newsreader">A arte da precisão em cada detalhe.</p>
    </div>
    <div class="absolute inset-0 bg-primary-container/20 mix-blend-overlay"></div>
  </section>

</main>
</body>
</html>