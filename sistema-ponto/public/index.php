<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

// 1. Carrega dependências
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Controllers/PontoController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

// 2. Roteamento
$rota = $_GET['rota'] ?? 'login';

switch ($rota) {
    // --- AUTH ---
    case 'login':       AuthController::index(); break;
    case 'autenticar':  AuthController::login(); break;
    case 'sair':        AuthController::logout(); break;

    // --- FUNCIONÁRIO ---
    case 'dashboard':        PontoController::index(); break;
    case 'registrar_ponto':  PontoController::registrar(); break;
    case 'solicitar_ajuste': PontoController::solicitarAjuste(); break;
    case 'meu_perfil':       UserController::meuPerfil(); break;
    case 'alterar_senha':    UserController::alterarSenha(); break;
    case 'gerar_relatorio':  PontoController::gerarRelatorio(); break;

    // --- ADMINISTRAÇÃO ---
    case 'admin_dashboard':  
        AdminController::dashboard(); 
        break;

    case 'admin_usuarios':   
        AdminController::listarUsuarios(); 
        break;

    case 'cadastrar_usuario':
        AdminController::cadastrarUsuario(); 
        break;

    case 'inativar_usuario': 
        AdminController::inativarUsuario(); 
        break;

    case 'ativar_usuario':   // << NOVA ROTA
        AdminController::ativarUsuario(); 
        break;

    case 'admin_solicitacoes':
        AdminController::listarSolicitacoes(); 
        break;

    case 'aprovar_ajuste':   
        AdminController::processarAjuste('aprovado'); 
        break;

    case 'rejeitar_ajuste':  
        AdminController::processarAjuste('rejeitado'); 
        break;

    case 'admin_relatorio_individual': // << GERA PDF PELO ADMIN
        AdminController::gerarRelatorioIndividual();
        break;

    // --- 404 ---
    default:
        http_response_code(404);
        echo "Página não encontrada. <a href='?rota=login'>Voltar</a>";
        break;
}
?>