# 🏢 Sistema de Gestão Condominial

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/CodeIgniter-4.7%2B-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter 4" />
  <img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Shield-Auth_1.2-blue?style=for-the-badge" alt="Shield Auth" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License" />
</p>

---

## 📌 Sobre o Projeto

O **Sistema de Gestão Condominial** é uma plataforma web completa, robusta e moderna desenvolvida com foco na excelência operacional de condomínios residenciais e comerciais. O sistema centraliza a administração de moradores, a disponibilização de áreas compartilhadas (salões de festas, quiosques, churrasqueiras, quadras esportivas), a gestão inteligente de agendamentos e reservas sem sobreposição de horários e a geração de cobranças vinculadas.

Construído sob a arquitetura **MVC** do **CodeIgniter 4**, adota práticas rigorosas de segurança, separação de responsabilidades com **Entities**, persistência com **Soft Deletes**, validação de regras de negócio em nível de Model e controle de acesso com **CodeIgniter Shield**.

---

## ✨ Funcionalidades Principais

### 📊 1. Painel Administrativo Executivo (Dashboard)
- **KPIs em Tempo Real:** Total de moradores cadastrados/ativos, quantidade de áreas sociais disponíveis, reservas confirmadas/pendentes e receita consolidada de taxas.
- **Vitrine de Áreas Comuns:** Visão rápida das instalações com horários de abertura/fechamento, capacidade máxima e taxas.
- **Histórico Recente de Agendamentos:** Listagem detalhada das reservas mais recentes.
- **Novos Moradores:** Feed dos últimos residentes registrados no condomínio.
- **Mural Informativo:** Divulgação de normas internas (horário de silêncio, prazo para cancelamentos) e canais de emergência/portaria 24h.

### 👥 2. Módulo de Residentes
- **Cadastro Completo:** Nome, CPF (com máscara e validação de unicidade), telefone, e-mail e localização física da unidade (Apartamento, Bloco e Torre).
- **Integração com Shield Auth:** Vínculo direto com contas de usuário, permitindo criação de credenciais, liberação ou bloqueio imediato de acesso ao portal.
- **Listagem com Filtros Avançados:** Busca por nome, CPF ou apartamento, filtro por status (ativo/bloqueado) e paginação nativa estilizada.
- **Edição & Exclusão Segura:** Soft delete nativo preservando histórico e auditoria.

### 🏊 3. Módulo de Áreas Comuns
- **Gestão de Espaços:** Cadastro detalhado com nome, regulamento de uso, capacidade máxima de ocupação e horário de expediente (abertura e encerramento).
- **Taxas de Reserva:** Suporte a espaços gratuitos ou com taxa de locação parametrizável.
- **Controle de Disponibilidade:** Botão de alternância rápida de status (disponibilizar ou interditar para manutenção) sem necessidade de exclusão.
- **Exclusão Lógica:** Proteção de dados históricos com suporte a restauração via `deleted_at`.

### 📅 4. Módulo de Reservas & Agendamentos
- **Algoritmo Anti-Conflito de Horários:** Prevenção matemática contra duplicidade ou sobreposição parcial de reservas para a mesma data e espaço (`horario_inicio < B_fim AND horario_fim > B_inicio`).
- **Validação de Expediente:** Bloqueio automático de agendamentos fora do horário de funcionamento configurado na área.
- **Comprovante Detalhado:** Emissão de comprovante formal para impressão com identificação da unidade, regras e valor.
- **Cancelamento Auditado:** Regra de prazo de cancelamento (mínimo 24 horas de antecedência) com registro formal de justificativa e liberação imediata do espaço.

---

## 🛠️ Tecnologias Utilizadas

