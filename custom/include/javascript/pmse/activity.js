// Enrico Simonetti
// enricosimonetti.com
//
// 2017-04-27
// Tested on Sugar 7.8.2.0

// retrieve the modules on which the action needs to be displayed, just once
var customWorkflowActionModules = {};

SUGAR.App.api.call('read', SUGAR.App.api.buildURL('customv1/pmse_Project/CrmData/customWorkflowActions'), {}, {
    success: function(response) {
        if(response) {
            customWorkflowActionModules = response;
        }
    },
    error: function(error){
    }
});

/**
 * Gets custom action context menu addition
 * @return {object} Action definition for a context menu
 */
AdamActivity.prototype.customContextMenuActions = function() {
    // only if allowed, show the item in the menu
    if(_.indexOf(customWorkflowActionModules, PROJECT_MODULE) >= 0) {
        return [{
            name: 'CALL_CUSTOM_LOGIC',
            text: translate('CALL_CUSTOM_LOGIC') // or SUGAR.App.lang.get('CALL_CUSTOM_LOGIC')
        }]
    } else {
        return [];
    }
}

/**
 * Gets a custom action definition for rendering
 * @param {string} type The action type
 * @param {object} w Window definition
 * @return {object} Definition for the custom action
 */
AdamActivity.prototype.customGetAction = function(type, w) {
    switch (type) {
        case 'CALL_CUSTOM_LOGIC':
            
            var comboApi = new SearchableCombobox({ 
                label: translate('LBL_CUSTOM_LOGIC_TO_CALL'), 
                name: 'act_service_method', 
                placeholder: translate('LBL_PMSE_FORM_OPTION_SELECT'), 
                change: function() { 
                    comboApi.setValid(true); 
                }, 
                proxy: new SugarProxy({ 
                    url: 'customv1/pmse_Project/CrmData/customWorkflowActions/' + PROJECT_MODULE, 
                    callback: null 
                }) 
            });
 
            var actionText = translate('LBL_PMSE_CONTEXT_MENU_SETTINGS');
            var actionCSS = 'adam-menu-icon-configure';
            var items = [comboApi];
            var proxy = new SugarProxy({
                url: 'pmse_Project/ActivityDefinition/' + this.id,
                uid: this.id,
                callback: null
            });

            var callback = {
                'loaded': function(data) {
                    self.canvas.emptyCurrentSelection();
                    comboApi.proxy.getData(null, {
                        success: function(rules) {
                            if (rules && rules.success) {
                                comboApi.setOptions(rules.result);
                            }
                            App.alert.dismiss('upload');
                            w.html.style.display = 'inline';
                        }
                    });
                }
            };

            action = {
                proxy: proxy,
                items: items,
                actionText: actionText,
                actionCSS: actionCSS,
                callback: callback
            };
            
            return action;
    }
}

/**
 * Needed to define the modal that pops up with a form
 * @param {string} type The action type
 * @return {object} Definition needed for a modal
 */
AdamActivity.prototype.customGetWindowDef = function(type) {
    switch(type) {
        case 'CALL_CUSTOM_LOGIC':
            var wWidth = 500;
            var wHeight = 150;
            var wTitle = translate('CALL_CUSTOM_LOGIC') + ': ' + this.getName();
            return {wWidth: wWidth, wHeight: wHeight, wTitle: wTitle};
    }
}
