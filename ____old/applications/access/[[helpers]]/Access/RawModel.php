<?php

namespace Helper\Access;

class RawModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new \Helper\Relational\PDO(["database" => "access"]);
    }

    function findUserByLoginAndPass($login, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE login=? AND password=? LIMIT 1");
        $stmt->execute([$login, $password]);
        $user = $stmt->fetchAll();
        if (empty($user))
            throw new \Exception('User not found.');
        return $user[0];
    }

    function findApplicationByHash($hash)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM application WHERE hash=? LIMIT 1');
        $stmt->execute([$hash]);
        $application = $stmt->fetchAll();
        if (empty($application))
            throw new Exception("Application not found.");
        return $application[0];
    }

    function findLastTokenUserXAapplication($user, $application)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM token WHERE user_id=? AND application_id=? LIMIT 1");
        $stmt->execute([$user, $application]);
        $token = $stmt->fetchAll();
        $token = empty($token) ? null : $token[0];
    }

    function findToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, application_id FROM token WHERE token=? LIMIT 1");
        $stmt->execute([$token]);
        $data = $stmt->fetchAll();
        if (empty($data))
            throw new \Exception("Token not found.");
        return $data[0];
    }

    function createToken($user, $application)
    {
        $hash = bin2hex(random_bytes(16));
        $stmt = $this->pdo->prepare("INSERT INTO token(user_id, application_id, token) VALUES(?, ?, '{$hash}')");
        $stmt->execute([$user, $application]);
        return $hash;
    }

    function updateToken($token)
    {
        $hash = bin2hex(random_bytes(16));
        $stmt = $this->pdo->prepare("UPDATE token SET token='{$hash}', updated=NOW() WHERE token=?");
        $stmt->execute([$token]);
        if (!(bool)$stmt->rowCount())
            throw new \Exception("Token not found.");
        return $hash;
    }

    function deleteToken($token)
    {
        $stmt = $this->pdo->prepare("DELETE FROM token WHERE token=? LIMIT 1");
        $stmt->execute([$token]);
        return (bool)$stmt->rowCount();
    }

    function findUserPermission($user = false, $application = false, $slug = false)
    {
        $columns = [];

        if ($user !== false)
            $columns['user_id'] = $user;

        if ($application !== false)
            $columns['application_id'] = $application;

        if (!empty($slug))
            $columns['slug'] = $slug;

        $sql = "
            SELECT
                -1 as 'group_id',
                permission.id,
                permission.name,
                permission.slug,
                permission.description
            FROM 
                user_x_permission 
                INNER JOIN permission ON permission_id = permission.id
            WHERE 
        ";

        if (!empty($columns))
            $sql .= "    " . implode("=? AND ", array_keys($columns)) . "=?";
        else
            $sql .= 1;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($columns));
        $data = $stmt->fetchAll();
        return empty($data) ? null : $data;
    }

    function findGroupPermission($user = false, $application = false, $slug = false)
    {
        $columns = [];

        if ($user !== false)
            $columns['user_id'] = $user;

        if ($application !== false)
            $columns['application_id'] = $application;

        if (!empty($slug))
            $columns['slug'] = $slug;

        $sql = "
            SELECT 
                uxg.group_id,
                permission.id,
                permission.name,
                permission.slug,
                permission.description
            FROM 
                user_x_group uxg
                LEFT JOIN group_x_permission gxp ON gxp.group_id = uxg.group_id
                LEFT JOIN permission ON permission.id = gxp.permission_id
            WHERE 
        ";

        if (!empty($columns))
            $sql .= "    " . implode("=? AND ", array_keys($columns)) . "=?";
        else
            $sql .= 1;


        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($columns));
        $data = $stmt->fetchAll();
        return empty($data) ? null : $data;
    }
}