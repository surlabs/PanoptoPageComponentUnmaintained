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

    /**
     *
     */
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

    /**
     *
     */
    function insert() {
        ilUtil::sendInfo($this->pl->txt('msg_choose_videos'));
        $this->tpl->addJavaScript($this->pl->getDirectory() . '/js/ppco.js');
        $form = new ppcoVideoFormGUI($this);
        $this->tpl->setContent($this->getModal() . $form->getHTML());
    }

    /**
     *
     */
    function create() {
        // the videos have to be created in reverse order to be presented in the correct order
        $_POST['session_id'] = array_reverse($_POST['session_id']);
        $_POST['height'] = array_reverse($_POST['height']);
        $_POST['width'] = array_reverse($_POST['width']);

        for ($i = 0; $i < count($_POST['session_id']); $i++) {
            $this->createElement(array(
                'id' => $_POST['session_id'][$i],
                'height' => $_POST['height'][$i],
                'width' => $_POST['width'][$i]
            ));
        }

        $this->ctrl->returnToParent($this);
    }

    /**
     *
     */
    function edit() {
        $form = new ppcoVideoFormGUI($this, $this->getProperties());
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    function update() {
        $form = new ppcoVideoFormGUI($this, $this->getProperties());
        $form->setValuesByPost();

        if (!$form->checkInput()) {
            $this->tpl->setContent($form->getHTML());
            return;
        }

        $this->updateElement(array(
            'id' => $_POST['id'],
            'height' => $_POST['height'],
            'width' => $_POST['width']
        ));

        $this->returnToParent();
    }

    /**
     * @param $a_mode
     * @param array $a_properties
     * @param $plugin_version
     * @return string
     */
    function getElementHTML($a_mode, array $a_properties, $plugin_version) {
        $client = xpanClient::getInstance();
        if (!$client->hasUserViewerAccessOnSession($a_properties['id'])) {
            $client->grantUserViewerAccessToSession($a_properties['id']);
        }

        return "<iframe src='" . 'https://' . xpanUtil::getServerName() . "/Panopto/Pages/Embed.aspx?id=" . $a_properties['id']
            . "&v=1' width='" . $a_properties['width'] . "' height='" . $a_properties['height'] . "' frameborder='0' allowfullscreen></iframe>";
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