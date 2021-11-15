<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 14.07.2018
 * Time: 13:51
 */

namespace Core\Panel\Package\Ajax;

use Core\Panel\Infrastructure\PanelAjaxController;
use Core\Panel\Package\Package;

class PackageAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('package', 'show');
        $user = new Package();
        return $user->getDataTable($options);
    }
}