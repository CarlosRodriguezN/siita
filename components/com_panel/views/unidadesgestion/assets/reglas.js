jQuery(document).ready(function() {

    //  identifica el div que se comporta como acordion
    jQuery("#acordionPanelUG").accordion( { header: "> div > h3",
                                            active: '#acordionPanelUG',
                                            collapsible: true }).sortable({ axis: "y",
                                                                            handle: "h3",
                                                                            stop: function(event, ui) {
                                                                                // IE doesn't register the blur when sorting
                                                                                // so trigger focusout handlers to remove .ui-state-focus
                                                                                ui.item.children("h3").triggerHandler("focusout");
                                                                            }
                                                                        });

    //  PESTAÑAS
//    jQuery('#tabsAttrIndicador').tabs();
    
})