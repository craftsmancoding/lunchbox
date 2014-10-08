<?php
require_once $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/default/resource/update.class.php';

class LunchboxUpdateManagerController extends ResourceUpdateManagerController {

    public $resource;

    public function loadCustomCssJs() {
        parent::loadCustomCssJs();
        
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : null;

        $Lunchbox = new Lunchbox($this->modx);
        $this->addJavascript($assets_url . 'js/lunchbox.js');
    	$lunchbox_connector_url = $Lunchbox->getControllerUrl();
        

$this->addHtml('
            <script type="text/javascript">
               var hierarchy = [];
                var connector_url = '.json_encode($lunchbox_connector_url).';
                var site_url = "'.MODX_SITE_URL.'";
                Ext.onReady(function(){
                    Ext.getCmp("modx-resource-tabs").insert(0, {
                        title: "Children",
                        id: "children-tab",
                        width: "95%",
                        html: "<div id=\"child_pages\" style=\"padding:20px;\"></div>"
                    });
                    show_all_child();
                    Ext.getCmp("modx-resource-tabs").setActiveTab("children-tab");
                });
            </script>');


/*
    	$this->addHtml('
			<script type="text/javascript">
                var hierarchy = [];
                var connector_url = '.json_encode($lunchbox_connector_url).';
                var site_url = "'.MODX_SITE_URL.'";
				Ext.onReady(function(){
					setBreadcrumbs('.$page_id.');
					renderLunchbox(MODx.config);
				});
			</script>');*/
    }
        
    public function getLanguageTopics() {
        return array('resource','lunchbox:default');
    }

}