<?php

class DB {
    protected $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'thander';
        $user = 'root';
        $pass = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    // Método auxiliar para executar comandos com ou sem parâmetros
    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getPdo() {
        return $this->pdo;
    }

    // Lista todas as ordens
    public function buscarTodasOrdens() {
        $stmt = $this->pdo->query("SELECT * FROM ordem_servicos ORDER BY data_entrada DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Resumo por status
    public function buscarResumoStatus() {
        $stmt = $this->pdo->query("SELECT status, COUNT(*) AS total FROM ordem_servicos GROUP BY status");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar ordem por ID
    public function buscarOrdemPorId($id) {
        $stmt = $this->execute("SELECT * FROM ordem_servicos WHERE id = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserir nova ordem
    public function inserirOrdem($nome, $telefone, $endereco, $aparelho_marca, $descricao, $data_entrada) {
        $sql = "
            INSERT INTO ordem_servicos
            (nome, telefone, endereco, aparelho_marca, descricao, status, data_entrada)
            VALUES (?, ?, ?, ?, ?, 'Aberta', ?)
        ";
        $this->execute($sql, [$nome, $telefone, $endereco, $aparelho_marca, $descricao, $data_entrada]);
    }

    // Atualizar ordem existente
    public function atualizarOrdem($id, $nome, $telefone, $endereco, $aparelho, $descricao, $status) {
        $sql = "UPDATE ordem_servicos SET nome = ?, telefone = ?, endereco = ?, aparelho_marca = ?, descricao = ?, status = ? WHERE id = ?";
        $this->execute($sql, [$nome, $telefone, $endereco, $aparelho, $descricao, $status, $id]);
    }

    // Atualizar apenas o status
    public function atualizarStatus($id, $novo_status) {
        $this->execute("UPDATE ordem_servicos SET status = ? WHERE id = ?", [$novo_status, $id]);
    }

    // Atualizar data de retirada
    public function atualizarDataRetirada($id, $data) {
        $this->execute("UPDATE ordem_servicos SET data_retirada = ? WHERE id = ?", [$data, $id]);
    }

    // Excluir ordem
    public function excluirOrdem($id) {
        $this->execute("DELETE FROM ordem_servicos WHERE id = ?", [$id]);
    }

    // (Opcional) Salvar orçamento
    public function salvarOrcamento($id, $descricao, $valor) {
        $this->execute("UPDATE ordem_servicos SET orcamento_descricao = ?, orcamento_valor = ? WHERE id = ?", [$descricao, $valor, $id]);
    }
}
