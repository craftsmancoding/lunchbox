<?php
require_once $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/default/resource/update.class.php';

class LunchboxUpdateManagerController extends ResourceUpdateManagerController {

    public $resource;

    public function loadCustomCssJs() {

        parent::loadCustomCssJs();
        
        $mgr_url = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL.'components/lunchbox/');
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : null;
        
		//Add below for customization
		//See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel
        $this->addJavascript($assets_url . 'js/lunchbox.js');
    	$lunchbox_connector_url = $assets_url.'connector.php?';
    	$this->addHtml('
			<script type="text/javascript">
                var connector_url = "'.$lunchbox_connector_url.'";
                var site_url = "'.MODX_SITE_URL.'";
                    var breadcrumbs = {
                        items: [
                            {
                                xtype: "box",
                                autoEl: {cn: "<span onclick=\'javascript:alert(123);\'>Breadcrumb</span> >> Here >> Now</span>"}
                            }
                        ]
                    };
				isProductContainerCreate = false;
				Ext.onReady(function(){
					renderLunchbox(isProductContainerCreate, MODx.config);
				});
			</script>');
			
        $this->addCss($assets_url.'components/lunchbox/css/mgr.css');

    }
        
    public function getLanguageTopics() {
        return array('resource','lunchbox:default');
    }

}