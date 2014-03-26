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



function renderLunchbox(isCreate, config){
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
				height:880 // Should react to default_per_page approx. 44 pixel per result
			},
			items: getChildrenTabContent(config)
		};

		tabPanel.insert(0, childrenTab);
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
				{name: 'pagetitle'},
				{name: 'description'},
				{name: 'uri'},
				{name: 'published'}
			]
		}),
		proxy : new Ext.data.HttpProxy({
			method: 'GET',
			prettyUrls: false,
			url: connector_url+'parent='+pid
		})
	});
}

function getChildrenTabContent(config){
	var store = getResourcesStore();

	var cm = new Ext.grid.ColumnModel([{
			header:'Pagetitle',
			resizable: false,
			dataIndex: 'pagetitle',
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
						//MODx.loadPage(MODx.action['lunchbox:index'], 'f=product_update&product_id='+record.data.product_id);
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
		id: 'modx-resource-products-columns',
		defaults: {
			labelSeparator: '',
			labelAlign: 'top',
			border: false,
			msgTarget: 'under'
		},
		items:[{
			layout:'border',
			id: 'modx-resource-productsList-columns',
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
					text:'Add Product',
					tooltip : 'Add a Product inside this Store',
					listeners: {
						'click': {fn: function(){
							MODx.loadPage(MODx.action['lunchbox:index'], 'f=product_create&store_id='+pid);
						}, scope: this}
					}
				},{
					xtype: 'button',
					text:'Manage Inventory',
					handler : function(){
						MODx.loadPage(MODx.action['lunchbox:index'], 'f=product_inventory&store_id='+pid);
					}
				},{
					xtype: 'button',
					padding : 0,
					cls : 'divided-btn',
					width : 55,
					text:'Sort',
					handler : function(){
						MODx.loadPage(MODx.action['lunchbox:index'], 'f=product_sort_order&store_id='+pid);
					}
				},{
					border:false,
					xtype: 'displayfield',
					value:'&nbsp;',
					columnWidth:.20
				},{
					xtype: 'textfield',
					emptyText:'Search..',
					name:'search'
				},{
					xtype: 'button',
					text:'Filter'
				},{
					xtype: 'button',
					text:'Show All'
				}]
			},
			this.resourcesGrid_panel]
		},{
			layout:'form',
			anchor: '100%',
			id: 'modx-resource-Addproducts-columns',
			defaults: {
				labelSeparator: '',
				labelAlign: 'top',
				border: false,
				msgTarget: 'under'
			},
			items:[{
				layout:'column',
				columns:4,
				height:50,
				xtype:'panel',
				items:[{
					xtype: 'label',
					text: 'Name'
				},{
					xtype: 'textfield',
					flex:1,
					fieldLabel:'Name'
				},{
					xtype:'spacer',anchor:'100%',width:'100%'},{
					xtype: 'label',
					text: 'Active?'
				},new Ext.form.ComboBox({fieldLabel: 'Active?',editable: true,flex:1,width:60})]
			},{
				region:'north',
				layout:'column',
				columns:4,
				height:50,
				xtype:'panel',
				items:[{
					xtype: 'label',
					text: 'SKU'
				},{
					xtype: 'textfield',
					flex:1,
					fieldLabel:'SKU'
				},{
					xtype: 'label',
					text: 'Vendor SKU'
				},{
					xtype: 'textfield',
					flex:1,
					fieldLabel:'Vendor SKU'
				}]
			},{
				xtype: 'textarea',
				anchor: '100%',
				fieldLabel:'Description'
			},{
				xtype: 'textfield',
				fieldLabel:'Price'
			},{
				 xtype: 'textfield',
				fieldLabel:'Strike Through Price'
			},{
				xtype : 'combo',
				fieldLabel: 'Category',
				editable: true,
				width:60},
			{
				region:'north',
				layout:'column',
				columns:4,
				height:50,
				xtype:'panel',
				items:[{
					xtype: 'label',
					text: 'Inventory'
				},{
					xtype: 'textfield',
					fieldLabel:'Inventory'
				},{
					xtype: 'label',
					text: 'Qty Min.'
				},{
					xtype: 'textfield',
					fieldLabel:'Qty Min.'
				}]
			},{
				region:'north',
				layout:'column',
				columns:4,
				height:50,
				xtype:'panel',
				items:[{
					xtype: 'label',
					text: 'Alert Qty'
				},{
					xtype: 'textfield',
					fieldLabel:'Alert Qty'
				},{
					xtype: 'label',
					text: 'Qty Max.'
				},{
					xtype: 'textfield',
					fieldLabel:'Qty Max.'
				}]
			},{
				xtype: 'textarea',
				name: 'textProduct',
				id: 'textProduct',
				hideLabel: true,
				anchor: '100%',
				height: 400,
				grow: false
			}]
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
