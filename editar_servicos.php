<?php
session_start(); 

require_once 'classes/db.php';
require_once 'classes/login.php';

$db = new db();

$msg = '';
$msg_tipo= '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtrar e validar os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $aparelho = filter_input(INPUT_POST, 'aparelho_marca', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $data_retirada = $_POST['data_retirada'] ?? null;

    if (!$nome || !$telefone || !$endereco || !$aparelho || !$descricao || !$status) {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
        $tipoMensagem = "warning";
    } else {
        try {
            $sql = "UPDATE ordem_servicos SET nome = ?, telefone = ?, endereco = ?, aparelho_marca = ?, descricao = ?, status = ?, data_retirada = ? WHERE id = ?";
            $stmt = $db->getPdo()->prepare($sql);
            $stmt->execute([$nome, $telefone, $endereco, $aparelho, $descricao, $status, $data_retirada, $id]);

            $mensagem = "Ordem atualizada com sucesso.";
            $tipoMensagem = "success";

            header("Location: ordens_cadastradas.php?id=$id&update=success");
            exit;

        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar: " . $e->getMessage();
            $tipoMensagem = "danger";
        }
    }
}

$ordem = $db->buscarOrdemPorId($id);
if (!$ordem) {
    die("Ordem não encontrada.");
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Editar Ordem - Thander</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/ordem.css">
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
          <li class="nav-item"><a href="ordens_cadastradas.php" class="nav-link active">Cadastros</a></li>
          <li class="nav-item"><a href="adm.php" class="nav-link">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<div class="container mt-5">
  <h2>Editar Ordem de Serviço (ID: <?= $ordem['id'] ?>)</h2>

  <?php if ($msg): ?>
    <div class="alert alert-<?= $msg ?> mt-3"><?= htmlspecialchars($msg_tipo) ?></div>
  <?php endif; ?>

  <form method="post" class="mt-4">
    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($ordem['nome']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Telefone</label>
      <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($ordem['telefone']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Endereço</label>
      <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($ordem['endereco']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Aparelho / Marca</label>
      <input type="text" name="aparelho_marca" class="form-control" value="<?= htmlspecialchars($ordem['aparelho_marca']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="3" required><?= htmlspecialchars($ordem['descricao']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <?php
        $statusList = ['Aberta', 'Em andamento', 'Concluída', 'Cancelada'];
        foreach ($statusList as $status) {
            $selected = ($ordem['status'] === $status) ? 'selected' : '';
            echo "<option $selected>$status</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Data de Retirada</label>
      <input type="date" name="data_retirada" class="form-control" value="<?= htmlspecialchars($ordem['data_retirada']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="ordens_cadastradas.php" class="btn btn-secondary">Voltar</a>
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
