# Sistema de Estacionamento Inteligente (Projeto-Final-SOLID)

> Um sistema de estacionamento implementado com os princípios SOLID em PHP — pensado para demonstrar boas práticas de orientação a objetos, separação de responsabilidades e arquitetura limpa.

## Visão Geral

Este projeto tem como objetivo criar um sistema simples porém bem estruturado para gerenciar um estacionamento. Ele demonstra a aplicação dos princípios SOLID (Single Responsibility, Open/Closed, Liskov, Interface Segregation, Dependency Inversion) numa aplicação real, com funcionalidades como:
- Cadastro de veículos
- Registro de entrada e saída
- Cálculo de tempo e valor de permanência
- Controle de disponibilidade de vagas  
- Persistência de dados (via banco ou estrutura simulada)  
- Extensibilidade para adicionar novas regras sem violar os princípios de design  

O intuito não é apenas fazer funcionar, mas servir como **exemplo educativo / portfólio** de boas práticas de engenharia de software em PHP.

## Tecnologias / Dependências

- PHP  
- Composer — para autoload / gerenciamento de dependências
- Ambiente de desenvolvimento local (XAMPP)

Se houver frameworks, libs ou pacotes usados, liste aqui.  

## Como Rodar / Como Configurar

### Pré-requisitos

- PHP instalado (CLI ou servidor web compatível)  
- Composer instalado  
- Se for usar banco de dados, um DB compatível  

### Passos para execução local

```bash
# Clonar o repositório
git clone https://github.com/joseHenrique346/PROJETO-FINAL-SOLID.git

# Entrar na pasta do projeto
cd PROJETO-FINAL-SOLID

# Instalar dependências
composer install

# Se você está rodando através de Apache, basta colocar o projeto dentro do diretório do servidor.
# Exemplo:
# C:/xampp/htdocs/PROJETO-FINAL-SOLID/
http://localhost/PROJETO-FINAL-SOLID/public/index.php


