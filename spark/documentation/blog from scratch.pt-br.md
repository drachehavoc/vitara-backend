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

Em nosso caso o arquivo terá a seguinte estrutura:

```php
<?php return [
    'paths' => [
        'localhost' => 'blog/main.php',
        '127.0.0.1' => 'blog/main.php'    
    ];
];
```
Garantindo assim que em caso do usuário requisitar a aplicação por localhost ou 127.0.0.1, ambas as requisições direcionem para o arquivo de entrada da nossa aplicação.

#### 1.1.2.2

Aqui faremos as rotas de acesso para nossa aplicação, utilizaremos o `Helper\Route` para facilitar o processo, criaremos então o arquivo `applications\blog\main.php` com o seguinte conteúdo:

```php
<?php 

Class Blog {
    function buscarArtigo(){};
    function buscarArtigos(){};
    function criarArtigo(){};
    function alterarArtigo(){};
    function deletarArtigo(){};

    function buscarComentario(){};
    function buscarComentarios(){};
    function criarComentario(){};
    function alterarComentario(){};
    function deletarComentario(){};

    function closure(){

    }
}



return function() {
    $blog = new Blog();
    $route = \Helper\Route::createInstance();

    $route->add('{GET/artigo}', $blog->closure('buscarArtigo'));
    $route->add('{GET/artigo/(?<artigo>[[0-9]]+)}', $blog->closure('buscarArtigos'));
    $route->add('{POST/artigo/(?<artigo>[[0-9]]+)}', $blog->closure('criarArtigo'));
    $route->add('{PUT/artigo/(?<artigo>[[0-9]]+)}', $blog->closure('alterarArtigo'));
    $route->add('{DELETE/artigo/(?<artigo>[[0-9]]+)}', $blog->closure('deletarArtigo'));
    
    $route->add('{GET/comentario}', $blog->closure('buscarComentario'));
    $route->add('{GET/comentario/(?<comentario>[[0-9]]+)}', $blog->closure('buscarComentarios'));
    $route->add('{POST/comentario/(?<comentario>[[0-9]]+)}', $blog->closure('criarComentario'));
    $route->add('{PUT/comentario/(?<comentario>[[0-9]]+)}', $blog->closure('alterarComentario'));
    $route->add('{DELETE/comentario/(?<comentario>[[0-9]]+)}', $blog->closure('deletarComentario'));
    
    $route->go();
};
```

### 1.2 
#### 1.2.1 
#### 1.2.2
#### 1.2.3
### 1.3

## 2.