| Camada | Tecnologia | Descrição |
|---|---|---|
| **Linguagem** | PHP 8.2+ | Tipagem estrita, nullables, match expressions e alta performance |
| **Framework** | CodeIgniter 4.7+ | Arquitetura MVC limpa, Query Builder, Migrations e Forge |
| **Autenticação** | CodeIgniter Shield 1.2 | Controle de autenticação, sessões seguras e senhas com Argon2/Bcrypt |
| **Banco de Dados** | MySQL 8.0 / MariaDB | Tabelas InnoDB, Foreign Keys em cascata, Soft Deletes e índices otimizados |
| **Interface / UI** | Soft UI Dashboard | Design System responsivo baseado em Bootstrap 5, glassmorphism e micro-animações |
| **Ícones & Tipografia** | FontAwesome 6 & Open Sans | Iconografia profissional e leitura dinâmica |

---

## 📁 Estrutura de Diretórios do Projeto

```text
condominio/
├── app/
│   ├── Config/              # Configurações do framework, banco e autenticação (Auth, Routes, Filters)
│   ├── Controllers/         # Controladores da aplicação
│   │   ├── AreasController.php       # Gestão de áreas comuns
│   │   ├── HomeController.php        # Dashboard administrativo principal
│   │   ├── ReservasController.php    # Gestão de agendamentos e cancelamentos
│   │   └── ResidentesController.php  # Gestão de moradores e usuários vinculados
│   ├── Database/
│   │   └── Migrations/      # Migrações versionadas (tabelas users, residentes, areas, reservas)
│   ├── Entities/            # Classes de negócio nativas (Area, Reserva, Residente)
│   ├── Models/              # Camada de persistência, validações e consultas
│   │   ├── AreaModel.php
│   │   ├── ReservaModel.php
│   │   ├── ResidenteModel.php
│   │   └── UserModel.php
│   └── Views/               # Templates HTML/PHP estilizados com Soft UI
│       ├── Areas/           # Telas de listagem, cadastro e edição de áreas
│       ├── Home/            # Dashboard executivo
│       ├── Layouts/         # Layout master (main.php) com sidebar e navbar
│       ├── Pagers/          # Template de paginação personalizado
│       ├── Reservas/        # Telas de listagem, detalhes e novo agendamento
│       └── Residentes/      # Telas de listagem, detalhes, cadastro e gestão de usuário
├── public/                  # Ponto de entrada web (index.php) e assets estáticos (CSS, JS, imagens)
├── writable/                # Diretório de escrita para logs, uploads e cache
├── .env                     # Variáveis de ambiente da aplicação
├── composer.json            # Dependências gerenciadas pelo Composer
├── LICENSE                  # Licença de uso MIT
└── README.md                # Documentação técnica do projeto
```

---

## 🚀 Como Executar o Projeto Localmente

### Pré-requisitos
- **PHP 8.2 ou superior** com as extensões ativas: `intl`, `mbstring`, `pdo_mysql`, `curl`, `json`.
- **Composer 2.x** instalado globalmente.
- Servidor **MySQL 8.0+** ou MariaDB em execução.

### Passo 1: Clonar o Repositório
```bash
git clone https://github.com/TaviloBreno/condominio.git
cd condominio
```

### Passo 2: Instalar as Dependências
```bash
composer install
```

### Passo 3: Configurar o Arquivo de Ambiente (.env)
Copie o arquivo de exemplo ou configure seu `.env` na raiz do projeto:
```ini
CI_ENVIRONMENT = development

# URL Base
app.baseURL = 'http://localhost:8080/'

# Banco de Dados
database.default.hostname = localhost
database.default.database = condominio
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port     = 3306
```

### Passo 4: Executar as Migrações do Banco de Dados
Execute o comando do CodeIgniter Spark para criar todas as tabelas versionadas:
```bash
php spark migrate
```
As seguintes migrações serão executadas em ordem cronológica:
1. `create_auth_tables` (Tabelas do CodeIgniter Shield)
2. `CreateResidentesTable` (Tabela `residentes`)
3. `AddResidentFieldsToUsersTable` (Campos complementares na tabela `users`)
4. `CreateAreasTable` (Tabela `areas`)
5. `CreateReservasTable` (Tabela `reservas`)
6. `CreateNotificacoesTable` (Tabela `notificacoes`)
7. `CreateCobrancasTable` (Tabela `cobrancas`)

