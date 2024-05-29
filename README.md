### README.md

# Sistema de Menu de Serviços Online - Coren-RJ

Este projeto é um sistema de árvore de decisões interativa e módulo de agendamento de serviços, com um painel administrativo para gerenciar ambos. Foi desenvolvido utilizando Laravel 11, proporcionando uma interface intuitiva tanto para usuários finais quanto para administradores.

## Funcionalidades Principais

### Menu de Serviços

- **Interface Interativa**: Usuários podem clicar em botões para navegar por várias opções.
- **Ações dos Botões**:
  - Abrir sub-opções (novos botões).
  - Redirecionar para um link externo.
  - Direcionar para a página de agendamento de serviços.
- **Hierarquia**: Exibição do menu em formato hierárquico, com nós e sub-nós endentados.

### Agendamento de Serviços

- **Sistema de Agendamento**: Usuários podem marcar horários para diferentes tipos de serviços.
- **Página de Agendamento**:
  - Seleção de data.
  - Seleção de horário com base nas disponibilidades cadastradas pelo administrador.
  - Seleção de local.
- **Confirmação de Agendamento**:
  - Envio de confirmação por e-mail com token gerado automaticamente.
  - Geração de código de agendamento e token.

### Painel Administrativo

- **Gerenciamento da Árvore de Decisões**:
  - Adicionar, editar ou remover nós.
  - Configurar ações para cada nó (abrir sub-opções, redirecionar para links externos ou agendamento).
  - Definir a posição de cada nó para ordenar a apresentação.
- **Gerenciamento de Agendamentos**:
  - Cadastro de datas, horários e locais disponíveis para agendamento.
  - Visualização e gerenciamento de agendamentos realizados.
  - Configuração dos tipos de serviços que podem ser agendados.
- **Instruções Associadas**:
  - Associar instruções específicas a cada nó da árvore de decisões.

## Estrutura do Banco de Dados

### Tabela `decision_tree`

- `id` (bigint, unsigned, auto_increment) - Identificador único do nó.
- `label` (varchar 255) - Texto a ser exibido no nó.
- `parent_id` (bigint, unsigned, nullable) - Referência ao nó pai.
- `action_type` (varchar 255) - Tipo de ação (sub_option, external_link, schedule).
- `action_value` (varchar 255, nullable) - Valor da ação (URL, etc).
- `instruction_id` (bigint, unsigned, nullable) - Referência a uma instrução.
- `position` (int) - Posição do nó para ordenação.
- `created_at` (timestamp) - Data de criação.
- `updated_at` (timestamp) - Data de atualização.

### Tabela `instructions`

- `id` (bigint, unsigned, auto_increment) - Identificador único da instrução.
- `title` (varchar 255) - Título da instrução.
- `content` (text) - Conteúdo da instrução.
- `created_at` (timestamp) - Data de criação.
- `updated_at` (timestamp) - Data de atualização.

### Tabela `services`

- `id` (bigint, unsigned, auto_increment) - Identificador único do serviço.
- `name` (varchar 255) - Nome do serviço.
- `created_at` (timestamp) - Data de criação.
- `updated_at` (timestamp) - Data de atualização.

### Tabela `locations`

- `id` (bigint, unsigned, auto_increment) - Identificador único do local.
- `name` (varchar 255) - Nome do local.
- `address` (varchar 255) - Endereço do local.
- `is_available` (tinyint 1, default 1) - Disponibilidade do local.
- `created_at` (timestamp) - Data de criação.
- `updated_at` (timestamp) - Data de atualização.

### Tabela `schedule_slots`

- `id` (bigint, unsigned, auto_increment) - Identificador único do slot de agendamento.
- `location_id` (bigint, unsigned) - Referência ao local.
- `date` (date) - Data do agendamento.
- `time` (time) - Hora do agendamento.
- `is_available` (tinyint 1, default 1) - Disponibilidade do slot.
- `created_at` (timestamp) - Data de criação.
- `updated_at` (timestamp) - Data de atualização.

## Instalação e Configuração

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git
   cd seu-repositorio
   ```

2. Instale as dependências do Composer:
   ```bash
   composer install
   ```

3. Copie o arquivo de exemplo `.env` e configure suas credenciais de banco de dados:
   ```bash
   cp .env.example .env
   ```

4. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

5. Execute as migrações para criar as tabelas no banco de dados:
   ```bash
   php artisan migrate
   ```

6. Instale as dependências do NPM e compile os assets:
   ```bash
   npm install
   npm run dev
   ```

## Uso

### Executar o Servidor

Para iniciar o servidor de desenvolvimento, execute:
```bash
php artisan serve
```

Acesse a aplicação em [http://localhost:8000](http://localhost:8000).

### Gerenciamento de Árvore de Decisões

- Navegue até `/decision_tree` para visualizar e gerenciar a árvore de decisões.
- Utilize os botões "Adicionar Nó" para criar novos nós e "Editar" para modificar nós existentes.
- A hierarquia da árvore será exibida de forma endentada, facilitando a visualização das relações entre os nós.

### Agendamento de Serviços

- Navegue até `/schedule` para visualizar e gerenciar agendamentos.
- Utilize a interface para selecionar datas, horários e locais disponíveis para os serviços desejados.



