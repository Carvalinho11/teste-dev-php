## Sobre o projeto

API Restful desenvolvida em Laravel 12 para gerenciamento de fornecedores, com suporte a CPF/CNPJ, autenticação, filtros, ordenação e integração com a BrasilAPI.


## Passo a passo para executar o teste

- Instalar dependências com o Composer:
  ```bash
  composer install
  ```

- Criar o banco de dados (MySQL ou PostgreSQL).

- Copiar o `.env.example` para `.env` e configurar as variáveis do banco de dados:
  ```env
  DB_CONNECTION=mysql
  DB_DATABASE=nome_do_banco
  DB_USERNAME=seu_usuario
  DB_PASSWORD=sua_senha
  ```

- Rodar as migrations:
  ```bash
  php artisan migrate
  ```

- Rodar o seeder (opcional):
  ```bash
  php artisan db:seed
  ```
  ou:
  ```bash
  php artisan migrate --seed
  ```

- Configurar o `.env.testing` com as credenciais do banco de testes.

- Rodar os testes automatizados:
  ```bash
  php artisan test
  ```

- Postman:
  Se preferir, você pode testar todos os endpoints utilizando a collection do Postman disponível neste repositório
  [Baixar collection Postman](postman/TestRevendamais.postman_collection.json)
```

- Login (auth):
  ```http
  POST api/login
  {
    "email": "usuario@example.com",
    "password": "senha123"
  }
  ```

- Logout (se quiser):
  ```http
  POST api/logout
  ```

- Cadastro de fornecedor:
  ```http
  POST api/suppliers
  {
    "name": "Empresa Sem Email",
    "document": "98765432000199",
    "phone": "31987654321",
    "address": {
      "zipcode": "54321-987",
      "street": "Av. Sem Email",
      "number": "321",
      "neighborhood": "Bairro Sem Email",
      "city": "Fortaleza",
      "state": "CE"
    }
  }
  ```

- Edição de fornecedor:
  ```http
  PUT api/suppliers/{supplier}
  { ... mesmo corpo do cadastro ... }
  ```

- Exclusão de fornecedor:
  ```http
  DELETE api/suppliers/{supplier}
  ```

- Listagem de fornecedores:
  ```http
  GET api/suppliers
  ```
  Filtros: `city`, `state`, `document`  
  Ordenação: `name`, `document`, `email`, `phone`, `created_at`

- Buscar CNPJ via BrasilAPI:
  ```http
  GET api/cnpj/{cnpj}/fetch
  Salvo em cache evitando buscar novamente o mesmo cnpj excedendo o limite da APi
  ```
