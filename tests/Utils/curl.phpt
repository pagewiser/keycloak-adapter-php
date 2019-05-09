<?php
    /**
     * Created by PhpStorm.
     * User: miroslav
     * Date: 23/04/2019
     * Time: 09:11
     */

    require __DIR__ . "/../bootstrap.php";

    use Tester\Assert;


    $response = \Ataccama\Utils\Curl::post("https://KC_URL/auth/realms/REAL_NAME/protocol/openid-connect/token",
        [
            "Content-Type" => "application/x-www-form-urlencoded"
        ], [
            'grant_type'   => 'authorization_code',
            'code'         => "NO_AUTHORIZATION_CODE",
            'client_id'    => "",
            'redirect_uri' => "https://"
        ]);

    Assert::same("invalid_grant", $response->body->error);
    Assert::same("Code not valid", $response->body->error_description);