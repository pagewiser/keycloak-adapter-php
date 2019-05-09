<?php
    /**
     * Created by PhpStorm.
     * User: miroslav
     * Date: 23/04/2019
     * Time: 09:56
     */

    namespace Ataccama\Utils;


    use Nette\SmartObject;


    /**
     * @property-read string $email
     * @property-read string $name
     * @property-read string $id
     */
    class UserIdentity
    {
        use SmartObject;

        /** @var array */
        private $roles;

        /** @var string */
        protected $email, $name, $id;

        /**
         * UserIdentity constructor.
         * @param \stdClass $userIdentity
         */
        public function __construct(\stdClass $userIdentity)
        {
            $this->id = $userIdentity->sub;
            $this->email = $userIdentity->email;
            $this->name = $userIdentity->name;

            $this->roles = $userIdentity->resource_access;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getId(): string
        {
            return $this->id;
        }

        /**
         * @param string $clientId
         * @return array
         */
        public function getRoles(string $clientId): array
        {
            if (isset($this->roles->{"$clientId"})) {
                $roles = [];
                foreach ($this->roles->{"$clientId"}->roles as $role) {
                    $roles[] = $role;
                }

                return $roles;
            }

            return [];
        }


    }