<?php
require_once __DIR__ . '/../Config/Database.php';

class AdminController {
    
    // Middleware: Garante que apenas admin acessa
    private static function checkAdmin() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'admin') {
            header("Location: ?rota=dashboard"); 
            exit;
        }
    }

    // ==========================================================
    // 1. DASHBOARD & GRÁFICOS
    // ==========================================================
    public static function dashboard() {
        self::checkAdmin();
        $pdo = Database::getConnection();

        // Dados Gráfico Pizza (Status)
        $sqlPizza = "SELECT status, COUNT(*) as total FROM solicitacoes_ajuste GROUP BY status";
        $dadosPizza = $pdo->query($sqlPizza)->fetchAll(PDO::FETCH_KEY_PAIR);

        // Dados Gráfico Barra (Atividade Recente)
        $sqlBarra = "SELECT DATE(data_hora) as dia, COUNT(*) as total 
                     FROM registros_ponto 
                     WHERE data_hora >= DATE(NOW()) - INTERVAL 7 DAY 
                     GROUP BY DATE(data_hora) 
                     ORDER BY dia ASC";
        $dadosBarra = $pdo->query($sqlBarra)->fetchAll();

        // Lista de usuários para o Dropdown do Relatório
        $usuarios = $pdo->query("SELECT id, nome, matricula FROM usuarios WHERE perfil = 'funcionario' ORDER BY nome")->fetchAll();

        require __DIR__ . '/../../resources/views/admin/dashboard.php';
    }

    // ==========================================================
    // 2. GESTÃO DE USUÁRIOS
    // ==========================================================
    public static function listarUsuarios() {
        self::checkAdmin();
        $pdo = Database::getConnection();
        $usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nome")->fetchAll();
        require __DIR__ . '/../../resources/views/admin/usuarios.php';
    }

    public static function cadastrarUsuario() {
        self::checkAdmin();
        $pdo = Database::getConnection();
        
        try {
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, cpf, matricula, senha, perfil) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['cpf'], $_POST['matricula'], $senha, $_POST['perfil']]);
            $msg = "Usuário cadastrado com sucesso!";
        } catch (PDOException $e) {
            $msg = "Erro: Email, CPF ou Matrícula já cadastrados.";
        }
        
        header("Location: ?rota=admin_usuarios&msg=$msg");
    }

    public static function inativarUsuario() {
        self::checkAdmin();
        $pdo = Database::getConnection();
        $pdo->prepare("UPDATE usuarios SET ativo = 0 WHERE id = ?")->execute([$_GET['id']]);
        header("Location: ?rota=admin_usuarios&msg=Usuário inativado.");
    }

    public static function ativarUsuario() {
        self::checkAdmin();
        $pdo = Database::getConnection();
        $pdo->prepare("UPDATE usuarios SET ativo = 1 WHERE id = ?")->execute([$_GET['id']]);
        header("Location: ?rota=admin_usuarios&msg=Usuário reativado.");
    }

    // ==========================================================
    // 3. GESTÃO DE SOLICITAÇÕES
    // ==========================================================
    public static function listarSolicitacoes() {
        self::checkAdmin();
        $pdo = Database::getConnection();
        $sql = "SELECT s.*, u.nome, u.matricula 
                FROM solicitacoes_ajuste s 
                JOIN usuarios u ON s.usuario_id = u.id 
                WHERE s.status = 'pendente' 
                ORDER BY s.data_ajuste ASC";
        $pendentes = $pdo->query($sql)->fetchAll();
        require __DIR__ . '/../../resources/views/admin/solicitacoes.php';
    }

    public static function processarAjuste($novoStatus) {
        self::checkAdmin();
        $pdo = Database::getConnection();
        $id = $_POST['id'];

        $pdo->prepare("UPDATE solicitacoes_ajuste SET status = ? WHERE id = ?")->execute([$novoStatus, $id]);

        if ($novoStatus == 'aprovado') {
            $sol = $pdo->query("SELECT * FROM solicitacoes_ajuste WHERE id = $id")->fetch();
            
            if ($sol) {
                $dataHora = $sol['data_ajuste'] . ' ' . $sol['hora_nova']; 
                
                $check = $pdo->prepare("SELECT id FROM registros_ponto WHERE usuario_id = ? AND DATE(data_hora) = ? AND tipo = ?");
                $check->execute([$sol['usuario_id'], $sol['data_ajuste'], $sol['pont_tipo']]);
                $existente = $check->fetch();

                if ($existente) {
                    $pdo->prepare("UPDATE registros_ponto SET data_hora = ? WHERE id = ?")->execute([$dataHora, $existente['id']]);
                } else {
                    $pdo->prepare("INSERT INTO registros_ponto (usuario_id, tipo, data_hora) VALUES (?, ?, ?)")->execute([$sol['usuario_id'], $sol['pont_tipo'], $dataHora]);
                }
            }
        }
        header("Location: ?rota=admin_solicitacoes&msg=Solicitação processada.");
    }

    // ==========================================================
    // 4. RELATÓRIO INDIVIDUAL (PDF)
    // ==========================================================
    public static function gerarRelatorioIndividual() {
        self::checkAdmin();
        $pdo = Database::getConnection();

        $id_usuario = $_GET['usuario_id'];
        $inicio = $_GET['data_inicio'];
        $fim = $_GET['data_fim'];

        // CORREÇÃO: Busca o nome do funcionário selecionado (e não da sessão atual)
        $func = $pdo->query("SELECT nome FROM usuarios WHERE id = $id_usuario")->fetch();
        $nome_funcionario = $func['nome']; 

        // Busca registros
        $stmt = $pdo->prepare("SELECT * FROM registros_ponto WHERE usuario_id = ? AND DATE(data_hora) BETWEEN ? AND ? ORDER BY data_hora ASC");
        $stmt->execute([$id_usuario, $inicio, $fim]);
        $registros = $stmt->fetchAll();

        require __DIR__ . '/../../resources/views/funcionario/relatorio.php';
    }
}
?>