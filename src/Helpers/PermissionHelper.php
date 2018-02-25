<?php

namespace Solspace\Commons\Helpers;

use yii\web\ForbiddenHttpException;

class PermissionHelper
{
    /**
     * Checks a given permission for the currently logged in user
     *
     * @param string $permissionName
     * @param bool   $checkForNested - see nested permissions for matching permission name root
     *
     * @return bool
     */
    public static function checkPermission(string $permissionName, bool $checkForNested = false): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        $user           = \Craft::$app->getUser();
        $permissionName = strtolower($permissionName);

        if (self::permissionsEnabled()) {
            if ($checkForNested) {
                if (!$user->getId()) {
                    return false;
                }

                $permissionList = \Craft::$app->userPermissions->getPermissionsByUserId($user->getId());
                foreach ($permissionList as $permission) {
                    if (strpos($permission, $permissionName) === 0) {
                        return true;
                    }
                }
            }

            return $user->checkPermission($permissionName);
        }

        return false;
    }

    /**
     * @param string $permissionName
     *
     * @return void
     * @throws ForbiddenHttpException
     */
    public static function requirePermission(string $permissionName)
    {
        if (self::isAdmin()) {
            return;
        }

        $user           = \Craft::$app->getUser();
        $permissionName = strtolower($permissionName);

        if (!$user->checkPermission($permissionName)) {
            throw new ForbiddenHttpException('User is not permitted to perform this action');
        }
    }

    /**
     * Fetches all nested allowed permission IDs from a nested permission set
     *
     * @param string $permissionName
     *
     * @return array|bool
     */
    public static function getNestedPermissionIds(string $permissionName)
    {
        if (self::isAdmin()) {
            return true;
        }

        $user           = \Craft::$app->getUser();
        $permissionName = strtolower($permissionName);
        $idList         = [];

        if (self::permissionsEnabled()) {
            if (!$user->getId()) {
                return [];
            }

            $permissionList = \Craft::$app->userPermissions->getPermissionsByUserId($user->getId());
            foreach ($permissionList as $permission) {
                if (strpos($permission, $permissionName) === 0) {
                    if (strpos($permission, ':') === false) {
                        continue;
                    }

                    list($name, $id) = explode(':', $permission);

                    $idList[] = $id;
                }
            }

            return $idList;
        }

        return self::isAdmin();
    }

    /**
     * Combines a nested permission with ID
     *
     * @param string $permissionName
     * @param int    $id
     *
     * @return string
     */
    public static function prepareNestedPermission($permissionName, $id): string
    {
        return $permissionName . ':' . $id;
    }

    /**
     * Returns true if the currently logged in user is an admin
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        if (self::isConsole()) {
            return true;
        }

        return \Craft::$app->getUser()->getIsAdmin();
    }

    /**
     * @return bool
     */
    private static function isConsole(): bool
    {
        return \Craft::$app->request->getIsConsoleRequest();
    }

    /**
     * @return bool
     */
    private static function permissionsEnabled(): bool
    {
        $edition = \Craft::$app->getEdition();

        return \in_array($edition, [\Craft::Pro, \Craft::Client], true);
    }
}

