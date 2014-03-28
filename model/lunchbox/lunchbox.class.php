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
     * @param array $node
     * @return array
     */
    public function prepareTreeNode(array $node = array()) {
/*
        $this->xpdo->lexicon->load('articles:default');
        $menu = array();
        $idNote = $this->xpdo->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">('.$this->id.')</span>' : '';
        $menu[] = array(
            'text' => '<b>'.$this->get('pagetitle').'</b>'.$idNote,
            'handler' => 'Ext.emptyFn',
        );
        $menu[] = '-';
        $menu[] = array(
            'text' => $this->xpdo->lexicon('articles.articles_manage'),
            'handler' => 'this.editResource',
        );
        $menu[] = array(
            'text' => $this->xpdo->lexicon('articles.articles_write_new'),
            'handler' => 'function(itm,e) { itm.classKey = "Article"; this.createResourceHere(itm,e); }',
        );
        $menu[] = array(
            'text' => $this->xpdo->lexicon('articles.container_duplicate'),
            'handler' => 'function(itm,e) { itm.classKey = "ArticlesContainer"; this.duplicateResource(itm,e); }',
        );
        $menu[] = '-';
        if ($this->get('published')) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('articles.container_unpublish'),
                'handler' => 'this.unpublishDocument',
            );
        } else {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('articles.container_publish'),
                'handler' => 'this.publishDocument',
            );
        }
        if ($this->get('deleted')) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('articles.container_undelete'),
                'handler' => 'this.undeleteDocument',
            );
        } else {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('articles.container_delete'),
                'handler' => 'this.deleteDocument',
            );
        }
        $menu[] = '-';
        $menu[] = array(
            'text' => $this->xpdo->lexicon('articles.articles_view'),
            'handler' => 'this.preview',
        );

        $node['menu'] = array('items' => $menu);
        $node['hasChildren'] = true;
        return $node;
*/
//        print '<pre>'.print_r($node, true).'</pre>'; exit;
//        $node['hasChildren'] = false;
        $node['hasChildren'] = true;
//        $node['allowDrop'] = false;
        $node = parent::prepareTreeNode($node);

        $node['hasChildren'] = true;
//        $node['allowDrop'] = false;
//        error_log(print_r($node,true));
        return $node;
    }

    /**
     * Given a page id, return an array of all the parents from that page
     * all the way up to the root of the site.  To be used for breadcrumbs.
     * 
     * @param integer $page_id
     * @return array
     */
    public function lookupHierarchy($page_id) {
        $out = array();
        while ($page = $this->modx->getObject('modResource', $page_id)) {
            $out[] = array(
                'id' => $page->get('id'),
                'pagetitle' => $page->get('pagetitle')
            );
            $page_id = $page->get('parent');
        }
        return $out;
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