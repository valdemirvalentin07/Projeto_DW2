Thander Assistência Técnica
Sistema web para gerenciamento de ordens de serviço, controle de orçamentos, cadastro e manutenção, desenvolvido em PHP com PDO, orientado a objetos e utilizando Bootstrap para o front-end.

Funcionalidades
Cadastro, edição, exclusão e listagem de ordens de serviço (CRUD completo)

Controle de status das ordens (Aberta, Em andamento, Concluída, Cancelada)

Registro de data de entrada e data de retirada do serviço

Geração e visualização de orçamentos para ordens

Sistema simples de login com permissões (admin)

Interface responsiva com Bootstrap

Validação e tratamento seguro de dados via prepared statements PDO

Feedback visual para operações (sucesso, erro, aviso)

Tecnologias Utilizadas
PHP 7.x ou superior

PDO para acesso seguro ao banco de dados MySQL

MySQL / MariaDB

Bootstrap 5 para layout e responsividade

HTML5, CSS3

JavaScript para interações básicas (confirmação de exclusão)

Estrutura do Projeto
bash
Copiar
Editar
/
├── classes/
│   ├── DB.php            # Classe de conexão e manipulação do banco
│   ├── Login.php         # Classe para gerenciamento de login e sessão
├── css/
│   └── ordem.css         # Estilos customizados
├── editar_servicos.php   # Página para edição de ordens
├── excluir_ordem.php     # Script para exclusão segura de ordens
├── gerar_orcamento.php   # Página para geração de orçamentos
├── ordens_cadastradas.php# Listagem e gerenciamento de ordens
├── salvar_orcamento.php  # Script para salvar orçamento no banco
├── login.php             # Página de login do sistema
├── adm.php               # Página inicial após login (dashboard)
└── README.md             # Este arquivo
Como usar
Configure seu servidor local (XAMPP, WAMP, etc.) com PHP e MySQL.

Crie o banco de dados thander e importe a estrutura das tabelas (não incluso neste repositório, configurar conforme seu modelo).

Ajuste as configurações de acesso ao banco em classes/DB.php se necessário.

Coloque os arquivos na pasta raiz do servidor local.

Acesse login.php, faça login com usuário e senha padrão (admin/admin123).

Utilize o sistema para gerenciar ordens e orçamentos.

Segurança
Uso de prepared statements para evitar SQL Injection

Controle de sessão para acesso restrito às páginas

Confirmação via JavaScript para ações destrutivas (exclusão)

Sanitização dos dados de entrada via filter_input




