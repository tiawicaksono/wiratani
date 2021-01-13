<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    public static function getMenu()
    {
        $findAllMenu = Menu::where('parent_id', 0)
            ->where('menu_status', 1)
            ->orderBy('menu_sort', 'asc')
            ->get();
        foreach ($findAllMenu as $dataMenu) {
            if ($dataMenu->menu_condition != NULL) {
                $conditionMenu = $dataMenu->menu_condition;
            } else {
                $result["menu"][$dataMenu->menu_id] = $dataMenu;
                $conditionMenu = NULL;
            }
            $evalMenu = sprintf("%s", $conditionMenu);
            if (eval($evalMenu)) {
                $result["menu"][$dataMenu->menu_id] = $dataMenu;
            } else {
                $result["menu"][$dataMenu->menu_id] = $dataMenu;
            }

            $findAllSubMenu = Menu::where('parent_id', $dataMenu->menu_id)
                ->where('menu_status', 1)
                ->orderBy('menu_sort', 'asc')
                ->get();
            foreach ($findAllSubMenu as $dataSubMenu) :
                $result["sub_menu"][$dataMenu->menu_id][] = $dataSubMenu;
            endforeach;
        }
        return $result;
    }
}
