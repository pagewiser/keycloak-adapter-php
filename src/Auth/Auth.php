<?php
    /**
     * Created by PhpStorm.
     * User: miroslav
     * Date: 23/04/2019
     * Time: 14:51
     */

    namespace Ataccama\Auth;


    use Ataccama\Adapters\Keycloak;
    use Ataccama\Utils\AuthorizationResponse;
    use Ataccama\Utils\KeycloakAPI;
    use Ataccama\Utils\RefreshToken;


    abstract class Auth
    {
        /** @var Keycloak */
        protected $keycloak;

        /**
         * Authorizes and returns user's profile.
         *
         * @param string|null $authorizationCode
         * @return array
         * @throws \Ataccama\Exceptions\CurlException
         * @throws \Ataccama\Exceptions\UnknownError
         */
        public function authorize(string $authorizationCode = null): array
        {
            if (empty($authorizationCode)) {
                return [];
            }

            $this->beforeAuthorization();

            $response = KeycloakAPI::getAuthorization($this->keycloak, $authorizationCode);
            $this->setAuthorized(true);

            return $this->getUserProfile($response);
        }

        private function beforeAuthorization()
        {
            $this->keycloak->redirectUri = $this->getRedirectUri();
        }

        /**
         * Authorizes and returns user's profile.
         *
         * @param RefreshToken $refreshToken
         * @return array
         * @throws \Ataccama\Exceptions\NotDefined
         */
        public function invokeForceAuthorization(RefreshToken $refreshToken): array
        {
            $this->beforeAuthorization();
            try {
                $response = KeycloakAPI::reauthorize($this->keycloak, $refreshToken);
                $this->setAuthorized(true);
            } catch (\Exception $e) {
                header("Location: " . $this->keycloak->getLoginUrl());
                exit();
            }

            return $this->getUserProfile($response);
        }

        /**
         * @param AuthorizationResponse $response
         * @return array
         */
        protected function getUserProfile(AuthorizationResponse $response): array
        {
            $userIdentity = $response->accessToken->getUserIdentity();
            $profile = [
                "id"                     => $userIdentity->getId(),
                "name"                   => $userIdentity->getName(),
                "email"                  => $userIdentity->getEmail(),
                "roles"                  => $userIdentity->getRoles($this->keycloak->clientId),
                "refreshToken"           => $response->refreshToken->refreshToken,
                "refreshTokenExpiration" => $response->refreshToken->expiration
            ];

            return $profile;
        }

        /**
         * @param RefreshToken $refreshToken
         * @throws \Ataccama\Exceptions\CurlException
         */
        public function logoutSSO(RefreshToken $refreshToken)
        {
            KeycloakAPI::logout($this->keycloak, $refreshToken);
        }

        /**
         * @return bool
         */
        abstract public function isAuthorized(): bool;

        /**
         * @param bool $authorized
         * @return mixed
         */
        abstract protected function setAuthorized(bool $authorized);

        /**
         * @return string
         */
        abstract protected function getRedirectUri(): string;
    }