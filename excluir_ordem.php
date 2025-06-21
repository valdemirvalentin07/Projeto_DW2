<?php
session_start();

require_once 'classes/db.php';
require_once 'classes/login.php';

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario'], $_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: Login.html");
    exit;
}

// Verifica se o método é POST e se o ID é válido
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['mensagem'] = "Requisição inválida.";
    $_SESSION['tipoMensagem'] = "danger";
    header("Location: ordens_cadastradas.php");
    exit;
}

$id = (int)$_POST['id'];

try {
    $db = new DB();
    $pdo = $db->getPdo();

    // Verifica se a ordem existe
    $stmt = $pdo->prepare("SELECT id FROM ordem_servicos WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        $_SESSION['mensagem'] = "Ordem não encontrada.";
        $_SESSION['tipoMensagem'] = "warning";
        header("Location: ordens_cadastradas.php");
        exit;
    }

    // Executa a exclusão
    $stmt = $pdo->prepare("DELETE FROM ordem_servicos WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['mensagem'] = "Ordem excluída com sucesso.";
    $_SESSION['tipoMensagem'] = "success";
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao excluir: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "danger";
}

header("Location: ordens_cadastradas.php");
exit;
