<?php
class AuthController {
    public static function index() {
        if (isset($_SESSION['usuario_id'])) header("Location: ?rota=dashboard");
        require __DIR__ . '/../../resources/views/auth/login.php';
    }

    public static function login() {
        $pdo = Database::getConnection();
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['perfil'] = $user['perfil'];
            header("Location: ?rota=dashboard");
        } else {
            header("Location: ?rota=login&erro=1");
        }
    }

    public static function logout() {
        session_destroy();
        header("Location: ?rota=login");
    }
}