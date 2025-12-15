<?php include __DIR__ . '/../../layouts/header.php'; ?>

<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --card-bg: #ffffff;
        --body-bg: #f8f9fc;
    }

    body {
        background-color: var(--body-bg);
    }

    /* Cards Modernos */
    .dashboard-card {
        background: var(--card-bg);
        border: none;
        border-radius: 0.75rem; /* 12px */
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
    }

    .card-header-custom {
        background: transparent;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header-title {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0;
    }

    /* Inputs e Forms */
    .form-control-modern {
        border-radius: 0.5rem;
        border: 1px solid #d1d3e2;
        padding: 0.6rem 1rem;
    }
    
    .form-control-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Botões */
    .btn-action {
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    /* Lista de Seleção Customizada */
    #selectFuncionario {
        border-radius: 0.5rem;
        border: 1px solid #d1d3e2;
        font-size: 0.9rem;
    }
    
    #selectFuncionario option {
        padding: 8px;
        border-bottom: 1px solid #f1f1f1;
    }
</style>

<div class="container-fluid py-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 fade-in">
        <h1 class="h3 mb-0 text-gray-800 fw-bold border-start border-primary border-5 ps-3">
            Dashboard Gerencial
        </h1>
        <div class="d-flex gap-2 mt-3 mt-sm-0">
            <a href="?rota=admin_usuarios" class="btn btn-sm btn-primary shadow-sm btn-action">
                <i class="fas fa-users fa-sm text-white-50 me-1"></i> Usuários
            </a>
            <a href="?rota=admin_solicitacoes" class="btn btn-sm btn-warning shadow-sm btn-action text-dark">
                <i class="fas fa-clipboard-check fa-sm text-dark-50 me-1"></i> Aprovar Ajustes
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header-custom">
                    <h6 class="card-header-title">
                        <i class="fas fa-file-pdf me-2"></i>Relatório Individual
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-4">Gere extratos detalhados de ponto filtrando por funcionário e período.</p>
                    
                    <form action="index.php" method="GET" target="_blank" class="needs-validation" novalidate>
                        <input type="hidden" name="rota" value="admin_relatorio_individual">
                        
                        <div class="mb-4">
                            <label class="form-label text-xs fw-bold text-uppercase text-gray-600">Buscar Funcionário</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-gray-400"></i></span>
                                <input type="text" id="inputBusca" class="form-control bg-light border-0 small" placeholder="Digite nome ou matrícula..." aria-label="Search">
                            </div>

                            <select name="usuario_id" id="selectFuncionario" class="form-select shadow-none" size="6" required style="overflow-y: auto;">
                                <option value="" selected disabled class="text-center py-2 text-muted small">-- Selecione na lista --</option>
                                <?php foreach($usuarios as $u): ?>
                                    <option value="<?= $u['id'] ?>">
                                        <?= htmlspecialchars($u['nome']) ?> (Mat: <?= $u['matricula'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Selecione um funcionário.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label text-xs fw-bold text-uppercase text-gray-600">Início</label>
                                <input type="date" name="data_inicio" class="form-control form-control-modern" required value="<?= date('Y-m-01') ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-xs fw-bold text-uppercase text-gray-600">Fim</label>
                                <input type="date" name="data_fim" class="form-control form-control-modern" required value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-action shadow-sm">
                            <i class="fas fa-download me-2"></i> Gerar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                
                <div class="col-md-6 mb-4">
                    <div class="card dashboard-card h-100">
                        <div class="card-header-custom d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-header-title text-info">
                                <i class="fas fa-chart-pie me-2"></i>Status Solicitações
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-2 pb-2">
                                <canvas id="graficoPizza"></canvas>
                            </div>
                            <?php if(empty($dadosPizza)): ?>
                                <div class="text-center mt-3 text-muted small"><i class="fas fa-inbox fa-2x mb-2"></i><br>Sem dados para exibir</div>
                            <?php endif; ?>
                            <div class="mt-4 text-center small">
                                <span class="me-2"><i class="fas fa-circle text-warning"></i> Pendente</span>
                                <span class="me-2"><i class="fas fa-circle text-success"></i> Aprovado</span>
                                <span class="me-2"><i class="fas fa-circle text-danger"></i> Rejeitado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card dashboard-card h-100">
                        <div class="card-header-custom">
                            <h6 class="card-header-title text-primary">
                                <i class="fas fa-chart-bar me-2"></i>Fluxo (7 Dias)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="graficoBarra"></canvas>
                            </div>
                            <?php if(empty($dadosBarra)): ?>
                                <div class="text-center mt-5 text-muted small">Sem registros recentes</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurações Globais dos Gráficos para visual mais limpo
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#858796';

    /* --- LÓGICA DE FILTRO DO SELECT --- */
    const inputBusca = document.getElementById('inputBusca');
    const selectFuncionario = document.getElementById('selectFuncionario');
    // Armazena as opções originais em memória ao carregar
    const opcoesOriginais = Array.from(selectFuncionario.querySelectorAll('option'));

    inputBusca.addEventListener('input', function() {
        const termo = this.value.toLowerCase();
        
        // Limpa o select atual
        selectFuncionario.innerHTML = '';

        // Filtra e reconstrói
        const opcoesFiltradas = opcoesOriginais.filter(opcao => {
            const texto = opcao.text.toLowerCase();
            const valor = opcao.value;
            // Mantém o placeholder ou se der match
            return valor === "" || texto.includes(termo);
        });

        if (opcoesFiltradas.length > 0) {
            opcoesFiltradas.forEach(op => selectFuncionario.appendChild(op));
        } else {
            // Feedback visual se não achar nada
            const opt = document.createElement('option');
            opt.text = "Nenhum funcionário encontrado";
            opt.disabled = true;
            selectFuncionario.appendChild(opt);
        }
    });

    /* --- GRÁFICO DE PIZZA (Doughnut Moderno) --- */
    const ctxPizza = document.getElementById('graficoPizza');
    const dadosP = {
        pendente: <?= $dadosPizza['pendente'] ?? 0 ?>,
        aprovado: <?= $dadosPizza['aprovado'] ?? 0 ?>,
        rejeitado: <?= $dadosPizza['rejeitado'] ?? 0 ?>
    };

    if ((dadosP.pendente + dadosP.aprovado + dadosP.rejeitado) > 0) {
        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: ['Pendente', 'Aprovado', 'Rejeitado'],
                datasets: [{
                    data: [dadosP.pendente, dadosP.aprovado, dadosP.rejeitado],
                    backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#dda20a', '#17a673', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 4 // Borda branca grossa separa as fatias
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '75%', // Buraco maior no meio (estilo moderno)
                plugins: {
                    legend: { display: false }, // Legenda customizada no HTML
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    }
                }
            }
        });
    }

    /* --- GRÁFICO DE BARRAS --- */
    const ctxBarra = document.getElementById('graficoBarra');
    <?php 
        $labels = []; $valores = [];
        if($dadosBarra) {
            foreach($dadosBarra as $d) {
                $labels[] = date('d/m', strtotime($d['dia']));
                $valores[] = $d['total'];
            }
        }
    ?>
    const dias = <?= json_encode($labels) ?>;
    const totais = <?= json_encode($valores) ?>;

    if (dias.length > 0) {
        new Chart(ctxBarra, {
            type: 'bar',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Registros',
                    data: totais,
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    borderRadius: 5, // Barras arredondadas
                    maxBarThickness: 25,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 6 }
                    },
                    y: {
                        ticks: { padding: 10, stepSize: 1 },
                        grid: { color: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2] }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false
                    }
                }
            }
        });
    }
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>