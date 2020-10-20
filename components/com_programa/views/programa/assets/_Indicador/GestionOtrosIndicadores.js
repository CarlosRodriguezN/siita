jQuery( 'document' ).ready( function(){
    var banIdOtroInd = -1;
    
    
    jQuery( '#btnAddIndicador' ).live( 'click', function(){
//                  + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=atributoindicador&layout=edit&idIndicador="+ dtaIndGap.gapMasculino.idIndicador +"&tpo=m&tpoIndicador=gap&idRegIndicador="+ dtaIndGap.idRegGap +"&tmpl=component&task=preview\", {size:{x:950,y:500}, handler:\"iframe\"} );'>"        
        jQuery( this ).load( 'index.php?option=com_proyectos&view=atributoindicador&layout=edit&idIndicador=1&tpo=m&tpoIndicador=gap&idRegIndicador=2&tmpl=component&task=preview", {size:{x:950,y:500}, handler:"iframe"}' );
    })
    
})