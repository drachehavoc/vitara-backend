<?php return function () {
        $prep = new Helper\Prepare($this);
        $model = new Helper\Access\RawModel();
        $prep->payload('usr', 'login', 'any')('pwd', 'password', 'any')('app', 'hash', 'any');
        $input = $prep->getData();
        $user = $model->findUserByLoginAndPass($input->login, $input->password);
        $application = $model->findApplicationByHash($input->hash);
        $token = $model->findLastTokenUserXAapplication($user->id, $application->id);
        return $token === null
                ? $model->createToken($user->id, $application->id)
                : $model->updateToken($token->token);
};