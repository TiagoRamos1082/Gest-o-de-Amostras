# Gestão de Amostras

Teste prático para o processo seletivo de Desenvolvedor(a) Estagiário(a) da Ultralims.

Sistema para gerenciamento de amostras de laboratório, desenvolvido com PHP/Slim no backend e Next.js/React/TypeScript no frontend.

## Tecnologias

### Backend

- PHP 8.5+
- Slim Framework 4
- MySQL
- PDO
- Composer
- PHPUnit

### Frontend

- Next.js
- React
- TypeScript
- Node.js
- npm

---

## Estrutura do projeto

```text
GestaoDeAmostras/
├── backend/
│   ├── public/
│   │   └── index.php
│   ├── src/
│   │   ├── Domain/
│   │   ├── Application/
│   │   └── Infrastructure/
│   ├── tests/
│   ├── composer.json
│   └── .env
│
├── frontend/
│   ├── app/
│   ├── services/
│   ├── types/
│   ├── package.json
│   └── tsconfig.json
│
└── .gitignore
```

---

# Pré-requisitos

Para executar o projeto localmente, é necessário ter instalado:

- PHP 8.5 ou superior
- Composer
- MySQL
- Node.js
- npm

---

# Banco de dados

O projeto utiliza MySQL.

## 1. Criar o banco

Acesse o MySQL:

```bash
mysql -u root -p
```

Crie o banco:

