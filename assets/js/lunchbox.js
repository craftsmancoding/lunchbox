/**
 * Drill down into a folder
 *
 */
function drillDown(id) {
    var store = getResourcesStore();
    store.load({
        params:{parent: id}
    });
    // update the breadcrumbs
    setBreadcrumbs(id);
}

/**
 * See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel 
 * And http://www.sencha.com/forum/showthread.php?38841-Using-Extjs-to-change-div-content
 */
function setBreadcrumbs(page_id) {

    Ext.Ajax.request({
        url: connector_url+'&action=hierarchy&page_id='+page_id,
        params: {},
        async:false,
        success: function(response){
            Ext.fly('lunchbox_breadcrumbs').update(response.responseText);
        }
    });
}

function getQueryParams(qs) {
	qs = qs.split("+").join(" ");
	var params = {}, tokens, re = /[?&]?([^=]+)=([^&]*)/g;
	while (tokens = re.exec(qs)) {
		params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
	}
	return params;
}

var query = getQueryParams(document.location.search),
	pid = query.id,
	/* Getting value loaded to the page through MODx 
	 * Asuming here that his path is always set by MODx.
	 */
	activeRecord = {};



function renderLunchbox(config){
	var tabPanel = Ext.getCmp("modx-resource-tabs");

	if(tabPanel!=null){

		//Add children tab
		var childrenTab = {
			title: 'Children',
			id: 'modx-resource-children',
			cls: 'modx-resource-tab',
			layout: 'fit',
			labelAlign: 'top',
			labelSeparator: '',
			bodyCssClass: 'tab-panel-wrapper main-wrapper',
			autoHeight: true,
			defaults: {
				border: false,
				msgTarget: 'under',
				width: 400,
				height:880 // Should react to default_per_page approx. ~44 pixel per result
			},
			items: getChildrenTabContent(config)
		};

		tabPanel.insert(0, childrenTab); // Add tab at the front
		tabPanel.setActiveTab(0);
		tabPanel.doLayout();
	}
}



/**
 * The children resources.
 */
function getResourcesStore(){
	var store = Ext.StoreMgr.get('resourceStore');

	if(store) return store;
	else return new Ext.data.Store({
		fields: ['id', 'pagetitle', 'description','uri', 'published'],
		autoLoad : true,
		storeId : 'resourceStore',
		reader : new Ext.data.JsonReader({
			idProperty: 'id',
			root: 'results',
			totalProperty: 'total',
			fields: [
				{name: 'id'},
				{name: 'isfolder'},
				{name: 'pagetitle'},
				{name: 'description'},
				{name: 'uri'},
				{name: 'published'}
			]
		}),

		proxy : new Ext.data.HttpProxy({
			method: 'GET',
			prettyUrls: false,
			url: connector_url+'&action=children&parent='+pid
		})
	});
}

function getChildrenTabContent(config){
    console.log('Getting Children Content...');
	var store = getResourcesStore();

	var cm = new Ext.grid.ColumnModel([
	      {
			header:'',
			resizable: false,
			width: 40,
			dataIndex: 'isfolder',
			sortable: true,
			renderer : function(value, metaData, record, rowIndex, colIndex, store) {
                if (value) {
                    return '<div class="lunchbox_folder" onclick="javascript:drillDown('+record.id+');">&nbsp;</div>';
                }
                else {
                    return '<div class="lunchbox_page"></div>';
                }
			}
		},{
			header:'Pagetitle',
			resizable: false,
			dataIndex: 'pagetitle',
			sortable: true
		},{
			header:'ID',
			resizable: false,
			dataIndex: 'id',
			sortable: true
		},{
			header: 'Description',
			dataIndex: 'description',
			sortable: true
		},{
			header: 'Published',
			dataIndex: 'published',
			sortable: true
		},{
			header : 'Action',
			dataIndex: 'id',
			sortable: true,
			renderer : function(value, metaData, record, rowIndex, colIndex, store) {
			  return '<a role="edit" style="padding: 5px 10px 5px 10px;color: #53595f;font: bold 11px tahoma,verdana,helvetica,sans-serif;text-shadow: 0 1px 0 #fcfcfc;" class="x-btn">Edit</a> <button style="padding: 5px 10px 5px 10px;color: #53595f;font: bold 11px tahoma,verdana,helvetica,sans-serif;text-shadow: 0 1px 0 #fcfcfc;" role="view" class="x-btn">View</button>';
			}
		}
	]);

	this.resourcesGrid_panel = new Ext.grid.GridPanel({
		ds: store,
		cm: cm,
		layout:'fit',
		region:'center',
		border: true,
		tbar: {
            items: [
                {
                    xtype: "box",
                    autoEl: {cn: '<div id="lunchbox_breadcrumbs">Breadcrumbs</div>'}
                }
            ]
        },
		viewConfig: {
			autoFill: true,
			forceFit: true,
			emptyText : 'This page has no children.'
		},
		listeners : {
			afterrender : function(){
				this.getStore().load();
			},
			cellclick : function(grid, rowIndex, columnIndex, e) {
				var record = grid.getStore().getAt(rowIndex),
					fieldName = grid.getColumnModel().getDataIndex(columnIndex);
				if(fieldName === 'id'){
					if(e.target.innerHTML === 'Edit'){
				        MODx.loadPage(MODx.action['resource/update'], 'id='+id);
					} else if(e.target.innerHTML === 'View'){
						window.open(site_url + record.data.uri, '_blank');
                    }
				}
			}
		},
		bbar: new Ext.PagingToolbar({
			store: store,
			displayInfo: true,
			pageSize: 30,
			prependButtons: true
		})
	});

	return [{
		layout:'card',
		activeItem:0,
		id: 'modx-resource-children-columns',
		defaults: {
			labelSeparator: '',
			labelAlign: 'top',
			border: false,
			msgTarget: 'under'
		},
		items:[{
			layout:'border',
			id: 'modx-resource-childrenlist-columns',
			defaults: {
				labelSeparator: '',
				labelAlign: 'top',
				border: false,
				msgTarget: 'under'
			},
			items:[{
				region:'north',
				layout:'column',
				columns:6,
				height:60,
				xtype:'panel',
				items:[{
					xtype: 'button',
					text:'Add Page',
					tooltip : 'Add a Page Here',
					listeners: {
						'click': {fn: function(){
                            MODx.loadPage(MODx.action['resource/create'], 'class_key=modDocument&context_key=web&id='+pid+'&parent='+pid);
						}, scope: this}
					}
				}]
			},
			this.resourcesGrid_panel]
		}]
	}];
}



var triggerDirtyField = function(fld) {
	Ext.getCmp('modx-panel-resource').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function() {
	triggerDirtyField(Ext.getCmp('textProduct'));
};
MODx.fireResourceFormChange = function(f,nv,ov) {
	Ext.getCmp('modx-panel-resource').fireEvent('fieldChange');
};
