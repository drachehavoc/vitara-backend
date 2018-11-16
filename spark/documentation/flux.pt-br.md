# Blog do Zero

- 1 Rest API
    - 1.1 Configuração
    - 1.1.2 Rota
        - 1.1.2.1 Rota de entrada para a aplicação
        - 1.1.2.2 Rotas da API 
    - 1.2 Opções de ações CRUD da API
        - 1.2.1 Artigo
        - 1.2.2 Comentários
        - 1.2.3 Cadastrar usuários
    - 1.3 Segurança

- 2. Client
    - 2.



## 1
### 1.1 
#### 1.1.2
#### 1.1.2.1

Crie um arquivo chamado `application\gates.php` na pasta raiz da `framework`, este arquivo é responsavel por direcionar e configurar o domínio para o arquivo de entrada da aplicação.

A estrutura básica deste arquivo é a seguinte:

```php
<?php return [
    'production' => /* boleano não obrigatório */,
    'paths' => [
        (domain:string) => 
            // function, 
            // string:path to function file
            // array<function,string>)    
    ];
];
```

- production:
    - deve receber 

#### 1.1.2.2


### 1.2 
#### 1.2.1 
#### 1.2.2
#### 1.2.3
### 1.3

## 2.