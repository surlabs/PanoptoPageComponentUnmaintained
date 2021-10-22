<?php

use srag\Plugins\PanoptoPageComponent\Util\PermissionUtils;

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
    const CMD_CANCEL = 'cancel';

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
     * @var xpanClient
     */
    protected $client;


    /**
     * ilPanoptoPageComponentPluginGUI constructor.
     */
    public function __construct() {
        global $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->lng = $DIC->language();
        $this->pl = new ilPanoptoPageComponentPlugin();
        $this->client = xpanClient::getInstance();
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
    function cancel() {
        $this->ctrl->returnToParent($this);
    }

    /**
     *
     */
    function insert() {
        $this->client->synchronizeCreatorPermissions();
        ilUtil::sendInfo($this->pl->txt('msg_choose_videos'));
        $this->tpl->addJavaScript($this->pl->getDirectory() . '/js/ppco.min.js?1');
        $form = new ppcoVideoFormGUI($this);
        $this->tpl->setContent($this->getModal() . $form->getHTML());
    }

    /**
     *
     */
    function create() {
        if (empty($_POST['session_id']) || empty($_POST['max_width']) || empty($_POST['is_playlist'])) {
            ilUtil::sendFailure($this->pl->txt('msg_no_video'), true);
            $this->ctrl->redirect($this, self::CMD_INSERT);
        }
        // the videos have to be created in reverse order to be presented in the correct order
        $_POST['session_id'] = array_reverse($_POST['session_id']);
        $_POST['max_width'] = array_reverse($_POST['max_width']);
        $_POST['is_playlist'] = array_reverse($_POST['is_playlist']);

        for ($i = 0; $i < count($_POST['session_id']); $i++) {
            $this->createElement(array(
                'id' => $_POST['session_id'][$i],
                'max_width' => $_POST['max_width'][$i],
                'is_playlist' => $_POST['is_playlist'][$i]
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
            'max_width' => $_POST['max_width'],
            'is_playlist' => $_POST['is_playlist'],
        ));

        $this->returnToParent();
    }

    /**
     * @param       $a_mode
     * @param array $a_properties
     * @param       $plugin_version
     * @return string
     * @throws ilLogException
     */
    function getElementHTML($a_mode, array $a_properties, $plugin_version) {
        try {
            if ($a_properties['is_playlist']) {
                $this->client->grantViewerAccessToPlaylistFolder($a_properties['id']);
            } else {
                $this->client->grantViewerAccessToSession($a_properties['id']);
            }
        } catch (Exception $e) {
            // exception could mean that the session was deleted. The embed player will display an appropriate message
            xpanLog::getInstance()->logError($e->getCode(), 'Could not grant viewer access: ' . $e->getMessage());
        }

        if (!isset($a_properties['max_width'])) { // legacy
            $size_props = "width:" . $a_properties['width'] . "px; height:" . $a_properties['height'] . "px;";
            return "<div class='ppco_iframe_container' style='" . $size_props . "'>" .
                "<iframe src='https://" . xpanUtil::getServerName() . "/Panopto/Pages/Embed.aspx?"
                . ($a_properties['is_playlist'] ? "p" : "") . "id=" . $a_properties['id']
                . "&v=1' frameborder='0' allowfullscreen style='width:100%;height:100%'></iframe></div>";
        }

        return "<div class='ppco_iframe_container' style='width:" . $a_properties['max_width'] . "%'>" .
            "<iframe src='https://" . xpanUtil::getServerName() . "/Panopto/Pages/Embed.aspx?"
            . ($a_properties['is_playlist'] ? "p" : "") . "id=" . $a_properties['id']
            . "&v=1' frameborder='0' allowfullscreen style='width:100%;height:100%;position:absolute'></iframe></div>";
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
        $button->setId('xpan_insert');
        $modal->addButton($button);
        return $modal->getHTML();
    }

}
