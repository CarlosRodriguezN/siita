jQuery( document ).ready(function(){

    dtaPlanOperativo = new Array();
    var dtaPlanificacion = eval( jQuery( '#jform_dtaPlanificacion' ).val() );
    
    if( typeOf( dtaPlanificacion ) != "null" && dtaPlanificacion.length ){
        setDtaPlan( dtaPlanificacion );
    }

    jQuery( '#jform_dcmMonto_ctr' ).blur( function(){
        if( dtaPlanOperativo.length > 0 ){
            jConfirm( "ESTE CAMBIO PROVOCARA CAMBIOS EN LOS PLANES, DESEA CONTINUAR", "SIITA - ECORAE", function(result) {
                if (result) {
                    addPropuestaPlan();
                }
            });
        }else{
            addPropuestaPlan();
        }
    })
    
    function setDtaPlan( dataInfo )
    {
        for( var x = 0; x < dataInfo.length; x++ ){
            var objPO = new PlanOperativo();
            dataInfo[x].idRegPlan = x;
            objPO.setDtaPlan( dataInfo[x] );

            jQuery( '#tbLstPlanesPry > tbody:last' ).append( objPO.addFilaPlan( 0 ) );

            dtaPlanOperativo.push( objPO );
        }
    }
    
    
    function addPropuestaPlan()
    {
        //  Obtengo URL completa del sitio
        var urlOrg = window.location.href;
        var path = urlOrg.split( '?' )[0];

        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option          : 'com_contratos',
                                view            : 'contrato',
                                tmpl            : 'component',
                                format          : 'json',
                                action          : 'getLstPlanesOperativos',
                                objetivo        : jQuery( '#jform_strDescripcion_ctr' ).val(),
                                idUGResponsable : jQuery( '#jform_intIdUndGestion' ).val(),
                                idUGFuncionarioR: jQuery( '#jform_intIdUGResponsable' ).val(),
                                idFuncionarioR  : jQuery( '#jform_idResponsable' ).val(),
                                fchInicioPln    : jQuery( '#jform_dteFechaInicio_ctr' ).val(),
                                fchFinPln       : jQuery( '#jform_dteFechaFin_ctr' ).val(),
                                monto           : unformatNumber( jQuery( '#jform_dcmMonto_ctr' ).val() ),
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Contratos - Gestion Planificacion: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            if( dataInfo.length ){
                setDtaPlan( dataInfo );
            }
        })
    }
    
})