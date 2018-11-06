<?php return function () {
    $prep = new Helper\Prepare($this);
    $model = new Helper\Access\RawModel();
    $prep->matches('token', null, 'any')('slug', null, 'any');
    $input = $prep->getData();
    $token = $model->findToken($input->token);
    $user = $model->findUserPermission($token->user_id, $token->application_id, $input->slug);
    $group = $model->findGroupPermission($token->user_id, $token->application_id, $input->slug);
    return array_merge((Array)$user, (Array)$group);
};