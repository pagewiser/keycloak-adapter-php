<?php
    /**
     * Created by PhpStorm.
     * User: miroslav
     * Date: 23/04/2019
     * Time: 14:51
     */

    namespace Ataccama\Auth;


    use Ataccama\Adapters\Keycloak;
    use Ataccama\Adapters\Utils\UserProfile;
    use Ataccama\Utils\AuthorizationResponse;
    use Ataccama\Utils\KeycloakAPI;
    use Ataccama\Utils\RefreshToken;
    use Ataccama\Utils\UserIdentity;


    abstract class Auth
    {
        /** @var Keycloak */
        protected $keycloak;

        /**
         * Authorizes and returns user's profile.
         *
         * @param string|null $authorizationCode
         * @return bool
         * @throws \Ataccama\Exceptions\CurlException
         * @throws \Ataccama\Exceptions\UnknownError
         */
        public function authorize(string $authorizationCode = null): bool
        {
            if (empty($authorizationCode)) {
                return false;
            }

            $this->beforeAuthorization();

            $response = KeycloakAPI::getAuthorization($this->keycloak, $authorizationCode);

            // triggers
            $this->setAuthorized(true);
            $this->authorized($this->getUserProfile($response));

            return true;
        }

        private function beforeAuthorization()
        {
            $this->keycloak->redirectUri = $this->getRedirectUri();
        }

        /**
         * Authorizes and returns user's profile.
         *
         * @param RefreshToken $refreshToken
         * @return bool
         * @throws \Ataccama\Exceptions\NotDefined
         */
        public function invokeForceAuthorization(RefreshToken $refreshToken): bool
        {
            $this->beforeAuthorization();
            try {
                $response = KeycloakAPI::reauthorize($this->keycloak, $refreshToken);
                $this->setAuthorized(true);
                $this->authorized($this->getUserProfile($response));
            } catch (\Exception $e) {
                header("Location: " . $this->keycloak->getLoginUrl());
                exit();
            }

            return true;
        }

        /**
         * @param AuthorizationResponse $response
         * @return UserProfile
         */
        private function getUserProfile(AuthorizationResponse $response): UserProfile
        {
            $userIdentity = $response->accessToken->getUserIdentity();

            return new UserProfile($userIdentity->getId(), $userIdentity->getName(), $userIdentity->getEmail(),
                $response->refreshToken->refreshToken, $response->refreshToken->expiration,
                $userIdentity->getRoles($this->keycloak->clientId), $userIdentity->username);
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
         * @return bool
         */
        abstract protected function setAuthorized(bool $authorized): bool;

        /**
         * @param UserProfile $userProfile
         * @return bool
         */
        abstract protected function authorized(UserProfile $userProfile): bool;

        /**
         * @return string
         */
        abstract protected function getRedirectUri(): string;
    }