```sql
CREATE DATABASE gestao_amostras
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Selecione o banco:

```sql
USE gestao_amostra;
```

## 2. Criar a tabela

Execute:

```sql
CREATE TABLE amostras (
    id INT NOT NULL AUTO_INCREMENT,
    codigo VARCHAR(50) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    responsavel_tecnico VARCHAR(255) DEFAULT NULL,
    data_recebimento DATE NOT NULL,
    data_conclusao DATE DEFAULT NULL,
    bloqueada TINYINT(1) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_amostras_codigo (codigo)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
```

---

# Configuração do Backend

Entre na pasta do backend:

```bash
cd backend
```

Instale as dependências:

```bash
composer install
```

Crie o arquivo `.env` na pasta `backend`.

Exemplo:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestao_amostras
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Configure os valores de acordo com o ambiente local.

> O arquivo `.env` não deve ser versionado, pois contém informações específicas do ambiente.

---

# Executando o Backend

Dentro da pasta `backend`, execute:

```bash
php -S localhost:8080 -t public
```

A API estará disponível em:

```text
http://localhost:8080
```

Para verificar se a API está funcionando:

```text
GET /teste
```

---

# Testes automatizados

Os testes automatizados estão localizados em:

```text
backend/tests
```

Para executar todos os testes:

```bash
cd backend
./vendor/bin/phpunit
```

Os testes abrangem principalmente:

- Entidade `Amostra`;
- Regras de negócio;
- Casos de uso;
- Repositories;
- Persistência no banco de dados;
- Operações de atualização;
- Operações de exclusão.

---

# Configuração do Frontend

Em outro terminal, entre na pasta:

```bash
cd frontend
```

Instale as dependências:

```bash
npm install
```

Execute o servidor de desenvolvimento:

```bash
npm run dev
```

O frontend estará disponível em:

```text
http://localhost:3000
```

O frontend consome a API disponibilizada pelo backend em:

```text
http://localhost:8080
```

---

# Funcionalidades

O sistema permite:

- Criar uma nova amostra;
- Listar amostras;
- Consultar uma amostra;
- Filtrar amostras por status;
- Filtrar amostras por tipo;
- Alterar o tipo da amostra;
- Alterar o responsável técnico;
- Iniciar uma análise;
- Concluir uma análise;
- Rejeitar uma amostra;
- Excluir uma amostra;
- Exibir mensagens de erro retornadas pela API.

---

# Regras de negócio

## Status inicial

Toda amostra é criada com o status:

```text
Recebida
```

## Iniciar análise

Uma amostra só pode passar de `Recebida` para `EmAnalise` quando possuir um responsável técnico informado.

## Concluir análise

Uma amostra só pode ser concluída quando:

- o status atual for `EmAnalise`;
- a data de conclusão estiver informada;
- a data de conclusão for igual ou posterior à data de recebimento.

## Rejeitar

Uma amostra pode ser rejeitada quando estiver nos estados:

- `Recebida`;
- `EmAnalise`.

## Estados finais

Os estados:

```text
Concluida
Rejeitada
```

são estados finais.

Após uma amostra ser concluída ou rejeitada, não são disponibilizadas ações de alteração ou transição no frontend.

---

# Fluxo de status

```text
Recebida
├── Iniciar análise → EmAnalise
└── Rejeitar → Rejeitada

EmAnalise
├── Concluir → Concluida
└── Rejeitar → Rejeitada

Concluida
└── Estado final

Rejeitada
└── Estado final
```

---

# Geração do código da amostra

Cada amostra recebe um código no formato:

```text
CAND108-AAAA-NNNNNN
```

Exemplo:

```text
CAND108-2026-000001
```

A sequência é controlada por ano, garantindo que a numeração seja incremental para cada ano.

---

# API

Principais endpoints disponibilizados:

```text
POST   /amostras
GET    /amostras
GET    /amostras/{id}

PUT    /amostras/{id}/tipo
PUT    /amostras/{id}/responsavel

POST   /amostras/{id}/iniciar-analise
POST   /amostras/{id}/concluir-analise
POST   /amostras/{id}/rejeitar

DELETE /amostras/{id}
```

---

# Arquitetura

O backend foi organizado seguindo uma separação de responsabilidades em camadas:

```text
Domain
   ↓
Application
   ↓
Infrastructure
```

## Domain

Contém a entidade `Amostra` e suas regras de negócio, principalmente as regras relacionadas às transições de status.

## Application

Contém os casos de uso da aplicação e as interfaces dos repositories.

Cada operação importante do sistema possui seu próprio Use Case, mantendo as responsabilidades separadas.

## Infrastructure

Contém:

- Controllers HTTP;
- Implementação dos repositories;
- Conexão com o banco de dados;
- Integração com Slim Framework;
- Persistência através de PDO.

---

# Principais decisões técnicas

## Separação de responsabilidades

Foi utilizada uma arquitetura em camadas para separar regras de negócio, casos de uso e infraestrutura.

## Domain

As regras de negócio relacionadas à entidade `Amostra` ficam centralizadas no domínio, evitando que regras importantes dependam diretamente dos Controllers ou do banco de dados.

## Use Cases

Cada operação possui um caso de uso específico, facilitando testes, manutenção e evolução do sistema.

## Repository Pattern

Foi utilizada uma interface de Repository na camada de Application e uma implementação concreta na Infrastructure, separando a aplicação da tecnologia utilizada para persistência.

## PDO

O acesso ao MySQL utiliza PDO com prepared statements, reduzindo riscos relacionados à construção de consultas SQL e mantendo o acesso ao banco organizado.

## Testes automatizados

Foi utilizado PHPUnit para validar regras de domínio, casos de uso e persistência.

## Frontend

O frontend foi desenvolvido utilizando Next.js, React e TypeScript.

As chamadas para a API foram centralizadas em uma camada de services, mantendo a comunicação HTTP separada da interface.

## Validação de regras

As regras de negócio são protegidas no backend.

O frontend também controla quais ações ficam disponíveis para o usuário de acordo com o status da amostra.

---

# Execução rápida

## Backend

```bash
cd backend
composer install
php -S localhost:8080 -t public
```

## Frontend

Em outro terminal:

```bash
cd frontend
npm install
npm run dev
```

## Testes

```bash
cd backend
./vendor/bin/phpunit tests/
```

---

# Observações

O projeto foi desenvolvido como parte do teste prático para o processo seletivo da Ultralims.

As configurações específicas do ambiente, como credenciais do banco de dados, devem ser informadas no arquivo `.env` local e não devem ser versionadas.
