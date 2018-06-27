<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilPanoptoPageComponentPluginGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilPanoptoPageComponentPluginGUI: ilPCPluggedGUI
 */
class ilPanoptoPageComponentPluginGUI extends ilPageComponentPluginGUI {

    const CMD_INSERT = 'insert';
    const CMD_CREATE = 'create';
    const CMD_EDIT = 'edit';
    const CMD_UPDATE = 'update';

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
        $this->tpl->addJavaScript($this->pl->getDirectory() . '/js/ppco.js');
        $form = new ppcoVideoFormGUI($this);
        $this->tpl->setContent($this->getModal() . $form->getHTML());
    }

    function edit() {

    }

    function create() {
        $ids = $_POST['session_id'];
        foreach ($ids as $id) {
            $this->createElement(array('id' => $id));
        }
        $this->ctrl->returnToParent($this);
    }

    function getElementHTML($a_mode, array $a_properties, $plugin_version) {
        return "<iframe src='" . 'https://' . xpanUtil::getServerName() . "/Panopto/Pages/Embed.aspx?id=" . $a_properties['id']
            . "&v=1' width='250' height='180' frameborder='0' allowfullscreen></iframe><br>";
    }


    /**
     * @return String
     */
    protected function getModal() {
        $this->tpl->addCss($this->pl->getDirectory() . '/templates/default/modal.css');
        $modal = ilModalGUI::getInstance();
        $modal->setId('xpan_modal');
        $modal->setType(ilModalGUI::TYPE_LARGE);
		$modal->setHeading($this->pl->txt('modal_title_browse_and_embed'));
        $url = 'https://' . xpanUtil::getServerName() . '/Panopto/Pages/Sessions/EmbeddedUpload.aspx?playlistsEnabled=true';
        $modal->setBody('<iframe id="xpan_iframe" src="'.$url.'"></iframe>');
        $button = ilSubmitButton::getInstance();
        $button->setCaption('insert');
//        $button->setCommand('test');
        $button->setId('xpan_insert');
        $modal->addButton($button);
//        $modal->setBody('<div>helooooo</div>');
        return $modal->getHTML();
    }

}