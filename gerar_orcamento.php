<?php
session_start();

require_once 'classes/db.php';
require_once 'classes/login.php';

$db = new DB();
$pdo = $db->getPdo();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("ID da ordem inválido.");
}

$id = intval($_POST['id']);

$stmt = $pdo->prepare("SELECT * FROM ordem_servicos WHERE id = ?");
$stmt->execute([$id]);
$ordem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ordem) {
    die("Ordem não encontrada.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Gerar Orçamento - Ordem #<?= $id ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/ordem.css" />
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
    <div class="container">
      <i class="navbar-brand fw-bold text-purple">Thander Assistência Técnica</i>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="adm.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="ordem_servico.php" class="nav-link">Ordens</a></li>
          <li class="nav-item"><a href="ordens_cadastradas.php" class="nav-link">Cadastros</a></li>
          <li class="nav-item"><a href="adm.php" class="nav-link">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<div class="container mt-5 mb-5" style="max-width:600px;">
  <h2 class="text-center mb-4">Gerar Orçamento para Ordem #<?= $id ?></h2>

  <?php if (isset($_SESSION['mensagem'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensagem']) ?></div>
    <?php unset($_SESSION['mensagem']); ?>
  <?php endif; ?>

  <form method="post" action="salvar_orcamento.php" class="mt-4">
    <input type="hidden" name="id" value="<?= $id ?>" />

    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição do Orçamento:</label>
      <textarea
        name="descricao"
        id="descricao"
        rows="4"
        class="form-control"
        required
      ><?= htmlspecialchars($ordem['orcamento_descricao'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label for="valor" class="form-label">Valor (R$):</label>
      <input
        type="number"
        step="0.01"
        min="0"
        name="valor"
        id="valor"
        value="<?= htmlspecialchars($ordem['orcamento_valor'] ?? '') ?>"
        class="form-control"
        required
      />
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success">Salvar Orçamento</button>
      <a href="ordens_cadastradas.php" class="btn btn-secondary">Voltar</a>
    </div>
  </form>
</div>

<footer class="footer shadow mt-5" style="background-color: #0808B7;">
  <div class="container text-center py-3">
    <p class="text-white m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
