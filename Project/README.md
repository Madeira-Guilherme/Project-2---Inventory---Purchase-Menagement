# 📦 Inventory & Purchase Management API

REST API desenvolvida com **Laravel**, utilizando **Swagger** para documentação, **Spatie packages** para funcionalidades auxiliares e **Docker** para ambiente de desenvolvimento.

---

## 🚀 Tecnologias

- PHP / Laravel
- Docker & Docker Compose
- Swagger (OpenAPI)
- Spatie Packages
- MySQL (ou outra DB configurada)

---

## 📋 Requisitos

Antes de começar, certifica-te de que tens instalado:

- Docker
- Docker Compose
- Git

---

## ⚙️ Instalação

### 1. Clonar o repositório

```bash
git clone https://github.com/Madeira-Guilherme/Project-2---Inventory---Purchase-Menagement.git
cd Project-2---Inventory---Purchase-Menagement/Project
```

### 2. Configurar o ambiente

Copiar o ficheiro .env:
```bash
cp .env.example .env
```
### 3. Subir os containers Docker
```bash
docker-compose up -d --build
```
### 4. Instalar dependências

Entrar no container da app:
```bash
docker exec -it app bash
```
Dentro do container:
```bash
composer install
```
### 5. Gerar a key da aplicação
```bash
php artisan key:generate
```
### 6. Configurar base de dados

Editar o .env com as credenciais da DB (exemplo):
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=project2
DB_USERNAME=root
DB_PASSWORD=root
```
### 7. Executar migrations
```bash
php artisan migrate
```
### 8. (Opcional) Seed da base de dados
```bash
php artisan db:seed
```

---

## Documentação da API (Swagger)

Após subir o projeto, acede:

http://localhost:8000/api/documentation
