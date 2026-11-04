<?php
namespace App\Support;

final class AccessPermissions
{
    public const MODULES = [
        'dashboard' => ['view'],
        'clients' => ['view', 'create', 'edit', 'delete'],
        'projects' => ['view', 'create', 'edit', 'delete'],
        'payments' => ['view', 'create', 'edit', 'delete'],
        'reports' => ['view'],
    ];

    public static function keys(): array
    {
        $keys = [];
        foreach (self::MODULES as $module => $actions) {
            foreach ($actions as $action) $keys[] = "$module.$action";
        }
        return $keys;
    }

    public static function normalize(array $submitted): array
    {
        $permissions = array_values(array_unique(array_intersect(self::keys(), $submitted)));
        // A user with create/edit/delete access must also be able to view that module.
        foreach (self::MODULES as $module => $actions) {
            foreach (['create','edit','delete'] as $action) {
                if (in_array("$module.$action", $permissions, true)) {
                    $permissions[] = "$module.view";
                    break;
                }
            }
        }
        return array_values(array_unique($permissions));
    }
}
