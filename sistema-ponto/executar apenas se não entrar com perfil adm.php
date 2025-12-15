<?php
// Arquivo: setup_admin.php
require 'config.php';

$senha_plana = 'admin123';
$senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);
$email = 'admin@empresa.com';

try {
    // Tenta limpar o admin antigo se existir
    $pdo->query("DELETE FROM usuarios WHERE email = '$email'");

    // Cria o novo admin
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil, ativo) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute(['Super Admin', $email, $senha_hash, 'admin']);

    echo "<h1>Sucesso!</h1>";
    echo "<p>Usuário criado/resetado.</p>";
    echo "Login: <b>$email</b><br>";
    echo "Senha: <b>$senha_plana</b><br><br>";
    echo "<a href='index.php'>Ir para o Login</a>";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>