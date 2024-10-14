<?php

namespace Modules\Menu\Classes;

use Modules\Menu\Models\Menu;

class Menus
{
    /**
     * @param $key
     * @param null $status
     * @return array
     */
    public function getMenu($key, $status = null)
    {
        $parent_menu = Menu::where('key', $key)->active()->first();
        if ($parent_menu) {
            $menus = $parent_menu->getChildren($status);
        } else {
            $menus = [];
        }
        return $menus;
    }

    public function getParents($active_key = '')
    {
        $parents = Menu::root()->active()->orderBy('order')->get();
        $pills = [];

        // set active_key if not passed
        if (empty($active_key) && $parents->count()) {
            $active_key = $parents->first()->key;
        }

        foreach ($parents as $parent) {
            array_push($pills, [
                'label' => $parent->name,
                'href' => url(config('menu.models.menu.resource_url') . 's/' . $parent->key),
                'active' => $active_key == $parent->key ? 'active' : ''
            ]);
        }

        return $pills;
    }
}
