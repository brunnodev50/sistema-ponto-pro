<?php
require_once __DIR__ . '/../Config/Database.php';

class UserController {
    public static function meuPerfil() {
        // Verifica se está logado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?rota=login");
            exit;
        }

        $pdo = Database::getConnection();
        $u = $pdo->query("SELECT * FROM usuarios WHERE id = " . $_SESSION['usuario_id'])->fetch();

        // CAMINHO DO ARQUIVO DE PERFIL
        // Certifique-se que o arquivo existe em: resources/views/funcionario/perfil.php
        $caminhoView = __DIR__ . '/../../resources/views/funcionario/perfil.php';
        
        if (file_exists($caminhoView)) {
            require $caminhoView;
        } else {
            die("Erro: O arquivo de visualização do perfil não foi encontrado em: $caminhoView");
        }
    }

    public static function alterarSenha() {
        if (!isset($_SESSION['usuario_id'])) header("Location: ?rota=login");

        $pdo = Database::getConnection();
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?")->execute([$senha, $_SESSION['usuario_id']]);
        header("Location: ?rota=meu_perfil&msg=Senha alterada com sucesso!");
    }
}
?>