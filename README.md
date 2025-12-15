# 🕰️ Sistema Ponto Pro

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-Concluído-success?style=for-the-badge)

> **Solução corporativa completa para gestão de frequência e jornada de trabalho.**

Desenvolvido com foco em simular um ambiente real de RH, o **Sistema Ponto Pro** elimina a complexidade de frameworks para entregar performance bruta e código limpo utilizando PHP 8 Nativo. O sistema oferece fluxos distintos para Colaboradores (registro e justificativas) e Gestores (auditoria e relatórios).

---

## 🚀 Funcionalidades Principais

### 👨‍💼 Painel do Colaborador
- **Smart Clock:** Relógio digital em tempo real com validação de horários.
- **Timeline Visual:** Histórico de batidas (Entrada, Almoço, Saída) em formato de linha do tempo.
- **Gestão de Justificativas:** Solicitação de ajustes de ponto com **upload de atestados/evidências**.
- **Segurança:** Visualização de espelho de ponto e alteração segura de senha.

### 🏢 Painel Administrativo (Gestor)
- **Dashboard Gerencial:** Gráficos interativos (Chart.js) para análise de assiduidade e fluxo de aprovações.
- **Relatórios Oficiais (A4):** Geração de espelho de ponto em PDF com layout rígido para impressão e assinatura.
- **Gestão de Pessoas:** Cadastro completo com máscaras de input (CPF), avatares automáticos e controle de acesso (RBAC).
- **Auditoria:** Fluxo visual para Aprovar ou Rejeitar solicitações de ajuste.

---

## 🛠️ Stack Tecnológica

O projeto foi construído seguindo o princípio **"Senior Simplicity"** — tecnologias robustas sem over-engineering:

- **Back-end:** PHP 8.2 (Vanilla/Nativo)
- **Front-end:** Bootstrap 5.3 + CSS Customizado (Glassmorphism & Soft UI)
- **Database:** MySQL / MariaDB
- **JavaScript:** Vanilla JS + Chart.js (Dashboards)
- **Ícones:** FontAwesome 6

---

## 📸 Screenshots

### 1. Acesso e Identidade
<div align="center">
  <img src="https://github.com/user-attachments/assets/cba790b6-2e6b-4c64-b042-37e540359bd1" alt="Login do Sistema" width="80%">
</div>

### 2. Visão do Colaborador (Dashboard & Perfil)
<div align="center">
  <img src="https://github.com/user-attachments/assets/462bc3bb-4198-45e1-871e-2fdd4e72c9c7" alt="Dashboard Funcionário" width="48%">
  <img src="https://github.com/user-attachments/assets/84aa346e-d3df-44a2-954f-063d0f041a28" alt="Perfil Usuário" width="48%">
</div>

### 3. Visão Administrativa (Gestão & Dashboards)
<div align="center">
  <img src="https://github.com/user-attachments/assets/04f04792-48c3-44dc-86c9-333cd66f6de6" alt="Dashboard Admin" width="100%">
  <br><br>
  <img src="https://github.com/user-attachments/assets/63f444c6-44b1-49fd-8cdf-4a75ece0ddf8" alt="Gestão de Usuários" width="100%">
</div>

### 4. Fluxo de Auditoria e Aprovação
<div align="center">
  <img src="https://github.com/user-attachments/assets/d4d5747a-dc93-4e54-a0d0-924078e62522" alt="Aprovação de Ponto" width="100%">
</div>

---
Desenvolvido com foco em simular um ambiente real de RH, o **Sistema Ponto Pro** foi construído sob uma **Arquitetura MVC (Model-View-Controller)** robusta. O projeto elimina a dependência de frameworks pesados, entregando performance bruta e código limpo utilizando PHP 8 Nativo, com práticas avançadas de segurança e organização.

---

## 🏗️ Arquitetura e Backend (Senior Level)

Diferente de scripts PHP comuns, este sistema utiliza uma engenharia de software profissional:

### 📂 Estrutura de Pastas
```text
/sistema-ponto
├── app/
│   ├── Config/        # Singleton de Conexão (PDO)
│   ├── Controllers/   # Lógica de Negócio (Admin, Auth, Ponto, User)
│   └── Utils/         # Helpers e Tratamento de Uploads
├── public/            # ÚNICO ponto de acesso (Security Layer)
│   ├── documentos/    # Uploads protegidos (.htaccess)
│   └── index.php      # Roteador (Router Pattern)
└── resources/
    ├── layouts/       # Cabeçalhos e Rodapés reutilizáveis
    └── views/         # Telas HTML limpas (sem query SQL)

## 📦 Como Instalar

1. Clone o repositório:
```bash
git clone [https://github.com/brunnodev50/sistema-ponto-pro.git](https://github.com/brunnodev50/sistema-ponto-pro.git)
