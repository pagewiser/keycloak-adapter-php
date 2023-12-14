<?php
    require __DIR__ . '/../vendor/autoload.php';

    Tester\Environment::setup();
    date_default_timezone_set('Europe/Prague');

    // type into terminal to start: vendor/bin/tester tests/


    // TODO: set your file here for testing
    //$filename = 'keycloak_settings_template.json';
    $filename = 'keycloak_settings.json';

    $_keycloakSettings = json_decode(file_get_contents(__DIR__ . "/" . $filename) ?? '');
    $settings = [];
    foreach ($_keycloakSettings as $key => $val) {
        if ($val instanceof stdClass) {
            $settings[$key] = (array) $val;
        } else {
            $settings[$key] = $val;
        }
    }

    define("KEYCLOAK", $settings);