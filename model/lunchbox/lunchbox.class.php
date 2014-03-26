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

    /**
     * Override the parent function to get our special properties.
     * @param string $namespace
     * @return array
     */
/*
    public function getProperties($namespace='core') {
        $properties = parent::getProperties($namespace);
        //$this->xpdo->log(1, print_r($properties,true));
        if (!empty($properties)) {
            return $properties;
        }

        // Properties defaults
        $properties = array (
            'product_type'      => 'regular',
            'product_template'  => $this->xpdo->getOption(
                'lunchbox.default_product_template','',
                $this->xpdo->getOption('default_template')
            ),
            'sort_order'        => 'name',
            'qty_alert'         => 0,
            'track_inventory'   => 0,
            'specs' => array (),
            'variations' =>   array(),
            'taxonomies' => array()
        );
        
        return $properties;
    }
*/

    /**
     *
     */
/*
    public function prepareTreeNode(array $node = array()) {
        $this->xpdo->lexicon->load('lunchbox:default');
        $menu = array();
        $idNote = $this->xpdo->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">('.$this->id.')</span>' : '';

        $menu[] = array(
            'text' => '<b>'.$this->get('pagetitle').'</b>'.$idNote,
            'handler' => 'Ext.emptyFn',
        );
        $menu[] = '-'; // equiv. to <hr/>
        $menu[] = array(
            'text' => $this->xpdo->lexicon('lunchbox_create_here'),
            'handler' => "function(itm,e) { 
				var at = this.cm.activeNode.attributes;
		        var p = itm.usePk ? itm.usePk : at.pk;
	            Ext.getCmp('modx-resource-tree').loadAction(
	                'a='+MODx.action['lunchbox:index']
	                + '&f=product_create'
	                + '&store_id='+p
	                + '&type=regular'
                );
        	}",
        );
        
        $menu[] = '-'; // equiv. to <hr/>
        
        $menu[] = array(
            'text' => $this->xpdo->lexicon('lunchbox_duplicate'),
            'handler' => 'function(itm,e) { itm.classKey = "Term"; this.duplicateResource(itm,e); }',
        );
        
        if ($this->get('published')) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('lunchbox_unpublish'),
                'handler' => 'this.unpublishDocument',
            );
        } else {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('lunchbox_publish'),
                'handler' => 'this.publishDocument',
            );
        }
        if ($this->get('deleted')) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('lunchbox_undelete'),
                'handler' => 'this.undeleteDocument',
            );
        } else {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('lunchbox_delete'),
                'handler' => 'this.deleteDocument',
            );
        }
        $menu[] = '-';
        $menu[] = array(
            'text' => $this->xpdo->lexicon('lunchbox_view'),
            'handler' => 'this.preview',
        );

        $node['menu'] = array('items' => $menu);
        $node['hasChildren'] = false;
        return $node;
    }
*/
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
        $raw = $this->getProperties(); // <-- this will have raw values
        $properties = $this->object->getProperties('lunchbox'); //<-- we need to update these values
        $this->object->set('class_key','Lunchbox');
        //$this->modx->log(1,'beforeSave raw values: '.print_r($raw,true));
        //$this->modx->log(1,'beforeSave raw POST values: '.print_r($_POST,true));
        
        $properties['product_type'] = $this->modx->getOption('product_type',$raw);
        $properties['product_template'] = $this->modx->getOption('product_template',$raw);
        $properties['track_inventory'] = ($this->modx->getOption('track_inventory',$raw) == 'Yes')? 1:0;
        $properties['sort_order'] = $this->modx->getOption('sort_order',$raw);
        $properties['qty_alert'] = $this->modx->getOption('qty_alert',$raw);

        $this->object->setProperties($properties,'lunchbox');
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