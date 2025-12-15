<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            height: 100vh;
            background: var(--primary-gradient);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden; /* Evita scroll desnecessário */
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            background: var(--glass-bg);
            backdrop-filter: blur(10px); /* Efeito de vidro fosco */
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px); /* Leve flutuação ao passar o mouse */
        }

        .form-floating label {
            color: #6c757d;
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.25);
        }

        .btn-login {
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            opacity: 0.9;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }

        .password-toggle {
            cursor: pointer;
            z-index: 10;
            color: #6c757d;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <div class="card login-card p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Bem-vindo de volta</h4>
                        <p class="text-muted small">Insira suas credenciais para acessar</p>
                    </div>

                    <?php if(isset($_GET['erro'])): ?>
                        <div class="alert alert-danger d-flex align-items-center fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>Usuário ou senha inválidos.</div>
                        </div>
                    <?php endif; ?>

                    <form action="?rota=autenticar" method="POST" id="loginForm" novalidate>
                        
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="emailInput" name="email" placeholder="nome@exemplo.com" required>
                            <label for="emailInput"><i class="bi bi-envelope me-2"></i>Email</label>
                            <div class="invalid-feedback">Por favor, insira um email válido.</div>
                        </div>

                        <div class="position-relative mb-4">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="senhaInput" name="senha" placeholder="Senha" required>
                                <label for="senhaInput"><i class="bi bi-lock me-2"></i>Senha</label>
                            </div>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lembrar">
                                <label class="form-check-label small text-muted" for="lembrar">Lembrar-me</label>
                            </div>
                            <a href="#" class="small text-decoration-none text-primary">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 rounded-pill" id="btnEntrar">
                            <span class="btn-text">Entrar</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Lógica de Toggle Password (Mostrar/Ocultar Senha)
        function togglePassword() {
            const senhaInput = document.getElementById('senhaInput');
            const icon = document.getElementById('toggleIcon');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                senhaInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Lógica de Validação e Loading do Botão
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const form = this;
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Se válido, ativa estado de loading
                const btn = document.getElementById('btnEntrar');
                const text = btn.querySelector('.btn-text');
                const spinner = btn.querySelector('.spinner-border');

                // Evita duplo clique
                btn.classList.add('disabled');
                text.textContent = 'Autenticando...';
                spinner.classList.remove('d-none');
                
                // O form será enviado normalmente após isso
            }

            form.classList.add('was-validated');
        });
    </script>
</body>
</html>