<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Ponto - <?= $nome_funcionario ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #7f8c8d;
            --border: #bdc3c7;
            --bg-paper: #ffffff;
            --highlight: #f8f9fa;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background-color: #e9ecef; /* Fundo cinza na tela */
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px; /* Fonte menor para caber mais dados */
        }

        /* Simulação de Folha A4 */
        .page {
            background: var(--bg-paper);
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Cabeçalho */
        .header {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-info h1 { margin: 0; font-size: 18px; text-transform: uppercase; color: var(--primary); }
        .company-info p { margin: 2px 0; color: var(--secondary); font-size: 11px; }

        .report-meta { text-align: right; font-size: 11px; color: var(--secondary); }

        /* Dados do Funcionário (Grid) */
        .employee-card {
            background-color: var(--highlight);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 4px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .info-group label { display: block; font-weight: bold; text-transform: uppercase; font-size: 9px; color: var(--secondary); margin-bottom: 2px; }
        .info-group span { display: block; font-size: 13px; font-weight: 600; color: #000; }

        /* Tabela */
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        
        th {
            background-color: var(--primary);
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            font-size: 12px;
        }

        tr:nth-child(even) { background-color: #fcfcfc; }

        /* Status Visual */
        .badge-dot {
            height: 8px; width: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .bg-success { background-color: #27ae60; } /* Entrada */
        .bg-danger { background-color: #c0392b; }  /* Saída */
        .bg-warning { background-color: #f39c12; } /* Pausa */

        /* Rodapé de Assinatura */
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 40%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 11px;
        }

        /* Botões Flutuantes (Não Imprimíveis) */
        .no-print-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-print { background: #2c3e50; color: white; }
        .btn-back { background: #ecf0f1; color: #333; }

        /* CONFIGURAÇÃO DE IMPRESSÃO */
        @media print {
            body { background: none; padding: 0; }
            .page { width: 100%; margin: 0; box-shadow: none; border: none; padding: 0; }
            .no-print-bar { display: none !important; }
            th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-dot { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            /* Garante quebra de página correta */
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button onclick="window.history.back()" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Voltar
        </button>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Imprimir / PDF
        </button>
    </div>

    <div class="page">
        
        <header class="header">
            <div class="company-info">
                <h1><i class="fas fa-building"></i> Sistema Corporativo</h1>
                <p>CNPJ: 00.000.000/0001-99</p>
                <p>Rua Exemplo Corporativo, 123 - Centro</p>
            </div>
            <div class="report-meta">
                <strong>EXTRATO DE PONTO DETALHADO</strong><br>
                Emissão: <?= date('d/m/Y H:i') ?><br>
                Ref: #<?= strtoupper(substr(md5(time()), 0, 8)) ?>
            </div>
        </header>

        <section class="employee-card">
            <div class="info-group">
                <label>Colaborador</label>
                <span><?= strtoupper($nome_funcionario) ?></span>
            </div>
            <div class="info-group">
                <label>Período</label>
                <span><?= date('d/m', strtotime($inicio)) ?> a <?= date('d/m/Y', strtotime($fim)) ?></span>
            </div>
            <div class="info-group">
                <label>Status</label>
                <span>ATIVO</span>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th width="20%">Data</th>
                    <th width="15%">Dia</th>
                    <th width="40%">Tipo de Registro</th>
                    <th width="25%">Horário</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
                
                foreach($registros as $r): 
                    $timestamp = strtotime($r['data_hora']);
                    $tipoRaw = $r['tipo'];
                    
                    // Tratamento Visual do Tipo
                    $tipoVisual = "Desconhecido";
                    $dotColor = "bg-secondary";
                    $icone = "fa-circle";

                    if(strpos($tipoRaw, 'entrada') !== false) {
                        $tipoVisual = "ENTRADA";
                        $dotColor = "bg-success";
                        $icone = "fa-sign-in-alt";
                    } elseif(strpos($tipoRaw, 'saida') !== false) {
                        $tipoVisual = "SAÍDA";
                        $dotColor = "bg-danger";
                        $icone = "fa-sign-out-alt";
                    } elseif(strpos($tipoRaw, 'pausa') !== false) {
                        $tipoVisual = "INTERVALO " . (strpos($tipoRaw, 'inicio') !== false ? "(SAÍDA)" : "(RETORNO)");
                        $dotColor = "bg-warning";
                        $icone = "fa-coffee";
                    }
                ?>
                <tr>
                    <td><strong><?= date('d/m/Y', $timestamp) ?></strong></td>
                    <td style="color: #666; font-size: 11px;">
                        <?= $diasSemana[date('w', $timestamp)] ?>
                    </td>
                    <td>
                        <span class="badge-dot <?= $dotColor ?>"></span>
                        <?= $tipoVisual ?>
                    </td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold; font-size: 13px;">
                        <?= date('H:i:s', $timestamp) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($registros)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 20px; color: #999;">
                        Nenhum registro encontrado neste período.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="font-size: 10px; color: #666; margin-top: 20px; text-align: justify;">
            Declaro para os devidos fins que as informações acima correspondem fielmente aos horários de minha jornada de trabalho no período especificado, não havendo divergências a serem apontadas.
        </div>

        <div class="signatures">
            <div class="sig-box">
                <?= strtoupper($nome_funcionario) ?><br>
                Assinatura do Colaborador
            </div>
            <div class="sig-box">
                DEPARTAMENTO DE RH<br>
                Gestão de Pessoas
            </div>
        </div>

    </div>

</body>
</html>