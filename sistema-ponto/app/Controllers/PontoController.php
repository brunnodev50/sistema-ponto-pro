<?php
require_once __DIR__ . '/../Config/Database.php';

class PontoController {
    
    // Dashboard (Home)
    public static function index() {
        // Verifica se está logado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?rota=login");
            exit;
        }

        $pdo = Database::getConnection();
        $perfil = $_SESSION['perfil'];

        // CORREÇÃO AQUI:
        // Se for admin, redireciona para a rota correta do admin (que carrega gráficos)
        // Não carregamos a view diretamente aqui para não quebrar a lógica dos dados.
        if ($perfil == 'admin') {
            header("Location: ?rota=admin_dashboard");
            exit;
        }

        // --- LÓGICA DO FUNCIONÁRIO ---
        
        // Busca histórico recente (apenas para funcionário)
        $stmt = $pdo->prepare("SELECT * FROM registros_ponto WHERE usuario_id = ? ORDER BY data_hora DESC LIMIT 5");
        $stmt->execute([$_SESSION['usuario_id']]);
        $historico = $stmt->fetchAll();

        // Carrega a view do funcionário
        require __DIR__ . '/../../resources/views/funcionario/dashboard.php';
    }

    // Registrar batida
    public static function registrar() {
        $pdo = Database::getConnection();
        $tipo = $_POST['tipo'] ?? '';
        
        // Validação básica do tipo
        $validos = ['entrada', 'pausa_inicio', 'pausa_fim', 'saida'];
        if (in_array($tipo, $validos)) {
            $stmt = $pdo->prepare("INSERT INTO registros_ponto (usuario_id, tipo, data_hora) VALUES (?, ?, NOW())");
            $stmt->execute([$_SESSION['usuario_id'], $tipo]);
            header("Location: ?rota=dashboard&msg=Ponto registrado!");
        } else {
            header("Location: ?rota=dashboard&erro=Tipo inválido");
        }
    }

    // Processar solicitação com uploads
    public static function solicitarAjuste() {
        $pdo = Database::getConnection();
        
        // Função auxiliar de upload interna
        $upload = function($file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Validação de extensão
                if (in_array(strtolower($ext), ['jpg','jpeg','png','pdf'])) {
                    // Nome único para evitar conflitos
                    $nome = uniqid() . '.' . $ext;
                    // Caminho absoluto para a pasta public/documentos
                    $destino = __DIR__ . '/../../public/documentos/' . $nome;
                    
                    if (move_uploaded_file($file['tmp_name'], $destino)) {
                        return $nome;
                    }
                }
            }
            return null;
        };

        // Processa os 3 anexos
        $anexo1 = isset($_FILES['anexo_1']) ? $upload($_FILES['anexo_1']) : null;
        $anexo2 = isset($_FILES['anexo_2']) ? $upload($_FILES['anexo_2']) : null;
        $anexo3 = isset($_FILES['anexo_3']) ? $upload($_FILES['anexo_3']) : null;

        $stmt = $pdo->prepare("INSERT INTO solicitacoes_ajuste (usuario_id, data_ajuste, pont_tipo, hora_nova, tipo_ajuste, motivo, anexo_1, anexo_2, anexo_3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        try {
            $stmt->execute([
                $_SESSION['usuario_id'], 
                $_POST['data_ajuste'], 
                $_POST['pont_tipo'], 
                $_POST['hora_nova'], 
                $_POST['tipo_ajuste'], 
                $_POST['motivo'], 
                $anexo1, 
                $anexo2, 
                $anexo3
            ]);
            header("Location: ?rota=dashboard&msg=Solicitação enviada!");
        } catch (PDOException $e) {
            header("Location: ?rota=dashboard&erro=Erro ao salvar");
        }
    }

    public static function gerarRelatorio() {
        // Verifica Login
        if (!isset($_SESSION['usuario_id'])) header("Location: ?rota=login");

        $pdo = Database::getConnection();
        $inicio = $_GET['data_inicio'];
        $fim = $_GET['data_fim'];

        $stmt = $pdo->prepare("SELECT * FROM registros_ponto WHERE usuario_id = ? AND DATE(data_hora) BETWEEN ? AND ? ORDER BY data_hora ASC");
        $stmt->execute([$_SESSION['usuario_id'], $inicio, $fim]);
        $registros = $stmt->fetchAll();
        
        // Dados para a view
        $nome_funcionario = $_SESSION['nome'];
        
        require __DIR__ . '/../../resources/views/funcionario/relatorio.php';
    }
}
?>