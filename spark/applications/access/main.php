<?php return function () {

    Core\Autoload
        ::getInstance()
        ->addPath(__DIR__);

    Core\Config
        ::getInstance()
        ->load(__DIR__ . DS . 'access.config.php', 'access');

    $pdo = new \Helper\PDO('access', 'pdo');

    // MAPA DE ROTAS

    \Helper\Route::createInstance()
    
        // CRIAR TOKEN
        ->add('{POST/}', function () use ($pdo) {
            $inp = new \Helper\Input();

            $data = $inp->filter([
                "user" => Helper\Input\Filter::username(false),
                "password" => Helper\Input\Filter::password(false),
                "application" => Helper\Input\Filter::application(false)
            ]);

            $stmt = $pdo->prepare('
                SELECT 
                    token.hash 
                FROM 
                    token 
                    JOIN user ON user.id = user_id
                    JOIN application ON application.id = application_id
                WHERE
                    username=:user
                    AND password=:password
                    AND application.hash=:application
                LIMIT
                    1
            ');

            $stmt->execute($data);
            $find = $stmt->fetchAll();

            if (isset($find[0]) && property_exists($find[0], 'hash'))
                return $find[0]->hash;
        
            $hash = bin2hex(random_bytes(20));

            

            return $hash;
            
        })

        // ATUALIZAR TOKEN
        ->add('{PUT/(?<token>.+)}', function () {
            return 'ATUALIZAR TOKEN';
        })

        // BUSCAR DADOS DO USUÁRIO POR TOKEN
        ->add('{GET/(?<token>.+)}', function () {
            return 'BUSCAR DADOS DO USUÁRIO POR TOKEN';
        })

        // VERIFICAR PERMISSÃO
        ->add('{GET/permission/(?<permission>.+)/(?<token>.+)}', function () {
            return 'VERIFICAR PERMISSÃO';
        })

        // REVOGAR TOKEN
        ->add('{DELETE/(?<token>.+)}', function () {
            return 'REVOGAR TOKEN';
        })

        // CASOS OMISSOS
        ->add('{.*}', function () {
            return 'SITUAÇÃO NÃO TRATADA';
        })

        // 
        ->go(false);

    //
    return null;
};