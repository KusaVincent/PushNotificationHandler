<?php

function apiLogin(string $user_name, string $password): bool
{
    if (!getEnvVariables('authenticate'))
        return true;

    return getEnvVariables('user_name') == $user_name && password_verify($password, getEnvVariables('password'));
}