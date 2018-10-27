<?php

namespace Helper;

// HTTP METHOD	CRUD	                ENTIRE COLLECTION (E.G. /USERS)	                                                                    SPECIFIC ITEM (E.G. /USERS/123)
// POST	        Create	                201 (Created), ‘Location’ header with link to /users/{id} containing new ID.	                    Avoid using POST on single resource
// GET	        Read	                200 (OK), list of users. Use pagination, sorting and filtering to navigate big lists.	            200 (OK), single user. 404 (Not Found), if ID not found or invalid.
// PUT	        Update/Replace	        404 (Not Found), unless you want to update every resource in the entire collection of resource.	    200 (OK) or 204 (No Content). Use 404 (Not Found), if ID not found or invalid.
// PATCH	    Partial Update/Modify	404 (Not Found), unless you want to modify the collection itself.	                                200 (OK) or 204 (No Content). Use 404 (Not Found), if ID not found or invalid.
// DELETE	    Delete	                404 (Not Found), unless you want to delete the whole collection — use with caution.	                200 (OK). 404 (Not Found), if ID not found or invalid.

class Access
{
    private $context = [];
    private $user = null;
    private $app = null;
    private $token = [];

    private function select($query, $data = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        return $stmt->fetchAll();
    }

    private function query($query, $data = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }

    private function createHash()
    {
        return bin2hex(random_bytes(16));
    }

    function __construct($token = null)
    {
        $this->token = (Object)['hash' => $token];
        $this->pdo = new \Helper\PDO(["database" => "access"]);
    }

    function checkActionPermission($action, $token)
    {
        return __FUNCTION__ . ':' . $action . ', ' . $token;
    }

    function findUserByCredentials($user, $pass)
    {
        if ($this->user)
            return $this->user;

        $data = $this->select("SELECT id, login FROM user WHERE login=? AND pass=? LIMIT 1", [$user, $pass]);

        if (empty($data))
            throw new \Exception('Usuário não encontrado');

        return $this->user = $data[0];
    }

    function findApplicationByHash($hash)
    {
        if ($this->app)
            return $this->app;

        $data = $this->select('SELECT id, name, hash FROM application WHERE hash=?', [$hash]);

        if (empty($data))
            throw new \Exception('Aplicação não encontrado');

        return $this->app = $data[0];
    }

    function findUser($token)
    {
        return __FUNCTION__ . ':' . $token;
    }

    function getToken($token = null)
    {
        if ($token !== null)
            $this->token = (Object)['hash' => $token];

        if ($this->token->validated ?? false)
            return $this->token;

        $data = $this->select("SELECT id FROM token WHERE hash=? LIMIT 1", [$this->token->hash]);

        if (empty($data))
            return null;

        $this->token->id = $data[0]->id;
        $this->token->validated = true;

        return $this->token;
    }

    function getUserLastApplicationToken($user, $app)
    {
        $data = $this->select("SELECT id, hash FROM token WHERE user_id=? AND application_id=?  ORDER BY updated LIMIT 1", [$user->id, $app->id]);

        if (empty($data))
            return null;

        $this->token->id = $data[0]->id;
        $this->token->hash = $data[0]->hash;
        $this->token->validated = true;

        return $this->token;
    }

    function createOrUpdateToken($user, $pass, $appHash, $token = null)
    {
        if ($token) {
            return $this->updateToken($hash);

            $user = $this->findUserByCredentials($user, $pass);
            $app = $this->findApplicationByHash($appHash);

            if ($token = $this->getUserLastApplicationToken($user, $app))
                return $this->updateToken($token->hash);
        }

        $hash = $this->createHash();

        $stmt = $this->query('INSERT INTO token(user_id, application_id, hash) VALUES(?, ?, ?)', [$user->id, $app->id, $hash]);

        $this->token = (Object)[
            'id' => $this->pdo->lastInsertId(),
            'hash' => $hash,
            'validated' => true
        ];

        return $this->token->hash;
    }

    function updateToken($tokenHash = null)
    {
        $newTokenHash = $this->createHash();

        print_r([
            $tokenHash,
            $newTokenHash
        ]);

        $stmt = $this->query("UPDATE token SET hash=?, updated=NOW() WHERE hash=? LIMIT 1", [
            $newTokenHash,
            $tokenHash
        ]);

        if ($x = $stmt->rowCount())
            return $newTokenHash;

        throw new \Exception('Token não encontrado para atualização');
    }

    function deleteToken($token)
    {
        return __FUNCTION__ . ':' . $token;
    }

    static function route($context)
    {
        $path = $context->path;
        $token = $_SERVER['QUERY_STRING'] ?? null;
        $access = new self($token);

        if ($path === 'GET/user')
            return $access->findUser($token);

        if (preg_match('{GET/auth/(?<action>.*)}', $path, $inp))
            return $access->checkActionPermission($inp['action'], $token);

        if ($path === 'PUT/token')
            return $access->updateToken($token);

        if ($path === 'POST/token') {
            $data = \Helper\Prepare::verify($context)
                ->payload('user', null, 'string', true)
                ->payload('pass', null, 'string', true)
                ->payload('app', null, 'string', true)
                ->getData();
            return $access->createOrUpdateToken($data->user, $data->pass, $data->app, $token);
        }

        if ($path === 'DELETE/token')
            return $access->deleteToken($token);

        return '?';
    }
}