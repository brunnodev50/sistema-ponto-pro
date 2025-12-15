<?php include __DIR__ . '/../../layouts/header.php'; ?>

<?php
// Mantemos a função PHP para garantir a formatação ao exibir dados do banco
function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) == 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
    }
    return $cpf;
}
?>

<style>
    /* Estilo "Senior" - Clean & Modern */
    :root {
        --primary-bg: #f4f6f9;
        --card-border-radius: 12px;
        --avatar-size: 40px;
    }

    body { background-color: var(--primary-bg); }

    .user-card {
        border: none;
        border-radius: var(--card-border-radius);
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }

    /* Tabela Refinada */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.005);
        transition: all 0.2s ease;
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 2px solid #e9ecef;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        font-weight: 700;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f3f5;
        color: #495057;
    }

    /* Avatar com Iniciais */
    .avatar-circle {
        width: var(--avatar-size);
        height: var(--avatar-size);
        background-color: #e9ecef;
        color: #495057;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 15px;
    }

    /* Badges Suaves (Pill Style) */
    .badge-soft {
        padding: 0.5em 0.8em;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .badge-soft-success { background-color: #d1e7dd; color: #0f5132; }
    .badge-soft-danger  { background-color: #f8d7da; color: #842029; }
    .badge-soft-info    { background-color: #cff4fc; color: #055160; }
    .badge-soft-dark    { background-color: #e2e3e5; color: #41464b; }

    /* Inputs Modernos */
    .form-control-search {
        border-radius: 20px;
        padding-left: 40px;
        background-color: #f8f9fa;
        border: 1px solid transparent;
        transition: all 0.3s;
    }
    .form-control-search:focus {
        background-color: #fff;
        border-color: #b1b7c1;
        box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }
</style>

<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Gestão de Usuários</h4>
            <p class="text-muted small mb-0">Gerencie o acesso e permissões do sistema.</p>
        </div>
        <button class="btn btn-primary shadow-sm fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalCadastro">
            <i class="fas fa-plus me-2"></i> Novo Usuário
        </button>
    </div>

    <div class="user-card">
        
        <div class="p-4 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="position-relative">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscaUsuario" class="form-control form-control-search" placeholder="Buscar por nome, email ou CPF...">
                    </div>
                </div>
                <div class="col-md-8 text-end text-muted small">
                    <span id="contadorUsuarios"><?= count($usuarios) ?></span> usuários registrados
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0" id="tabelaUsuarios">
                <thead>
                    <tr>
                        <th class="ps-4">Colaborador</th>
                        <th>CPF</th>
                        <th>Matrícula</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): 
                        // Gerar iniciais para o avatar
                        $partesNome = explode(' ', trim($u['nome']));
                        $iniciais = strtoupper(substr($partesNome[0], 0, 1));
                        if (count($partesNome) > 1) {
                            $iniciais .= strtoupper(substr(end($partesNome), 0, 1));
                        }
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle random-bg"><?= $iniciais ?></div>
                                <div>
                                    <div class="fw-bold text-dark"><?= $u['nome'] ?></div>
                                    <div class="text-muted small"><?= $u['email'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="font-monospace text-secondary"><?= formatarCPF($u['cpf']) ?></td>
                        <td><?= $u['matricula'] ?></td>
                        <td>
                            <span class="badge-soft <?= $u['perfil'] == 'admin' ? 'badge-soft-dark' : 'badge-soft-info' ?>">
                                <i class="fas <?= $u['perfil'] == 'admin' ? 'fa-user-shield' : 'fa-user' ?> me-1"></i>
                                <?= ucfirst($u['perfil']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if($u['ativo']): ?>
                                <span class="badge-soft badge-soft-success"><i class="fas fa-check-circle me-1"></i>Ativo</span>
                            <?php else: ?>
                                <span class="badge-soft badge-soft-danger"><i class="fas fa-ban me-1"></i>Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <?php if($u['ativo']): ?>
                                <a href="?rota=inativar_usuario&id=<?= $u['id'] ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Deseja realmente inativar o acesso de <?= $u['nome'] ?>?')">
                                    Inativar
                                </a>
                            <?php else: ?>
                                <a href="?rota=ativar_usuario&id=<?= $u['id'] ?>" class="btn btn-outline-success btn-sm rounded-pill px-3" onclick="return confirm('Reativar o acesso de <?= $u['nome'] ?>?')">
                                    Ativar
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-link text-muted btn-sm"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div id="emptyState" class="text-center py-5 d-none">
                <i class="fas fa-search fa-3x text-light mb-3" style="color: #dee2e6 !important;"></i>
                <h6 class="text-muted">Nenhum usuário encontrado</h6>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCadastro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <form action="?rota=cadastrar_usuario" method="POST" id="formCadastro" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Email Corporativo</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">CPF</label>
                            <input type="text" name="cpf" id="inputCPF" class="form-control" placeholder="000.000.000-00" maxlength="14" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Matrícula</label>
                            <input type="text" name="matricula" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Perfil de Acesso</label>
                            <select name="perfil" class="form-select">
                                <option value="funcionario">Funcionário Padrão</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Senha Provisória</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="text" name="senha" class="form-control border-start-0" value="Mudar@123" required>
                            </div>
                            <div class="form-text text-muted small">Defina uma senha inicial. O usuário poderá alterar depois.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Salvar Usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Lógica de Busca em Tempo Real (Vanilla JS)
    document.getElementById('buscaUsuario').addEventListener('input', function() {
        const termo = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Remove acentos na busca
        const linhas = document.querySelectorAll('#tabelaUsuarios tbody tr');
        let visiveis = 0;

        linhas.forEach(linha => {
            const texto = linha.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            
            if (texto.includes(termo)) {
                linha.style.display = '';
                visiveis++;
            } else {
                linha.style.display = 'none';
            }
        });

        // Toggle Empty State
        const emptyState = document.getElementById('emptyState');
        const tabela = document.querySelector('.table-responsive table');
        
        if(visiveis === 0) {
            emptyState.classList.remove('d-none');
            tabela.classList.add('d-none');
        } else {
            emptyState.classList.add('d-none');
            tabela.classList.remove('d-none');
        }
    });

    // 2. Máscara de CPF (Input Mask)
    const inputCPF = document.getElementById('inputCPF');
    
    inputCPF.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
        
        if (value.length > 11) value = value.slice(0, 11); // Limita a 11 números

        // Aplica a formatação
        if (value.length > 9) {
            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
        } else if (value.length > 3) {
            value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
        }
        
        e.target.value = value;
    });

    // 3. Avatar Colors (Opcional: Dá cores aleatórias aos avatares para ficar bonito)
    const colors = ['#e0f2f1', '#e8eaf6', '#fce4ec', '#f3e5f5', '#e0f7fa'];
    const textColors = ['#00695c', '#283593', '#880e4f', '#4a148c', '#006064'];
    
    document.querySelectorAll('.avatar-circle').forEach(avatar => {
        const randomIdx = Math.floor(Math.random() * colors.length);
        avatar.style.backgroundColor = colors[randomIdx];
        avatar.style.color = textColors[randomIdx];
    });
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>