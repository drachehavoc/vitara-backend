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
    $blog = new Blog();
    $route = \Helper\Route::createInstance();

    $route->add('|^GET/artigo(/(?<artigo>[0-9])*){0,1}$|i', $blog->execute('buscarArtigo'));
    $route->add('|^POST/artigo/(?<artigo>[0-9]+)$|', $blog->execute('criarArtigo'));
    $route->add('|^PUT/artigo/(?<artigo>[0-9]+)$|', $blog->execute('alterarArtigo'));
    $route->add('|^DELETE/artigo/(?<artigo>[0-9]+)$|', $blog->execute('deletarArtigo'));

    $route->add('|^GET/comentario(/(?<comentario>[0-9])*){0,1}$|i', $blog->execute('buscarComentario'));
    $route->add('|^POST/comentario/(?<comentario>[0-9]+)$|', $blog->execute('criarComentario'));
    $route->add('|^PUT/comentario/(?<comentario>[0-9]+)$|', $blog->execute('alterarComentario'));
    $route->add('|^DELETE/comentario/(?<comentario>[0-9]+)$|', $blog->execute('deletarComentario'));

    $route->go(false);
};