### Passo 5: Iniciar o Servidor de Desenvolvimento
```bash
php spark serve
```
Acesse no seu navegador: **[http://localhost:8080](http://localhost:8080)**

---

## 🧭 Mapa de Rotas Principais

| Método | Rota | Nome da Rota | Descrição |
|---|---|---|---|
| `GET` | `/` | `home` | Painel executivo / Dashboard |
| `GET` | `/residentes` | `residentes.index` | Listagem paginada de moradores com filtros |
| `GET` | `/residentes/novo` | `residentes.novo` | Formulário para cadastro de novo morador |
| `POST` | `/residentes/criar` | `residentes.criar` | Processamento do cadastro de morador |
| `GET` | `/residentes/detalhes/(:num)` | `residentes.detalhes` | Visualização detalhada do morador |
| `GET` | `/residentes/editar/(:num)` | `residentes.editar` | Formulário de edição de dados |
| `POST` | `/residentes/atualizar/(:num)`| `residentes.atualizar`| Salva alterações cadastrais |
| `POST` | `/residentes/excluir/(:num)`  | `residentes.excluir`  | Exclusão lógica (soft delete) |
| `POST` | `/residentes/toggle-status/(:num)`| `residentes.toggleStatus` | Ativa ou bloqueia morador |
| `GET` | `/areas` | `areas.index` | Catálogo de áreas e espaços comuns |
| `GET` | `/areas/novo` | `areas.novo` | Formulário de cadastro de nova área |
| `POST` | `/areas/criar` | `areas.criar` | Processamento da criação da área |
| `GET` | `/areas/editar/(:num)` | `areas.editar` | Formulário de edição de área comum |
| `POST` | `/areas/atualizar/(:num)`| `areas.atualizar` | Salva alterações da área comum |
| `POST` | `/areas/excluir/(:num)` | `areas.excluir` | Exclusão lógica da área |
| `POST` | `/areas/toggle-status/(:num)`| `areas.toggleStatus` | Alterna status disponível/manutenção |
| `GET` | `/reservas` | `reservas.index` | Gestão de agendamentos e reservas |
| `GET` | `/reservas/novo` | `reservas.novo` | Formulário para solicitar reserva |
| `POST` | `/reservas/criar` | `reservas.criar` | Validação anti-conflito e persistência |
| `GET` | `/reservas/detalhes/(:num)` | `reservas.detalhes` | Comprovante detalhado da reserva |
| `POST` | `/reservas/cancelar/(:num)` | `reservas.cancelar` | Cancelamento com registro de justificativa |
| `GET` | `/cobrancas` | `cobrancas.index` | Gestão financeira e extrato de faturas |
| `GET` | `/cobrancas/detalhes/(:num)` | `cobrancas.detalhes` | Fatura detalhada com pagamento PIX |
| `POST` | `/cobrancas/pagar/(:num)` | `cobrancas.pagar` | Liquidação / confirmação de pagamento |

---

## 🔒 Segurança e Boas Práticas

- **Proteção CSRF Ativa:** Todos os formulários HTML utilizam tokens CSRF gerados via `<?= csrf_field() ?>`.
- **Autenticação com Shield:** Controle rigoroso de rotas via filtros de sessão (`session`).
- **Validação de Model:** Nenhum registro é gravado sem validação estrita dos campos, limites de caracteres e unicidade.
- **Proteção contra SQL Injection:** Uso exclusivo do CodeIgniter Query Builder e Prepared Statements.
- **Sanitização de Saída:** Todas as saídas de variáveis dinâmicas nas views utilizam o helper `esc()`, prevenindo ataques XSS.

---

## 📄 Licença

Este projeto está licenciado sob a **Licença MIT** — consulte o arquivo [LICENSE](LICENSE) para obter mais detalhes.

---

<p align="center">
  Desenvolvido com dedicação por <strong><a href="https://github.com/TaviloBreno">Tavilo Breno</a></strong>.
</p>
