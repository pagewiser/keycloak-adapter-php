# Keycloak PHP adapter

[![Latest Stable Version](https://poser.pugx.org/ataccama/keycloak-adapter/v/stable)](https://packagist.org/packages/ataccama/keycloak-adapter) [![Total Downloads](https://poser.pugx.org/ataccama/keycloak-adapter/downloads)](https://packagist.org/packages/ataccama/keycloak-adapter) [![License](https://poser.pugx.org/ataccama/keycloak-adapter/license)](https://packagist.org/packages/ataccama/keycloak-adapter) [![Monthly Downloads](https://poser.pugx.org/ataccama/keycloak-adapter/d/monthly)](https://packagist.org/packages/ataccama/keycloak-adapter)

## Install
`composer require ataccama/keycloak-adapter`

Neon config:
```
parameters:
    keycloak:
        realm: your_realm
        client_id: your_client_id
        rootUri: https://your.keycloak.com
        defaultRedirectUri: https://your.default.url
        api:
            username: your_username
            password: your_password
            client_id: your_api_client_id
            client_secret: your_client_secret
            
services:
    - Ataccama\Adapters\Keycloak(%keycloak%)
```

## Use
Create new class and extend class Ataccama\Auth, then you MUST implement all missing methods with your own logic.

In code use your class like this:
`$yourAuthClass->authorize($_GET['code'])`

Login URL:
`$loginUrl = $yourAuthClass->getLoginUrl()`