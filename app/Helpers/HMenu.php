<?php

namespace App\Helpers;

use App\Model\Menu;

class HMenu
{
    public static function renderContent()
    {
        $menu = Menu::getMenu();
        return view('layouts/left_menu', array('menu' => $menu));
    }
}
