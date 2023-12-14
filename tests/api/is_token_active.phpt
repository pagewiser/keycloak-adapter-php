<?php
    require_once './../bootstrap.php';

    $keycloakExtended = new \Ataccama\Adapters\KeycloakExtended(KEYCLOAK);

    // TODO: put a new token (Refresh Token or Access Token to this file)
    $token = file_get_contents(__DIR__ . "/../test_token");

    $retVal = \Ataccama\Utils\KeycloakAPI::isTokenActive($keycloakExtended, $token);

    \Tester\Assert::same(true, $retVal);

    $retVal = \Ataccama\Utils\KeycloakAPI::isTokenActive($keycloakExtended, "randomString");

    \Tester\Assert::same(false, $retVal);