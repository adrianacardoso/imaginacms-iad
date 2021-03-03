<?php

namespace Modules\Iad\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterIadSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('iad::iads.title.iads'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('iad::categories.title.categories'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.iad.category.create');
                    $item->route('admin.iad.category.index');
                    $item->authorize(
                        $this->auth->hasAccess('iad.categories.index')
                    );
                });
                $item->item(trans('iad::ads.title.ads'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.iad.ad.create');
                    $item->route('admin.iad.ad.index');
                    $item->authorize(
                        $this->auth->hasAccess('iad.ads.index')
                    );
                });
                $item->item(trans('iad::fields.title.fields'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.iad.field.create');
                    $item->route('admin.iad.field.index');
                    $item->authorize(
                        $this->auth->hasAccess('iad.fields.index')
                    );
                });
                $item->item(trans('iad::schedules.title.schedules'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.iad.schedule.create');
                    $item->route('admin.iad.schedule.index');
                    $item->authorize(
                        $this->auth->hasAccess('iad.schedules.index')
                    );
                });
// append




            });
        });

        return $menu;
    }
}
