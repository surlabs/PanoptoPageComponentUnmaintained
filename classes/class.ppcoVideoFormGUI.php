<?php

/**
 * Class ppcoVideoFormGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ppcoVideoFormGUI extends ilPropertyFormGUI {

    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var ilPanoptoPageComponentPluginGUI
     */
    protected $parent_gui;

    /**
     * ppcoVideoFormGUI constructor.
     */
    public function __construct(ilPanoptoPageComponentPluginGUI $parent_gui) {
        parent::__construct();

        global $DIC;
        $this->lng = $DIC->language();
        $this->id = 'xpan_embed';
        $button = ilButton::getInstance();
        $button->setCaption('add');
        $button->setOnClick("$('#xpan_modal').modal('show');") ;
        $DIC->toolbar()->addButtonInstance($button);

        $this->parent_gui = $parent_gui;

        $this->setFormAction($DIC->ctrl()->getFormAction($parent_gui));
        $this->initForm();
    }

    protected function initForm() {
        $this->addCommandButton(ilPanoptoPageComponentPluginGUI::CMD_CREATE, $this->lng->txt('create'));
    }
}