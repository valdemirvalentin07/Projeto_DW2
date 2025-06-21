<?php
session_start();

require_once 'classes/db.php';
require_once 'classes/login.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'], $_SESSION['tipo'])) {
    header("Location: Login.html");
    exit;
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ordens_cadastradas.php");
    exit;
}

// Valida os campos recebidos
if (!isset($_POST['id'], $_POST['descricao'], $_POST['valor']) || !is_numeric($_POST['id'])) {
    die("Dados inválidos.");
}

$id = intval($_POST['id']); // ← agora está garantido que $id está definido
$descricao = trim($_POST['descricao']);
$valor = str_replace(',', '.', $_POST['valor']); // Trata vírgulas como separador decimal

if (!is_numeric($valor)) {
    die("Valor inválido.");
}

try {
    $db = new DB();
    $pdo = $db->getPdo();

    // Verifica se a ordem existe antes de atualizar
    $stmt = $pdo->prepare("SELECT id FROM ordem_servicos WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        die("Ordem não encontrada.");
    }

    // Atualiza os dados do orçamento
    $stmt = $pdo->prepare("UPDATE ordem_servicos SET orcamento_descricao = ?, orcamento_valor = ? WHERE id = ?");
    $stmt->execute([$descricao, $valor, $id]);

    $_SESSION['mensagem'] = "Orçamento salvo com sucesso.";
    header("Location: ordens_cadastradas.php?id=$id");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar orçamento: " . $e->getMessage());
}
