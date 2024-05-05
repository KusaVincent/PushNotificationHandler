<?php

function apiLogin(string $user_name, string $password) : bool
{
    return getEnvVariables('user_name') == $user_name && password_verify($password, getEnvVariables('password'));
}