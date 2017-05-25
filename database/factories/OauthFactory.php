<?php

$factory->define(WA\DataStore\Oauth\Oauth::class, function ($faker) {
    return [
        'user_Id' => null,
        'name' => 'Personal Access Client',
        'secret' => 'Abk5iAKZiXVxt5sQdvmHzIzhjpFr7TwOiGhsPYsP',
        'redirect' => 'http://localhost',
        'personal_access_client' => 0,
        'password_client' => 1,
        'revoked' => 0,
    ];
});
