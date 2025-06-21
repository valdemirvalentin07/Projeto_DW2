<?php
session_start(); // Inicia a sessão para usar $_SESSION

require_once 'classes/db.php';
require_once 'classes/login.php';

$db = new DB();
$pdo = $db->getPdo();

$stmtStatus = $pdo->query("SELECT status, COUNT(*) AS total FROM ordem_servicos GROUP BY status");
$relStatus = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);

$stmtOrdens = $pdo->query("SELECT * FROM ordem_servicos ORDER BY data_entrada DESC");
$ordens = $stmtOrdens->fetchAll(PDO::FETCH_ASSOC);

$msg = $_SESSION['mensagem'] ?? '';
$msg_tipo = $_SESSION['tipoMensagem'] ?? '';
unset($_SESSION['mensagem'], $_SESSION['tipoMensagem']);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ordens cadastradas - Thander</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/ordem.css">
  <style>
    .table td, .table th {
      vertical-align: middle;
      text-align: center;
    }
    h2, h5 {
      text-align: center;
    }
    .table .btn {
      min-width: 100px;
    }
  </style>
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

<div class="container">
  <h2 class="text-center">Ordens de Serviço cadastradas</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-<?= htmlspecialchars($msg_tipo) ?> mt-4 text-center"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="mt-4">
    <h5>Resumo por Status</h5>
    <table class="table table-bordered bg-white">
      <thead>
        <tr>
          <th>Status</th>
          <th>Total de Ordens</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($relStatus as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= $row['total'] ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="mt-5">
    <h5>Listagem Completa</h5>
    <table class="table table-striped bg-white">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Telefone</th>
          <th>Endereço</th>
          <th>Aparelho/Marca</th>
          <th>Descrição</th>
          <th>Status</th>
          <th>Data de Entrada</th>
          <th>Data de Retirada</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($ordens as $o): ?>
        <tr>
          <td><?= $o['id'] ?></td>
          <td><?= htmlspecialchars($o['nome']) ?></td>
          <td><?= htmlspecialchars($o['telefone']) ?></td>
          <td><?= htmlspecialchars($o['endereco']) ?></td>
          <td><?= htmlspecialchars($o['aparelho_marca']) ?></td>
          <td><?= htmlspecialchars($o['descricao']) ?></td>
          <td><?= htmlspecialchars($o['status']) ?></td>
          <td><?= date('d/m/Y', strtotime($o['data_entrada'])) ?></td>
          <td><?= $o['data_retirada'] ? date('d/m/Y', strtotime($o['data_retirada'])) : '-' ?></td>
          <td class="text-center">
            <div class="d-flex flex-column align-items-center gap-1">
              <a href="editar_servicos.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-primary w-100">Editar</a>
              <form method="post" action="excluir_ordem.php" onsubmit="return confirm('Tem certeza que deseja excluir esta ordem?');" class="w-100">
                <input type="hidden" name="id" value="<?= $o['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger w-100">Excluir</button>
              </form>
              <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                <?php if (empty($o['orcamento_descricao']) || empty($o['orcamento_valor'])): ?>
                  <form method="post" action="gerar_orcamento.php" class="w-100">
                    <input type="hidden" name="id" value="<?= $o['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success w-100">Gerar Orçamento</button>
                  </form>
                <?php else: ?>
                  <div class="mt-2 p-2 bg-light border rounded text-start w-100">
                    <strong>Orçamento:</strong><br>
                    <span class="text-success">R$ <?= number_format($o['orcamento_valor'], 2, ',', '.') ?></span><br>
                    <small><?= nl2br(htmlspecialchars($o['orcamento_descricao'])) ?></small>
                  </div>
                <?php endif; ?>
              <?php else: ?>
                <em class="text-muted">Acesso restrito</em>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="footer shadow mt-5" style="background-color: #0808B7;">
  <div class="container text-center py-3">
    <p class="text-white m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
