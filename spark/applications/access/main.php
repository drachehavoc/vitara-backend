<?php 

class Access
{
    private $pdo;
    private $token;


    function __construct()
    {
        $this->token = $_GET['token'] ?? null;

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

    function __get($name)
    {
        return $this->$name ?? $this->bind($name);
    }

    function route()
    {
        $self = $this;
        \Helper\Route::createInstance()

            ->add(
                '{POST/}',
                $this->routeCreateOrUpdateTokenForApplication
            )

            ->add(
                '{PUT/}',
                $this->updateToken
            )

            ->add(
                '{DELETE/}',
                $this->revokeToken
            )

            ->add(
                '{GET/}',
                $this->getBasicUserInfo
            )

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
            'SELECT id, username FROM `user` WHERE username=? AND password=? LIMIT 1',
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
        return $this->token = $hash;
    }

    function updateToken()
    {
        $newHash = $this->getHash();
        $stmt = $this->pdo->prepare('UPDATE token SET hash=?, updated=NOW() WHERE hash=? LIMIT 1');
        $stmt->execute([$newHash, $this->token]);
        if ($stmt->rowCount())
            return $this->token = $newHash;
        throw new \Exception("Token não encontrado");
    }

    function revokeToken()
    {
        $stmt = $this->pdo->prepare('DELETE FROM token WHERE hash=? LIMIT 1');
        $stmt->execute([$this->token]);
        if ($stmt->rowCount())
            return true;
        throw new \Exception("Token não encontrado");
    }

    function getBasicUserInfo()
    {
        return $this->select(
            '
                SELECT 
                    user.id,
                    username
                FROM 
                    token 
                    INNER JOIN `user` ON token.user_id = user.id
                WHERE 
                    token.hash=? 
                LIMIT 1
            ',
            $this->token
        );
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

        if (!is_null($this->token = $this->findTokenByUserIdAndApplicationId($user->id, $application->id)))
            return $this->updateTokenByUserCredentials($user->id, $application->id);

        return $this->createToken($user->id, $application->id);
    }

    function checkPermission($permissionSlug)
    {
        $user_has_permission = $this->select(
            '
                SELECT 
                    slug
                FROM 
                    token 
                    LEFT JOIN user_x_permission ON token.user_id = user_x_permission.user_id
                    LEFT JOIN permission ON permission_id = permission.id
                WHERE 
                    token.hash=?
                    AND slug=?
                LIMIT 1
            ',
            $this->token,
            $permissionSlug
        );

        if (!empty($user_has_permission))
            return;

        $group_has_permission = $this->select(
            '
                SELECT 
                    slug
                FROM 
                    token 
                    LEFT JOIN user_x_group ON user_x_group.user_id = token.user_id
                    LEFT JOIN group_x_permission ON group_x_permission.group_id = user_x_group.group_id
                    LEFT JOIN permission ON group_x_permission.permission_id = permission.id
                WHERE 
                    token.hash=?
                    AND slug=?
                LIMIT 1
            ',
            $_GET['token'] ?? null,
            $permissionSlug
        );

        if (!empty($group_has_permission))
            return;

        throw new \Exception("Usuário não tem permissão para a ação `${permissionSlug}`");
    }
}

return function () {
    $access = new Access();
    // $access->checkPermission("__nadar__");
    return $access->route();
};