<?php

/**
 * Class ilPanoptoPageComponentPluginGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPanoptoPageComponentPluginGUI extends ilPageComponentPluginGUI {

    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilTemplate
     */
    protected $tpl;
    /**
     * @var ilPanoptoPageComponentPlugin
     */
    protected $pl;


    /**
     * ilPanoptoPageComponentPluginGUI constructor.
     */
    public function __construct() {
        global $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->lng = $DIC->language();
        $this->pl = new ilPanoptoPageComponentPlugin();
    }

    function executeCommand() {
        try {
            $next_class = $this->ctrl->getNextClass();
            $cmd = $this->ctrl->getCmd();

            switch ($next_class) {
                default:
                    $this->$cmd();
                    break;
            }
        } catch (xvmpException $e) {
            ilUtil::sendFailure($e->getMessage(), true);
            $this->ctrl->returnToParent($this);
        }
    }

    function insert() {
        // TODO: Implement insert() method.
    }

    function edit() {
        // TODO: Implement edit() method.
    }

    function create() {
        // TODO: Implement create() method.
    }

    function getElementHTML($a_mode, array $a_properties, $plugin_version) {
        // TODO: Implement getElementHTML() method.
    }

}