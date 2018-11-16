<?php 

class Access
{
    private $pdo;

    function __construct()
    {
        Core\Autoload
            ::getInstance()
            ->addPath(__DIR__);

        Core\Config
            ::getInstance()
            ->load(__DIR__ . DS . 'access.config.php', 'access');

        $this->pdo = new \Helper\PDO('access', 'pdo');
    }

    function getHash()
    {
        return bin2hex(random_bytes(20));
    }

    function bind(string $fn, ...$p)
    {
        $self = $this;
        return function () use ($p, $self, $fn) {
            return $self->{$fn}(...$p);
        };
    }

    function route()
    {
        $self = $this;
        \Helper\Route::createInstance()
    
            // CRIAR TOKEN
            ->add('{POST/}', $this->bind('routeCreateOrUpdateTokenForApplication'))

            // ATUALIZAR TOKEN
            ->add('{PUT/}', $this->bind('routeUpdateToken'))
            
            // REVOGAR TOKEN
            ->add('{DELETE/}', $this->bind('routeRevokeToken'))

            // BUSCAR DADOS DO USUÁRIO POR TOKEN
            ->add('{GET/(?<token>.+)}', function () {
                return 'BUSCAR DADOS DO USUÁRIO POR TOKEN';
            })

            // VERIFICAR PERMISSÃO
            ->add('{GET/permission/(?<permission>.+)/(?<token>.+)}', function () {
                return 'VERIFICAR PERMISSÃO';
            })

            // CASOS OMISSOS
            ->add('{.*}', function () {
                return 'SITUAÇÃO NÃO TRATADA';
            })

            // 
            ->go(false);
    }

    function select($select, ...$p)
    {
        $stmt = $this->pdo->prepare($select);
        $stmt->execute($p);
        $find = $stmt->fetchAll();
        if (isset($find[0]))
            return $find[0];
        return null;
    }

    function findUserByCredentials($user, $password)
    {
        return $this->select(
            'SELECT id, username FROM user WHERE username=? AND password=? LIMIT 1',
            $user,
            $password
        );
    }

    function findApplicationByHash($hash)
    {
        return $this->select(
            'SELECT id, hash FROM application WHERE hash=? LIMIT 1',
            $hash
        );
    }

    function findTokenByUserIdAndApplicationId($user_id, $application_id)
    {
        return $this->select(
            'SELECT *  FROM  token  WHERE user_id=? AND application_id=? LIMIT 1',
            $user_id,
            $application_id
        );
    }

    function createToken($user_id, $application_id)
    {
        $hash = $this->getHash();
        $stmt = $this->pdo->prepare('INSERT INTO token(user_id, application_id, hash) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $application_id, $hash]);
        return $hash;
    }

    function updateTokenByUserCredentials($user_id, $application_id)
    {
        $hash = $this->getHash();
        $stmt = $this->pdo->prepare('UPDATE token SET hash=?, updated=NOW() WHERE user_id=? AND application_id=? LIMIT 1');
        $stmt->execute([$hash, $user_id, $application_id]);
        return $hash;
    }

    function updateToken($oldHash)
    {
        $newHash = $this->getHash();
        $stmt = $this->pdo->prepare('UPDATE token SET hash=?, updated=NOW() WHERE hash=? LIMIT 1');
        $stmt->execute([$newHash, $oldHash]);
        if ($stmt->rowCount())
            return $newHash;
        throw new \Exception("Token não encontrado");
    }

    function revokeToken($hash)
    {
        $stmt = $this->pdo->prepare('DELETE FROM token WHERE hash=? LIMIT 1');
        $stmt->execute([$hash]);
        if ($stmt->rowCount())
            return true;
        throw new \Exception("Token não encontrado");
    }

    function routeCreateOrUpdateTokenForApplication()
    {
        $inp = new \Helper\Input();

        $data = (Object)$inp->filter([
            "user" => Helper\Input\Filter::username(false),
            "password" => Helper\Input\Filter::password(false),
            "application" => Helper\Input\Filter::application(false)
        ]);

        if (is_null($user = $this->findUserByCredentials($data->user, $data->password)))
            throw new \Exception('Usuário não encontrado');

        if (is_null($application = $this->findApplicationByHash($data->application)))
            throw new \Exception('Aplicação não encontrada');

        if (!is_null($token = $this->findTokenByUserIdAndApplicationId($user->id, $application->id))) {
            $this->updateTokenByUserCredentials($user->id, $application->id);
            return $token->hash;
        }

        return $this->createToken($user->id, $application->id);
    }

    function routeUpdateToken()
    {
        $inp = new \Helper\Input();
        $data = (Object)$inp->filter([
            "token" => Helper\Input\Filter::any(false),
        ]);
        return $this->updateToken($data->token);
    }

    function routeRevokeToken()
    {
        $inp = new \Helper\Input();
        $data = (Object)$inp->filter([
            "token" => Helper\Input\Filter::any(false),
        ]);
        return $this->revokeToken($data->token);
    }
}

return function () {
    $access = new Access();
    return $access->route();
};