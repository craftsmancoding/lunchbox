<?php
require_once $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/default/resource/update.class.php';

class LunchboxUpdateManagerController extends ResourceUpdateManagerController {

    public $resource;

    public function loadCustomCssJs() {

        parent::loadCustomCssJs();
        
        $mgr_url = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL.'components/lunchbox/');
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : null;

        $Lunchbox = new Lunchbox($this->modx);
//        $hierarchy = $Lunchbox->lookupHierarchy($page_id);

		//Add below for customization
		//See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel
        $this->addJavascript($assets_url . 'js/lunchbox.js');
//    	$lunchbox_connector_url = $assets_url.'connector.php?';
    	$lunchbox_connector_url = $Lunchbox->getControllerUrl();
//    	print $lunchbox_connector_url; exit;
//print json_encode($hierarchy); exit;
    	$this->addHtml('
			<script type="text/javascript">
                var hierarchy = [];
                var connector_url = '.json_encode($lunchbox_connector_url).';
                var site_url = "'.MODX_SITE_URL.'";
                var breadcrumbs = {
                    items: [
                        {
                            xtype: "box",
                            autoEl: {cn: "<div id=\'lunchbox_breadcrumbs\'>Test</div>"}
                        }
                    ]
                };
				Ext.onReady(function(){
					setBreadcrumbs('.$page_id.');
					renderLunchbox(MODx.config);
				});
			</script>');
			
        $this->addCss($assets_url.'components/lunchbox/css/mgr.css');

    }
        
    public function getLanguageTopics() {
        return array('resource','lunchbox:default');
    }

}