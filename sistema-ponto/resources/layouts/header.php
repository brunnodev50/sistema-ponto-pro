<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Ponto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<?php if(isset($_SESSION['usuario_id'])): ?>
<nav class="navbar navbar-dark bg-dark px-4 mb-4">
    <a class="navbar-brand" href="?rota=dashboard">Ponto Eletrônico</a>
    <div class="d-flex gap-2">
        <a href="?rota=meu_perfil" class="btn btn-primary btn-sm">Perfil</a>
        <?php if($_SESSION['perfil'] == 'admin'): ?>
            <a href="?rota=admin_usuarios" class="btn btn-info btn-sm">Usuários</a>
            <a href="?rota=admin_solicitacoes" class="btn btn-warning btn-sm">Solicitações</a>
        <?php endif; ?>
        <a href="?rota=sair" class="btn btn-danger btn-sm">Sair</a>
    </div>
</nav>
<?php endif; ?>
<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>