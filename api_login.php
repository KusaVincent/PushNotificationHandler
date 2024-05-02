<?php

function apiLogin(string $user_name, string $password) : bool
{
    return $_ENV['USER_NAME'] == $user_name && password_verify($password, $_ENV['PASSWORD']);
}