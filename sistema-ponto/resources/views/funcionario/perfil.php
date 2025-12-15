<?php include __DIR__ . '/../../layouts/header.php'; ?>

<?php
// Helpers de View (Lógica de apresentação)
function getIniciais($nome) {
    $partes = explode(' ', trim($nome));
    $primeira = $partes[0][0] ?? '?';
    $ultima = count($partes) > 1 ? end($partes)[0] : '';
    return strtoupper($primeira . $ultima);
}

function formatarCPFView($cpf) {
    if (!$cpf) return '';
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
}
?>

<style>
    :root {
        --profile-bg: #f8f9fa;
        --card-radius: 12px;
    }

    body { background-color: var(--profile-bg); }

    /* Cartão de Identidade (Esquerda) */
    .identity-card {
        border: none;
        border-radius: var(--card-radius);
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        text-align: center;
        padding: 2rem 1rem;
    }

    .avatar-xl {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1.5rem auto;
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }

    /* Cartão de Detalhes (Direita) */
    .details-card {
        border: none;
        border-radius: var(--card-radius);
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        background: #fff;
        height: 100%;
    }

    .section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #858796;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
    }

    /* Inputs "Readonly" estilizados */
    .form-control-plaintext {
        font-weight: 500;
        color: #5a5c69;
        font-size: 1rem;
        padding-left: 0;
    }
    
    .form-label-custom {
        font-size: 0.8rem;
        font-weight: 600;
        color: #4e73df;
    }

    /* Área de Senha */
    .security-box {
        background-color: #fff3cd;
        border: 1px solid #ffecb5;
        border-radius: 8px;
        padding: 1.5rem;
    }
</style>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-gray-800">Meu Perfil</h3>
        
        <?php if($_SESSION['perfil'] == 'admin'): ?>
            <a href="?rota=admin_dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        <?php else: ?>
            <a href="?rota=dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="identity-card h-100">
                <div class="avatar-xl">
                    <?= getIniciais($u['nome']) ?>
                </div>
                
                <h4 class="fw-bold text-dark mb-1"><?= $u['nome'] ?></h4>
                <p class="text-muted mb-3"><?= $u['email'] ?></p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-id-badge me-1"></i> <?= ucfirst($u['perfil']) ?>
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i> Ativo
                    </span>
                </div>

                <div class="text-start px-3 mt-4">
                    <small class="text-muted d-block mb-2">Estatísticas Rápidas</small>
                    <ul class="list-group list-group-flush small bg-transparent">
                        <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                            <span>Cadastro em:</span>
                            <span class="fw-bold">Dez 2025</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                            <span>Último Acesso:</span>
                            <span class="fw-bold">Hoje, 15:20</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="details-card p-4">
                
                <h6 class="section-title">Informações Pessoais</h6>
                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <label class="form-label-custom">Nome Completo</label>
                        <input type="text" readonly class="form-control-plaintext border-bottom" value="<?= $u['nome'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Email Corporativo</label>
                        <input type="text" readonly class="form-control-plaintext border-bottom" value="<?= $u['email'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">CPF</label>
                        <input type="text" readonly class="form-control-plaintext border-bottom" value="<?= formatarCPFView($u['cpf']) ?: 'Não informado' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Matrícula</label>
                        <input type="text" readonly class="form-control-plaintext border-bottom" value="<?= $u['matricula'] ?: '---' ?>">
                    </div>
                </div>

                <h6 class="section-title text-warning"><i class="fas fa-shield-alt me-2"></i>Segurança e Acesso</h6>
                
                <div class="security-box">
                    <div class="row align-items-center">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h6 class="fw-bold text-dark mb-1">Alterar Senha de Acesso</h6>
                            <p class="small text-muted mb-0">Recomendamos usar uma senha forte com caracteres especiais.</p>
                        </div>
                        <div class="col-md-5">
                            <form action="?rota=alterar_senha" method="POST" id="formSenha">
                                <div class="input-group">
                                    <input type="password" name="senha" id="inputSenha" class="form-control border-warning" placeholder="Nova senha" required minlength="4">
                                    <button class="btn btn-warning text-dark" type="button" onclick="toggleSenha()">
                                        <i class="fas fa-eye" id="iconEye"></i>
                                    </button>
                                </div>
                                <button class="btn btn-dark btn-sm w-100 mt-2 fw-bold">Atualizar</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleSenha() {
        const input = document.getElementById('inputSenha');
        const icon = document.getElementById('iconEye');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>