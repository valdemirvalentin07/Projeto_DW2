<?php
/**
 * Classe responsável por gerenciar o login do usuário.
 */
class Login {
    private $name = 'admin';
    private $password = 'admin123';

    public function verificar_credenciais($name, $password) {
        if ($name === $this->name && $password === $this->password) {
            $_SESSION["logged_in"] = true;
            return 'admin'; // tipo fixo
        }
        return false;
    }

    public function verificar_logado() {
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
            return true;
        }
        $this->logout();
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
