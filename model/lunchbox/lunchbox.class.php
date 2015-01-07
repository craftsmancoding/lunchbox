<?php
require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/update.class.php';

class Lunchbox extends modResource {
    public $showInContextMenu = true;
    public $hide_children_in_tree = true;
    public $allowChildrenResources = false;

    /**
     *
     * @param xPDO $xpdo
     *
     * @return Lunchbox
     */
    function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);
        $this->set('class_key','Luchbox');
        $this->set('hide_children_in_tree',true);
    }

    /**
     * 
     * @return string
     */     
    public static function getControllerPath(xPDO &$modx) {
        $x = $modx->getOption('lunchbox.core_path',null,$modx->getOption('core_path').'components/lunchbox/').'controllers/lunchbox/';
        return $x;
    }
    
    /**
     *
     * @return array
     */     
    public function getContextMenuText() {
        $this->xpdo->lexicon->load('lunchbox:default');
        return array(
            'text_create' => $this->xpdo->lexicon('lunchbox'),
            'text_create_here' => $this->xpdo->lexicon('lunchbox_create_here'),
        );
    }

    /**
     *
     * @return string
     */ 
    public function getResourceTypeName() {
        $this->xpdo->lexicon->load('lunchbox:default');
        return $this->xpdo->lexicon('lunchbox');
    } 

    /**
     * Get rid of that little triangle that lets a user open a container.
     *
     * @param array $node
     * @return array
     */
    public function prepareTreeNode(array $node = array()) {
        $node['hasChildren'] = true;
        return parent::prepareTreeNode($node);
    }
    
    /**
     * Gotta look up the URL of our CMP and its actions
     * 
     * @param array any optional arguments, e.g. array('action'=>'children','parent'=>123)
     * @return string
     */
    public function getControllerUrl($args=array()) {
        // future: pass as args:
        $namespace='lunchbox';
        $controller='index'; 
        $url = MODX_MANAGER_URL;
        if ($Action = $this->xpdo->getObject('modAction', array('namespace'=>$namespace,'controller'=>$controller))) {
            $url .= '?a='.$Action->get('id');
            if ($args) {
                foreach ($args as $k=>$v) {
                    $url.='&'.$k.'='.$v;
                }
            }
        }
        return $url;
    }

}

//------------------------------------------------------------------------------
//! CreateProcessor
//------------------------------------------------------------------------------
class LunchboxCreateProcessor extends modResourceCreateProcessor {
    /** 
     * @var Lunchbox $object 
     */
    public $object;
    
    /**
     * Override modResourceCreateProcessor::afterSave to provide custom functionality, saving the container settings to a
     * custom field in the manager
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->object->set('class_key','Lunchbox');
        $this->object->set('cacheable',true);
        $this->object->set('isfolder',false);
        
        return parent::afterSave();
    }

    /**
     * Override modResourceUpdateProcessor::beforeSave to provide custom functionality, saving settings for the container
     * to a custom field in the DB
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->object->set('class_key','Lunchbox');
        return parent::beforeSave();
    }

}

class LunchboxUpdateProcessor extends modResourceUpdateProcessor {
    /** 
     * @var Lunchbox $object 
     */
    public $object;
    
    /**
     * Override modResourceCreateProcessor::afterSave to provide custom functionality, saving the container settings to a
     * custom field in the manager
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->object->set('class_key','Lunchbox');
        $this->object->set('cacheable',true);
        $this->object->set('isfolder',true); // ensure we get a clean uri
        return parent::afterSave();
    }

    /**
     * Override modResourceUpdateProcessor::beforeSave to provide custom functionality, saving settings for the container
     * to a custom field in the DB.
     * On the flip side, it should be available in JS via this path: MODx.activePage.config.record.properties.lunchbox
     *
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->object->set('class_key','Lunchbox');
        return parent::beforeSave();
    }

}