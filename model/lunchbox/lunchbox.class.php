<?php
require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/update.class.php';

class Lunchbox extends modResource {
    public $showInContextMenu = true;

    /**
     *
     * @return string
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
        //$this->modx->log(1, __FILE__ . print_r($this->object->toArray(), true));
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
        //$this->modx->log(1, __FILE__ . print_r($this->object->toArray(), true));
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