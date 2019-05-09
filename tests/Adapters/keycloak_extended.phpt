<?php
    /**
     * Created by PhpStorm.
     * User: miroslav
     * Date: 23/04/2019
     * Time: 10:06
     */

    require __DIR__ . "/../bootstrap.php";

    use Tester\Assert;


    $keycloakExtended = new \Ataccama\Adapters\KeycloakExtended([
        "host"     => "",
        "realmId"  => "",
        "clientId" => "",
        "api"      => [
            "clientId"     => "",
            "clientSecret" => "",
            "username"     => "",
            "password"     => ""
        ]
    ]);

    $apiAccessToken = $keycloakExtended->apiAccessToken;
    $userIdentity = $apiAccessToken->getUserIdentity();

    Assert::same(3, count($userIdentity->getRoles("account")));

    Assert::same(false, $keycloakExtended->hasApiAccessTokenExpired());

    // user creation
    // uncomment it to test

    //    $email = "email" . rand(10, 99) . "@email.com";
    //    $success = \Ataccama\Utils\KeycloakAPI::createUser($keycloakExtended, $email, "first", "second", $email);
    //    Assert::same(true, $success);

    // logout
    //    $success = \Ataccama\Utils\KeycloakAPI::logout($keycloakExtended, $keycloakExtended->apiRefreshToken);
    //    Assert::same(true, $success);

    $succes = \Ataccama\Utils\KeycloakAPI::userExists($keycloakExtended, "email@email.com);
    Assert::same(true, $succes);

    $succes = \Ataccama\Utils\KeycloakAPI::userExists($keycloakExtended, "xyz@email.com");
    Assert::same(false, $succes);