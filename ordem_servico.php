<?php

require_once 'classes/db.php';
require_once 'classes/login.php';

$db = new DB();
$pdo = $db->getPdo(); 

$msg = '';
$msg_tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['acao'] ?? '') {
        case 'inserir':
            $nome         = $_POST['nome'];
            $tel          = $_POST['telefone'];
            $end          = $_POST['endereco'];
            $ap           = $_POST['aparelho_marca'];
            $desc         = $_POST['descricao'];
            $data_entrada = $_POST['data_entrada']; // recebe do formulário

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO ordem_servicos
                    (nome, telefone, endereco, aparelho_marca, descricao, status, data_entrada)
                    VALUES (?, ?, ?, ?, ?, 'Aberta', ?)
                ");
                $stmt->execute([$nome, $tel, $end, $ap, $desc, $data_entrada,]);
                $msg = "Cadastro realizado com sucesso!";
                $msg_tipo = "success";
            } catch (PDOException $e) {
                $msg = "Erro ao cadastrar: " . $e->getMessage();
                $msg_tipo = "danger";
            }
            break;

        case 'status':
            $id     = $_POST['id_ordem_status'];
            $status = $_POST['novo_status'];

            try {
                $stmt = $pdo->prepare("UPDATE ordem_servicos SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
                $msg = "Status atualizado com sucesso!";
                $msg_tipo = "success";
            } catch (PDOException $e) {
                $msg = "Erro ao alterar status: " . $e->getMessage();
                $msg_tipo = "danger";
            }
            break;
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ordem de Serviço - Thander</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
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
          <li class="nav-item"><a href="ordem_servico.php" class="nav-link active">Ordem</a></li>
          <li class="nav-item"><a href="ordens_cadastradas.php" class="nav-link">Cadastros</a></li>
          <li class="nav-item"><a href="adm.php" class="nav-link">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<main class="container flex-grow-1">
  <h2 class="text-center">Gerenciar Ordem de Serviço</h2>

  <?php if ($msg): ?>
    <div class="alert alert-<?= $msg_tipo ?> alert-dismissible fade show mt-3" role="alert">
      <?= htmlspecialchars($msg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  <?php endif; ?>

  <ul class="nav nav-tabs mt-4" id="ordemTab" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#add">Adicionar</button></li>
  </ul>

  <div class="tab-content">

    <!-- Adicionar -->
    <div class="tab-pane fade show active" id="add">
      <form method="post" class="p-4 bg-white border border-top-0">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nome Completo</label>
            <input type="text" name="nome" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Número de Telefone</label>
            <input type="tel" name="telefone" class="form-control" required />
          </div>
          <div class="col-12">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Aparelho / Marca</label>
            <input type="text" name="aparelho_marca" class="form-control" required />
          </div>
          <div class="col-12">
            <label class="form-label">Descrição do Problema</label>
            <textarea name="descricao" class="form-control" rows="3" required></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Data de Entrada</label>
            <input type="date" name="data_entrada" class="form-control" required />
          </div>
        </div>
        <button type="submit" name="acao" value="inserir" class="btn btn-success mt-3">Adicionar Ordem</button>
      </form>
    </div>
  </div>
</main>

<footer class="footer mt-auto shadow">
  <div class="container text-center py-3">
    <p class="text-white m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
