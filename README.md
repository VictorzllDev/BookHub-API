<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Sobre o Projeto

Um CRUD simples desenvolvido em **Laravel 9** como tarefa acadêmica. O sistema gerencia um relacionamento básico entre Autores (Authors) e Livros (Books), demonstrando os conceitos fundamentais do framework, incluindo Eloquent ORM, migrations, seeders e controllers.

## 🚀 Quick Start

Siga os passos abaixo para configurar e executar o projeto localmente.

### Pré-requisitos

Certifique-se de ter instalado em sua máquina:
*   PHP 8.1+
*   Composer 2.8+
*   MySQL (ou Docker, se preferir usar o container fornecido)

### 1. Clone o repositório

```bash
git clone https://github.com/VictorzllDev/CRUD-Laravel.git
cd CRUD-Laravel
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Configure o Ambiente

Copie o arquivo de ambiente de exemplo e gere a chave única da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o Banco de Dados

**Escolha UMA das opções abaixo:**

#### Opção A: Usando MySQL Local
Edite o arquivo `.env` com as credenciais do seu próprio servidor MySQL.

#### Opção B: Usando Docker (MySQL em Container)
Um arquivo `docker-compose.yaml` está disponível para subir um container MySQL.

1.  Inicie o container:
    ```bash
    docker-compose up -d
    ```
2.  Edite o arquivo `.env` para usar as configurações do container (verifique a porta e credenciais no arquivo `docker-compose.yaml`).

### 5. Execute as Migrations e Seeders

Este comando criará as tabelas no banco de dados e populará com dados iniciais para testes.

```bash
php artisan migrate && php artisan db:seed
```

### 6. Inicie o Servidor de Desenvolvimento

```bash
php artisan serve
```

O aplicativo estará disponível em: **http://localhost:8000**

---

## 📋 Funcionalidades

*   **CRUD de Autores (Authors)**: Criar, listar, visualizar, editar e excluir autores.
*   **CRUD de Livros (Books)**: Criar, listar, visualizar, editar e excluir livros.
*   **Relacionamento**: Cada livro está associado a um autor.
*   **API Resource**: Endpoints JSON para integração.
*   **Filtros e Ordenação**: As listagens de autores e livros possuem filtros e ordenação.

## 🛠️ Tecnologias Utilizadas

*   [Laravel 9](https://laravel.com/docs/9.x)
*   PHP 8.4.11
*   MySQL
*   Docker (opcional)
