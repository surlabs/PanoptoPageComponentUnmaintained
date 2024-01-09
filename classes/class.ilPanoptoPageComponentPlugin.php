<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilPanoptoPageComponentPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPanoptoPageComponentPlugin extends ilPageComponentPlugin {

    function isValidParentType($a_type): bool
    {
        return true;
    }

    function getPluginName(): string
    {
        return "PanoptoPageComponent";
    }

    public function getCssFiles($a_mode): array
    {
        return ['templates/default/page.css'];
    }
    public static function getInstance() : self
    {
        GLOBAL $DIC;
        /** @var ilComponentFactory $component_factory */
        $component_factory = $DIC["component.factory"];
        return $component_factory->getPlugin('ppco');
    }

}
