<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.dashboard'),
            'url' => '',
            'route' => 'voyager.dashboard',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-boat',
                'color' => null,
                'parent_id' => null,
                'order' => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.media'),
            'url' => '',
            'route' => 'voyager.media.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => null,
                'parent_id' => null,
                'order' => 5,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.users'),
            'url' => '',
            'route' => 'voyager.users.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => null,
                'parent_id' => null,
                'order' => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.roles'),
            'url' => '',
            'route' => 'voyager.roles.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => null,
                'parent_id' => null,
                'order' => 2,
            ])->save();
        }

        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.tools'),
            'url' => '',
        ]);
        if (!$toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => null,
                'parent_id' => null,
                'order' => 9,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.menu_builder'),
            'url' => '',
            'route' => 'voyager.menus.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => null,
                'parent_id' => $toolsMenuItem->id,
                'order' => 10,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.database'),
            'url' => '',
            'route' => 'voyager.database.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => null,
                'parent_id' => $toolsMenuItem->id,
                'order' => 11,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.compass'),
            'url' => '',
            'route' => 'voyager.compass.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => null,
                'parent_id' => $toolsMenuItem->id,
                'order' => 12,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.bread'),
            'url' => '',
            'route' => 'voyager.bread.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => null,
                'parent_id' => $toolsMenuItem->id,
                'order' => 13,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => __('voyager::seeders.menu_items.settings'),
            'url' => '',
            'route' => 'voyager.settings.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => null,
                'parent_id' => null,
                'order' => 14,
            ])->save();
        }

        $menu = Menu::where('name', 'telegram')->firstOrFail();
        $deputyMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Депутати 👥',
            'url' => '',
            'route' => '',
        ]);
        if (!$deputyMenuItem->exists) {
            $deputyMenuItem->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => null,
                'order' => 1,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Інформація 🏷',
            'url' => '',
            'route' => '',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => null,
                'order' => 2,
            ])->save();
        }
        $transport = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Транспорт 🚍',
            'url' => '',
            'route' => '',
        ]);
        if (!$transport->exists) {
            $transport->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => null,
                'order' => 3,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Список депутатів',
            'url' => '',
            'route' => '',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => $deputyMenuItem->id,
                'order' => 1,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Знайти депутата по округу',
            'url' => '',
            'route' => '',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => $deputyMenuItem->id,
                'order' => 2,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Графік маршруту 🗓',
            'url' => '',
            'route' => '',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => $transport->id,
                'order' => 1,
            ])->save();
        }
        $handbook = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => 'Довідник 📙',
            'url' => '',
            'route' => '',
        ]);
        if (!$handbook->exists) {
            $handbook->fill([
                'target' => '_self',
                'icon_class' => '',
                'color' => null,
                'parent_id' => null,
                'order' => 4,
            ])->save();
        }
    }
}
