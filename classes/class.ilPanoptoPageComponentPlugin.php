<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilPanoptoPageComponentPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPanoptoPageComponentPlugin extends ilPageComponentPlugin {

    function isValidParentType($a_type) {
        return true;
    }

    function getPluginName() {
        return "PanoptoPageComponent";
    }

    public function getCssFiles($a_mode)
    {
        return ['templates/default/page.css'];
    }

}
