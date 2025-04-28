## 🌐 Sistema Web de Gerenciamento de Imóveis

Aplicativo web desenvolvido em PHP, integrado a uma API Laravel para gestão de imóveis. A

## 🚀 Tecnologias

- PHP 
- JavaScript
- HTML
- PHPUnit 
- CSS 
## ⚙️ Requisitos

- php >= 8.1

## 📦 Instalação

```conf
# Clone o repositório
git clone https://github.com/GustaVasconcelos/property-management.git
cd property-management

# Antes de subir o projeto, crie um arquivo .env na raiz com o seguinte conteúdo: 

DB_HOST=db
DB_PORT=3306
DB_NAME=property_db
DB_USER=user
DB_PASSWORD=password

# Rode o docker e espere a instalação finalizar
docker compose up --build
```

## ▶️ Acessando o site

```conf
# Disponível na URL
http://localhost:8000/
```

## ▶️ Testes

```conf
# Acesse o bash
docker compose exec -it app bash
```

```conf
# Digite esse comando para rodar todos os testes
./vendor/bin/phpunit
```