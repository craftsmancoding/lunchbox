<?php
require_once $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/default/resource/update.class.php';

class LunchboxUpdateManagerController extends ResourceUpdateManagerController {

    public $resource;

    public function loadCustomCssJs() {
        parent::loadCustomCssJs();
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $Lunchbox = new Lunchbox($this->modx);


        $this->addJavascript($assets_url . 'js/jquery.min.js');


        $this->addJavascript($assets_url.'js/handlebars-v2.0.0.js');
        $this->addJavascript($assets_url . 'js/lunchbox.js');
        $this->addCss($assets_url . 'css/mgr.css'); 
        $this->addCss($assets_url . 'css/lunchbox.css'); 

        $this->addJavascript($assets_url . 'js/bootstrap.js');
        $this->addCss($assets_url . 'css/bootstrap.css'); 


    	$lunchbox_connector_url = $Lunchbox->getControllerUrl();
        $sort = $this->modx->getOption('sort',$scriptProperties,$this->modx->getOption('lunchbox.sort_col','','pagetitle'));

        $lb_data['sort_col'] = $sort;
        $lb_data['parent'] = $page_id;
        $lb_data['connector_url'] = $lunchbox_connector_url;
        $lb_data['site_url'] = MODX_SITE_URL;
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
                var Lunchbox = '.json_encode($lb_data).';
            </script>');


        $this->addHtml('
            <script type="text/javascript">
                Ext.onReady(function(){
                    Ext.getCmp("modx-resource-tabs").insert(0, {
                        title: "Children",
                        id: "children-tab",
                        width: "95%",
                        html: "<div id=\"child_pages\"></div>"
                    });
                    display_lunchbox();
                    Ext.getCmp("modx-resource-tabs").setActiveTab("children-tab");
                    setBreadcrumbs('.$page_id.');
                });
            </script>');

    }
        
    public function getLanguageTopics() {
        return array('resource','lunchbox:default');
    }

}