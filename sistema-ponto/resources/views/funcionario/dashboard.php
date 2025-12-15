<?php include __DIR__ . '/../../layouts/header.php'; ?>

<style>
    /* Design System */
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }

    /* Card do Relógio */
    .clock-card {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 1rem;
    }
    
    .digital-clock {
        font-family: 'Courier New', monospace; /* Fonte monoespaçada para estabilidade */
        font-weight: 700;
        letter-spacing: 2px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Botões de Ponto */
    .btn-ponto {
        border-radius: 12px;
        padding: 15px;
        transition: transform 0.2s;
        border: none;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .btn-ponto:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
    .btn-ponto i { font-size: 1.5rem; margin-bottom: 5px; }

    /* Estilo do Input de Arquivo Customizado */
    .file-upload-wrapper {
        position: relative;
        margin-bottom: 10px;
    }
    .file-upload-btn {
        border: 2px dashed #d1d3e2;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8f9fc;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        color: #858796;
    }
    .file-upload-btn:hover { background: #eaecf4; border-color: #4e73df; }
    .file-upload-btn.has-file {
        background: #e8f5e9;
        border-style: solid;
        border-color: #1cc88a;
        color: #0f6c44;
    }
    input[type="file"] { display: none; } /* Esconde o input feio padrão */

    /* Timeline do Histórico */
    .timeline { border-left: 2px solid #e3e6f0; margin-left: 10px; padding-left: 20px; }
    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #4e73df;
    }
</style>

<div class="row">
    
    <div class="col-lg-7 mb-4">
        
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <div class="clock-card p-4 text-center mb-4">
                    <h5 class="opacity-75 mb-0 text-uppercase small" id="dataAtual">Carregando data...</h5>
                    <h1 id="relogio" class="display-3 digital-clock mb-0">--:--:--</h1>
                </div>

                <form action="?rota=registrar_ponto" method="POST">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <button name="tipo" value="entrada" class="btn btn-success btn-ponto w-100">
                                <i class="fas fa-sign-in-alt"></i> Entrada
                            </button>
                        </div>
                        <div class="col-6 col-md-3">
                            <button name="tipo" value="pausa_inicio" class="btn btn-warning btn-ponto w-100 text-dark">
                                <i class="fas fa-utensils"></i> Almoço
                            </button>
                        </div>
                        <div class="col-6 col-md-3">
                            <button name="tipo" value="pausa_fim" class="btn btn-info btn-ponto w-100 text-white">
                                <i class="fas fa-undo"></i> Voltar
                            </button>
                        </div>
                        <div class="col-6 col-md-3">
                            <button name="tipo" value="saida" class="btn btn-danger btn-ponto w-100">
                                <i class="fas fa-sign-out-alt"></i> Saída
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Solicitar Ajuste de Ponto</h6>
            </div>
            <div class="card-body">
                <form action="?rota=solicitar_ajuste" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Data do Ocorrido</label>
                            <input type="date" name="data_ajuste" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Hora Correta</label>
                            <input type="time" name="hora_nova" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Tipo de Marcação</label>
                            <select name="pont_tipo" class="form-select" required>
                                <option value="entrada">Entrada</option>
                                <option value="pausa_inicio">Saída para Almoço</option>
                                <option value="pausa_fim">Retorno do Almoço</option>
                                <option value="saida">Saída</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Motivo do Erro</label>
                            <select name="tipo_ajuste" class="form-select" required>
                                <option value="esquecimento">Esquecimento</option>
                                <option value="erro_sistema">Erro no Sistema/Leitor</option>
                                <option value="justificativa_medica">Atestado Médico</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Descrição Detalhada</label>
                            <textarea name="motivo" class="form-control" rows="2" placeholder="Descreva brevemente o que aconteceu..." required></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label small fw-bold text-muted mb-2">Evidências / Atestados (Opcional)</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="anexo1" class="file-upload-btn" id="label1">
                                        <i class="fas fa-paperclip"></i> <span class="text-truncate">Anexo 1</span>
                                    </label>
                                    <input type="file" name="anexo_1" id="anexo1" onchange="updateFileName(this, 'label1')">
                                </div>
                                <div class="col-md-4">
                                    <label for="anexo2" class="file-upload-btn" id="label2">
                                        <i class="fas fa-paperclip"></i> <span class="text-truncate">Anexo 2</span>
                                    </label>
                                    <input type="file" name="anexo_2" id="anexo2" onchange="updateFileName(this, 'label2')">
                                </div>
                                <div class="col-md-4">
                                    <label for="anexo3" class="file-upload-btn" id="label3">
                                        <i class="fas fa-paperclip"></i> <span class="text-truncate">Anexo 3</span>
                                    </label>
                                    <input type="file" name="anexo_3" id="anexo3" onchange="updateFileName(this, 'label3')">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">*Formatos aceitos: PDF, JPG, PNG. Os arquivos são enviados separadamente.</small>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary px-4 fw-bold shadow-sm"><i class="fas fa-paper-plane me-2"></i> Enviar Solicitação</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-gray-800 mb-3"><i class="fas fa-file-pdf me-2"></i>Meus Relatórios</h6>
                <form action="index.php" method="GET" target="_blank" class="row g-2 align-items-end">
                    <input type="hidden" name="rota" value="gerar_relatorio">
                    <div class="col-5">
                        <label class="small text-muted">Início</label>
                        <input type="date" name="data_inicio" class="form-control form-control-sm" required value="<?= date('Y-m-01') ?>">
                    </div>
                    <div class="col-5">
                        <label class="small text-muted">Fim</label>
                        <input type="date" name="data_fim" class="form-control form-control-sm" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-dark btn-sm w-100 h-100"><i class="fas fa-download"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-gray-800">Últimos Registros</h6>
                <small class="text-muted">Hoje</small>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if(empty($historico)): ?>
                        <p class="text-muted small text-center py-4">Nenhum registro hoje.</p>
                    <?php else: ?>
                        <?php foreach($historico as $r): 
                            // Formatação Visual
                            $tipo = $r['tipo'];
                            $cor = 'secondary';
                            $icone = 'circle';
                            $texto = ucwords(str_replace(['_', '-'], ' ', $tipo));

                            if(strpos($tipo, 'entrada') !== false) { $cor = 'success'; $icone = 'sign-in-alt'; }
                            if(strpos($tipo, 'saida') !== false) { $cor = 'danger'; $icone = 'sign-out-alt'; }
                            if(strpos($tipo, 'pausa') !== false) { $cor = 'warning'; $icone = 'coffee'; $texto = "Intervalo"; }
                        ?>
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark h5 mb-0"><?= date('H:i', strtotime($r['data_hora'])) ?></span>
                                <span class="badge bg-<?= $cor ?> rounded-pill px-3"><?= $texto ?></span>
                            </div>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($r['data_hora'])) ?></small>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // 1. Relógio Digital em Tempo Real
    function atualizarRelogio() {
        const agora = new Date();
        const opcoesData = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        document.getElementById('relogio').innerText = agora.toLocaleTimeString('pt-BR');
        document.getElementById('dataAtual').innerText = agora.toLocaleDateString('pt-BR', opcoesData);
    }
    setInterval(atualizarRelogio, 1000);
    atualizarRelogio(); // Chama imediatamente para não esperar 1 seg

    // 2. Lógica de Upload de Arquivos (UX Sênior)
    // Quando o usuário seleciona um arquivo, muda o ícone e o texto do botão específico
    function updateFileName(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            // Trunca nome se for muito grande
            const shortName = fileName.length > 20 ? fileName.substring(0, 18) + '...' : fileName;
            
            label.innerHTML = `<i class="fas fa-check-circle"></i> ${shortName}`;
            label.classList.add('has-file');
        } else {
            // Se cancelar
            label.innerHTML = `<i class="fas fa-paperclip"></i> Anexo ${labelId.replace('label', '')}`;
            label.classList.remove('has-file');
        }
    }
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>