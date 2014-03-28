<?php
require_once dirname(__FILE__) .'/abstract/lunchboxmanagercontroller.class.php';
/**
 * Gets the hierarchy for a given page. (Ajax)
 */
class LunchboxHierarchyManagerController extends LunchboxManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = false;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = false;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = false;
    
    public $props = array();
    
    /**
     * pass a page id here
     *
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
        $page_id = $this->modx->getOption('page_id',$scriptProperties);
        $items = array();
        while ($page = $this->modx->getObject('modResource', $page_id)) {
            array_unshift($items, array(
                    'id' => $page->get('id'),
                    'pagetitle' => $page->get('pagetitle')
                )
            );
            $page_id = $page->get('parent');
        }

        $last = array_pop($items);
        
        $out = '<div id="lunchbox_breadcrumbs">';
        foreach ($items as $i) {
            $out .= '<span onclick="javascript:drillDown('.$i['id'].');" class="lunchbox_breadcrumb">'.$i['pagetitle'].'</span> &raquo; ';
        }
        $out .= '<span>'.$last['pagetitle'].'</span>';
        $out .= '</div>';

        return $out;
    }
    
    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() {
        return 'Hierarchy';
    }
}
/*EOF*/