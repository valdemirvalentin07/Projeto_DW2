<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require('classes/Login.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $login = new Login();
    $tipo = $login->verificar_credenciais($usuario, $senha);

    if ($tipo) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipo'] = $tipo;

        if ($tipo === 'admin') {
            header('Location:adm.php');
        }
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Thander</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
            <div class="container">
                <span class="navbar-brand fw-bold text-purple">Thander Assistência Técnica</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="home.html" class="nav-link text-white">Home</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">Marcas</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">Serviços</a></li>
                        <li class="nav-item">
                            <a href="pagina_login.html" class="btn btn-outline-purple nav-link text-white disabled">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Formulário de Login -->
    <main class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 75px - 50px); padding-top: 100px;">
        <div class="container" style="max-width: 450px;">
            <div class="rounded shadow p-0" style="border: 2px solid #0808B7;">
                <div class="text-center p-3" style="background-color: #0808B7;">
                    <h3 class="m-0 text-light">LOGIN</h3>
                </div>

                <div class="p-4 ps-5 pe-5">
                    <?php if (!empty($erro)) echo "<div class='alert alert-danger text-center'>$erro</div>"; ?>

                    <form method="POST" action="">
                        <div class="mb-4 input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" class="form-control" id="usuario" name="usuario"
                                   placeholder="Digite seu usuário" required>
                        </div>
                        <div class="mb-4 input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="senha" name="senha"
                                   placeholder="Digite sua senha" required>
                        </div>
                        <div class="d-grid col-4 mx-auto pt-4">
                            <button type="submit" class="btn text-light" style="background-color:#0808B7">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="footer shadow mt-5">
        <div class="footer">
            <p style="color: white; text-align: center;">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
