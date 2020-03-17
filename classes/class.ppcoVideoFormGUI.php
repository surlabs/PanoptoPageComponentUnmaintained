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
     * @var ilPanoptoPageComponentPlugin
     */
    protected $pl;
    /**
     * @var array
     */
    protected $properties;

    /**
     * ppcoVideoFormGUI constructor.
     */
    public function __construct(ilPanoptoPageComponentPluginGUI $parent_gui, $properties = array()) {
        parent::__construct();

        global $DIC;
        $this->lng = $DIC->language();
        $this->id = 'xpan_embed';
        $this->pl = new ilPanoptoPageComponentPlugin();
        $this->properties = $properties;
        $this->setTitle($this->pl->txt('video_form_title'));

        $this->parent_gui = $parent_gui;

        $this->setFormAction($DIC->ctrl()->getFormAction($parent_gui));
        $this->initForm();
    }

    protected function initForm() {
        if (empty($this->properties)) {
            $this->addCommandButton(ilPanoptoPageComponentPluginGUI::CMD_CREATE, $this->lng->txt('create'));

            $item = new ilCustomInputGUI('', 'xpan_choose_videos_link');
            $url = 'https://' . xpanUtil::getServerName() . '/Panopto/Pages/Sessions/EmbeddedUpload.aspx?playlistsEnabled=false';
            $onclick = "if(typeof(xpan_modal_opened) === 'undefined') { xpan_modal_opened = true; $('#xpan_iframe').attr('src', '" . $url . "');}"; // this avoids a bug in firefox (iframe source must be loaded on opening modal)
            $onclick .= "$('#xpan_modal').modal('show');";
            $item->setHtml("<a onclick=\"" . $onclick . "\">" . $this->pl->txt('choose_videos') . "<a>");
            $this->addItem($item);
        } else {
            $this->addCommandButton(ilPanoptoPageComponentPluginGUI::CMD_UPDATE, $this->lng->txt('update'));

            $item = new ilHiddenInputGUI('id');
            $item->setValue($this->properties['id']);
            $this->addItem($item);

            $item = new ilCustomInputGUI('', '');
            $item->setHtml("<iframe src='" . 'https://' . xpanUtil::getServerName() . "/Panopto/Pages/Embed.aspx?id=" . $this->properties['id']
                . "&v=1' width='" . $this->properties['width'] . "' height='" . $this->properties['height'] . "' frameborder='0' allowfullscreen></iframe>");
            $this->addItem($item);

            $item = new ilNumberInputGUI($this->pl->txt('height'), 'height');
            $item->setRequired(true);
            $item->setValue($this->properties['height']);
            $this->addItem($item);

            $item = new ilNumberInputGUI($this->pl->txt('width'), 'width');
            $item->setRequired(true);
            $item->setValue($this->properties['width']);
            $this->addItem($item);
        }

        $this->addCommandButton(ilPanoptoPageComponentPluginGUI::CMD_CANCEL, $this->lng->txt('cancel'));
    }

}