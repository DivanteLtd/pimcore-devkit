<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        5/12/2017 11:53
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\User;

/**
 * Class UserService
 *
 * @package PimcoreDevkitBundle\Service
 */
class UserService
{
    /**
     * @param string $username
     */
    public function deleteUser($username)
    {
        $user = self::getUser($username);
        if (null !== $user) {
            $user->delete();
        }
    }

    /**
     * @param array $params
     * @param array $permissions
     * @return User
     */
    public function createUser(array $params, array $permissions = null)
    {
        $username = $params['username'];
        $user = self::getUser($username);
        if (null === $user) {
            $user = User::create($params);
        }

        $user->setValues($params);
        if (null !== $permissions) {
            $user->setPermissions($permissions);
        }
        $user->save();

        return $user;
    }

    /**
     * @param string $name
     * @return null|User
     */
    public function getUser($name)
    {
        try {
            $user = User::getByName($name);
            if ($user instanceof User) {
                return $user;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
