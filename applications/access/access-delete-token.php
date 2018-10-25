<?php return function () {
    $prep = new Helper\Prepare($this);
    $model = new Helper\Access\RawModel();
    $prep->matches('token', null, 'any');
    $token = $prep->getData()->token;
    return $model->deleteToken($token);
};