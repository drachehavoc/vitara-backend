<?php 

class Blog
{
    private $route;

    function buscarArtigo()
    {
        return $this->route;
    }

    function criarArtigo()
    {
    }

    function alterarArtigo()
    {
    }

    function deletarArtigo()
    {
    }

    function buscarComentario()
    {
        return $this->route;
    }

    function criarComentario()
    {
    }

    function alterarComentario()
    {
    }

    function deletarComentario()
    {
    }

    function execute($name)
    {
        $that = $this;
        return function ($route) use ($that, $name) {
            $that->route = $route;
            return $that->{$name}();
        };
    }
}



return function () {
    // Criar objeto blog
    $blog = new Blog();
    // Criar rotas
    \Helper\Route::createInstance()
        // Artigos
        ->add('|^GET/artigo(/(?<artigo>[0-9])*){0,1}$|i', $blog->execute('buscarArtigo'))
        ->add('|^POST/artigo/(?<artigo>[0-9]+)$|', $blog->execute('criarArtigo'))
        ->add('|^PUT/artigo/(?<artigo>[0-9]+)$|', $blog->execute('alterarArtigo'))
        ->add('|^DELETE/artigo/(?<artigo>[0-9]+)$|', $blog->execute('deletarArtigo'))
        // Comentários
        ->add('|^GET/comentario(/(?<comentario>[0-9])*){0,1}$|i', $blog->execute('buscarComentario'))
        ->add('|^POST/comentario/(?<comentario>[0-9]+)$|', $blog->execute('criarComentario'))
        ->add('|^PUT/comentario/(?<comentario>[0-9]+)$|', $blog->execute('alterarComentario'))
        ->add('|^DELETE/comentario/(?<comentario>[0-9]+)$|', $blog->execute('deletarComentario'))
        // Iniciar rota
        ->go(false);
};