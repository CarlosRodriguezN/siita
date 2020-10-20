jQuery( document ).ready(function(){
    dtaPlanOperativo = new Array();
    var dtaPlanificacion = eval( jQuery( '#jform_dtaPlanificacion' ).val() );
    
    if( typeOf( dtaPlanificacion ) !== "null" && dtaPlanificacion.length ){
        setDtaPlan( dtaPlanificacion );
    }
    
    jQuery( '#jform_dcmMonto_total_stmdoPry' ).blur( function(){
        if( dtaPlanOperativo.length == 0 ){
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
                        data:{  option          : 'com_proyectos',
                                view            : 'proyecto',
                                tmpl            : 'component',
                                format          : 'json',
                                action          : 'getLstPlanesOperativos',
                                objetivo        : jQuery( '#jform_strNombre_pry' ).val(),
                                idUGResponsable : jQuery( '#jform_intIdUndGestion' ).val(),
                                idUGFuncionarioR: jQuery( '#jform_intIdUGResponsable' ).val(),
                                idFuncionarioR  : jQuery( '#jform_idResponsable' ).val(),                               
                                fchInicioPln    : jQuery( '#jform_dteFechaInicio_stmdoPry' ).val(),
                                fchFinPln       : jQuery( '#jform_dteFechaFin_stmdoPry' ).val(),
                                monto           : unformatNumber( jQuery( '#jform_dcmMonto_total_stmdoPry' ).val() )
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Gestion POA - Proyectos: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            if( dataInfo.length ){
                setDtaPlan( dataInfo );
            }
        })
    }

})