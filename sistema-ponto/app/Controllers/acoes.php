<?php
require 'config.php';
verificarLogin();

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// =========================================================
// FUNÇÃO DE UPLOAD (NOMES ÚNICOS)
// =========================================================
function processarUpload($arquivo) {
    if (isset($arquivo) && $arquivo['error'] === UPLOAD_ERR_OK) {
        $pasta = 'documentos/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        // Gera nome único para não sobrescrever: Time + Random
        $novoNome = uniqid(time()) . '_' . mt_rand(1000, 9999) . "." . $ext;
        
        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array(strtolower($ext), $tiposPermitidos)) {
            if (move_uploaded_file($arquivo['tmp_name'], $pasta . $novoNome)) {
                return $novoNome;
            }
        }
    }
    return null;
}

// =========================================================
// 1. REGISTRAR PONTO
// =========================================================
if ($acao == 'registrar_ponto') {
    $tipo = $_POST['tipo'];
    $validos = ['entrada', 'pausa_inicio', 'pausa_fim', 'saida'];
    
    if(in_array($tipo, $validos)) {
        $stmt = $pdo->prepare("INSERT INTO registros_ponto (usuario_id, tipo, data_hora) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['usuario_id'], $tipo]);
        header("Location: dashboard.php?msg=Ponto registrado com sucesso!");
    } else {
        header("Location: dashboard.php?msg=Erro: Tipo de ponto inválido.");
    }
    exit;
}

// =========================================================
// 2. SOLICITAR AJUSTE (COM 3 ANEXOS)
// =========================================================
if ($acao == 'solicitar_ajuste') {
    
    // Processa cada upload individualmente
    $anexo1 = isset($_FILES['anexo_1']) ? processarUpload($_FILES['anexo_1']) : null;
    $anexo2 = isset($_FILES['anexo_2']) ? processarUpload($_FILES['anexo_2']) : null;
    $anexo3 = isset($_FILES['anexo_3']) ? processarUpload($_FILES['anexo_3']) : null;

    $stmt = $pdo->prepare("INSERT INTO solicitacoes_ajuste 
        (usuario_id, data_ajuste, pont_tipo, hora_nova, tipo_ajuste, motivo, anexo_1, anexo_2, anexo_3) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
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
        header("Location: dashboard.php?msg=Solicitação enviada com sucesso!");
    } catch (PDOException $e) {
        die("Erro ao salvar solicitação: " . $e->getMessage());
    }
    exit;
}

// =========================================================
// 3. APROVAR/REJEITAR (ADMIN)
// =========================================================
if (($_SESSION['perfil'] == 'admin') && ($acao == 'aprovar_ajuste' || $acao == 'rejeitar_ajuste')) {
    $id = $_POST['id'];
    $novo_status = ($acao == 'aprovar_ajuste') ? 'aprovado' : 'rejeitado';
    
    // Atualiza status do pedido
    $pdo->prepare("UPDATE solicitacoes_ajuste SET status = ? WHERE id = ?")->execute([$novo_status, $id]);

    // Se aprovado, altera a tabela de registros de ponto
    if ($novo_status == 'aprovado') {
        $sol = $pdo->query("SELECT * FROM solicitacoes_ajuste WHERE id = $id")->fetch();
        
        if ($sol) {
            $dataHora = $sol['data_ajuste'] . ' ' . $sol['hora_nova']; 
            
            // Verifica se já existe batida desse tipo neste dia
            $check = $pdo->prepare("SELECT id FROM registros_ponto WHERE usuario_id = ? AND DATE(data_hora) = ? AND tipo = ?");
            $check->execute([$sol['usuario_id'], $sol['data_ajuste'], $sol['pont_tipo']]);
            $existente = $check->fetch();

            if ($existente) {
                // UPDATE (Corrige horário)
                $pdo->prepare("UPDATE registros_ponto SET data_hora = ? WHERE id = ?")
                    ->execute([$dataHora, $existente['id']]);
            } else {
                // INSERT (Cria registro que faltava)
                $pdo->prepare("INSERT INTO registros_ponto (usuario_id, tipo, data_hora) VALUES (?, ?, ?)")
                    ->execute([$sol['usuario_id'], $sol['pont_tipo'], $dataHora]);
            }
        }
    }
    header("Location: admin_solicitacoes.php?msg=Solicitação processada!");
    exit;
}

// =========================================================
// 4. CADASTRO DE USUÁRIO (ADMIN)
// =========================================================
if ($acao == 'cadastrar_usuario' && $_SESSION['perfil'] == 'admin') {
    try {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, cpf, matricula, senha, perfil) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nome'], $_POST['email'], $_POST['cpf'], $_POST['matricula'], $senha, $_POST['perfil']
        ]);
        header("Location: admin_usuarios.php?msg=Usuário cadastrado com sucesso!");
    } catch (PDOException $e) {
        header("Location: admin_usuarios.php?erro=Erro: Dados duplicados ou inválidos.");
    }
    exit;
}

// =========================================================
// 5. INATIVAR USUÁRIO (ADMIN)
// =========================================================
if ($acao == 'inativar' && $_SESSION['perfil'] == 'admin') {
    $pdo->prepare("UPDATE usuarios SET ativo = 0 WHERE id = ?")->execute([$_GET['id']]);
    header("Location: admin_usuarios.php?msg=Usuário inativado.");
    exit;
}

// =========================================================
// 6. ALTERAR SENHA
// =========================================================
if ($acao == 'alterar_senha') {
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?")->execute([$senha, $_SESSION['usuario_id']]);
    header("Location: perfil.php?msg=Senha alterada com sucesso!");
    exit;
}

// =========================================================
// 7. LOGOUT
// =========================================================
if ($acao == 'sair') {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>