<?php include __DIR__ . '/../../layouts/header.php'; ?>

<style>
    /* --- ESTILO GERAL E VARIÁVEIS --- */
    :root {
        --req-card-bg: #ffffff;
        --req-header-bg: #f8f9fc;
        --req-border: #e3e6f0;
        --req-primary: #4e73df;
    }

    body {
        background-color: #f8f9fc; /* Fundo geral claro */
    }

    /* --- CORREÇÃO DO MENU SUPERIOR (NAVBAR) --- */
    /* Força o menu a ficar branco e clean para combinar com o design novo */
    nav.navbar, .navbar-custom {
        background: #ffffff !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
        border-bottom: 1px solid #e3e6f0;
    }
    nav a, .navbar-nav .nav-link {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    nav a:hover, .navbar-nav .nav-link:hover {
        color: #4e73df !important;
    }

    /* --- ESTILO DOS CARDS DE SOLICITAÇÃO --- */
    .request-card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        background: var(--req-card-bg);
        transition: all 0.2s;
        height: 100%;
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
    }

    .req-header {
        background-color: var(--req-header-bg);
        border-bottom: 1px solid var(--req-border);
        padding: 1rem 1.25rem;
        border-radius: 0.75rem 0.75rem 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-item label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #858796;
        font-weight: 700;
        margin-bottom: 0.2rem;
        display: block;
    }

    .info-item div {
        color: #5a5c69;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .motivo-box {
        background: #f8f9fa;
        border-left: 4px solid var(--req-primary);
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .attachment-chip {
        display: inline-flex;
        align-items: center;
        background: #fff;
        border: 1px solid #d1d3e2;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        color: #5a5c69;
        text-decoration: none;
        transition: all 0.2s;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .attachment-chip:hover {
        background: #eaecf4;
        color: #4e73df;
        border-color: #4e73df;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
    }
</style>

<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold border-start border-warning border-5 ps-3">
            Solicitações Pendentes
        </h1>
        <?php if(!empty($pendentes)): ?>
            <span class="badge bg-warning text-dark shadow-sm px-3 py-2 rounded-pill">
                <i class="fas fa-clock me-1"></i> <?= count($pendentes) ?> Aguardando
            </span>
        <?php endif; ?>
    </div>

    <?php if(empty($pendentes)): ?>
        
        <div class="empty-state fade-in">
            <div class="mb-3">
                <i class="fas fa-check-circle text-success fa-4x opacity-25"></i>
            </div>
            <h4 class="text-gray-800 fw-bold">Tudo limpo por aqui!</h4>
            <p class="text-muted">Não há solicitações de ajuste pendentes no momento.</p>
            <a href="?rota=admin_dashboard" class="btn btn-outline-primary btn-sm mt-2 rounded-pill px-4">Voltar ao Dashboard</a>
        </div>

    <?php else: ?>

        <div class="row">
            <?php foreach($pendentes as $p): ?>
            
            <?php 
                // --- LÓGICA DE APRESENTAÇÃO (CORRIGIDA) ---
                
                // 1. Formata o texto (ex: "pausa_inicio" vira "Pausa Inicio")
                $tipo_raw = $p['pont_tipo'];
                $tipo_visual = ucwords(str_replace(['_', '-'], ' ', $tipo_raw));
                
                // 2. Define Cores e Ícones
                $badge_class = 'bg-secondary';
                $icone = 'fa-clock';

                // Verifica palavras-chave na string original
                if (stripos($tipo_raw, 'entrada') !== false) {
                    $badge_class = 'bg-success'; // Verde
                    $icone = 'fa-sign-in-alt';
                } elseif (stripos($tipo_raw, 'saida') !== false || stripos($tipo_raw, 'saída') !== false) {
                    $badge_class = 'bg-danger'; // Vermelho
                    $icone = 'fa-sign-out-alt';
                } elseif (stripos($tipo_raw, 'pausa') !== false || stripos($tipo_raw, 'intervalo') !== false) {
                    $badge_class = 'bg-warning text-dark'; // Amarelo
                    $icone = 'fa-coffee';
                }
            ?>

            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="request-card h-100 d-flex flex-column">
                    
                    <div class="req-header">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                <i class="fas fa-user small"></i>
                            </div>
                            <div>
                                <h6 class="m-0 fw-bold text-primary"><?= $p['nome'] ?></h6>
                                <small class="text-muted">Mat: <?= $p['matricula'] ?></small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border">
                            #<?= $p['id'] ?>
                        </span>
                    </div>

                    <div class="card-body flex-fill d-flex flex-column">
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <label><i class="far fa-calendar-alt me-1"></i> Data</label>
                                <div><?= date('d/m/Y', strtotime($p['data_ajuste'])) ?></div>
                            </div>
                            <div class="info-item">
                                <label><i class="far fa-clock me-1"></i> Novo Horário</label>
                                <div class="text-primary fw-bold"><?= substr($p['hora_nova'], 0, 5) ?></div>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-exchange-alt me-1"></i> Tipo</label>
                                <div>
                                    <span class="badge <?= $badge_class ?> bg-opacity-75 shadow-sm">
                                        <i class="fas <?= $icone ?> me-1"></i> <?= $tipo_visual ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Justificativa:</label>
                            <div class="motivo-box">
                                <p class="mb-0 small fst-italic text-secondary">"<?= nl2br(htmlspecialchars($p['motivo'])) ?>"</p>
                            </div>
                        </div>

                        <?php 
                        $anexos = array_filter([$p['anexo_1'], $p['anexo_2'], $p['anexo_3']]);
                        if($anexos): ?>
                            <div class="mb-4">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Comprovantes:</label>
                                <?php foreach($anexos as $index => $a): ?>
                                    <a href="documentos/<?= $a ?>" target="_blank" class="attachment-chip" title="Ver documento">
                                        <i class="fas fa-paperclip me-1 text-gray-500"></i> Anexo <?= $index + 1 ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-auto pt-3 border-top">
                            <div class="d-flex gap-2">
                                <form action="?rota=aprovar_ajuste" method="POST" class="flex-fill">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-success w-100 btn-sm fw-bold shadow-sm">
                                        <i class="fas fa-check me-1"></i> Aprovar
                                    </button>
                                </form>
                                
                                <form action="?rota=rejeitar_ajuste" method="POST" class="flex-fill" onsubmit="return confirmarRejeicao(event)">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm fw-bold">
                                        <i class="fas fa-times me-1"></i> Rejeitar
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmarRejeicao(event) {
        if (!confirm('Tem certeza que deseja REJEITAR esta solicitação? Esta ação não pode ser desfeita.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>