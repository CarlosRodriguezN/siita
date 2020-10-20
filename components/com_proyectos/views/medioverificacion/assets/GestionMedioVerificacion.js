lstTmpMedVerificacion = new Array();
lstTmpSupuestos = new Array();

jQuery(document).ready(function () {

    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    //  Actualizo contenido de un Medios de Verificacion y Supuestos
    dtaMLMVSup();

    //  Creo Objeto Medio de Verificacion y Supuestos de un determinado ML
    function dtaMLMVSup()
    {
        var objMV = parent.window.objMarcoLogico;

        switch (jQuery('#idTipoML').val()) {

            //  Marco Logico de tipo Fin
            case '1':
                if (typeof (objMV.fin) != "undefined") {
                    //  Accedo a la lista de Medios de Verificacion de Fin
                    var lstMV = objMV.fin.lstMedVerificacion;

                    //  Accedo a la lista de Supuestos de Fin
                    var lstSup = objMV.fin.lstSupuestos;
                }

            break;

                //  Marco Logico de tipo Proposito
            case '2':
                //  Accedo a la lista de Medios de Verificacion de Proposito
                if (typeof (objMV.proposito) != "undefined") {

                    //  Accedo a la lista de medios de verificacion
                    var lstMV = objMV.proposito.lstMedVerificacion;

                    //  Accedo a la lista de Supuestos de Proposito
                    var lstSup = objMV.proposito.lstSupuestos;
                }

            break;

            //  Gestion de componentes de un determinado Marco Logico
            case '3':
                //  Obtengo la posicion de un componente en la lista de componentes
                var pos = jQuery('#idRegML').val();

                //  Accedo a la lista de medios de verificacion
                var lstMV = objMV.lstComponentes[pos].lstMedVerificacion;

                //  Accedo a la lista de Supuestos de Proposito
                var lstSup = objMV.lstComponentes[pos].lstSupuestos;
            break;

            //  Gestiono actividades de un componente en un determinado marco logico
            case '4':
                var idCmp = jQuery('#idCmp').val();
                var idAct = jQuery('#idAct').val();

                //  Accedo a la lista de medios de verificacion
                var lstMV = objMV.lstComponentes[idCmp].lstActividades[idAct].lstMedVerificacion;

                //  Accedo a la lista de Supuestos de Proposito
                var lstSup = objMV.lstComponentes[idCmp].lstActividades[idAct].lstSupuestos;
            break;
        }
        

        //  Cargo los datos de Medios de verificacion
        if (typeof (lstMV) != 'undefined' ) {
            var nrLMV = lstMV.length;
            if( nrLMV > 0 ){
                for (var x = 0; x < lstMV.nrLMV; x++) {
                    var objMV = new MedioVerificacion();
                    lstMV[x].idRegMv = x;
                    objMV.setData(lstMV[x]);
                    lstTmpMedVerificacion.push(objMV);
                    if (lstMV[x].published) {
                        jQuery('#lstMedVertificacion > tbody:last').append(objMV.addFilaMedVer(0));
                    }
                }
            }else{
                var objMV = new MedioVerificacion();
                jQuery('#lstMedVertificacion > tbody:last').append( objMV.sinRegistros() );
            }
        }else{
            var objMV = new MedioVerificacion();
            jQuery('#lstMedVertificacion > tbody:last').append( objMV.sinRegistros() );
        }

        //  Cargo los datos de supuestos
        if (typeof (lstSup) != 'undefined') {
            var nrS = lstSup.length;
            
            if( nrS > 0 ){
                for (var x = 0; x < nrS; x++) {
                    var objSup = new Supuesto();
                    lstSup[x].idRegSup = x;
                    objSup.setData(lstSup[x]);
                    lstTmpSupuestos.push(objSup);
                    if (lstSup[x].published) {
                        jQuery('#lstSupuestos > tbody:last').append( objSup.addFilaSupuesto(0) );
                    }
                }
            }else{
                var objSup = new Supuesto();
                jQuery('#lstSupuestos > tbody:last').append( objSup.sinRegistros() );
            }
        }else{
            var objSup = new Supuesto();
            jQuery('#lstSupuestos > tbody:last').append( objSup.sinRegistros() );
        }

    }


    Joomla.submitbutton = function (task)
    {
        if (task == 'medioVerificacion.asignar') {
            //  Numero de Registros de medios de verificacion
            var numRegMV = lstTmpMedVerificacion.length;

            //  Numero de Registros de Supuestos
            var numRegSup = lstTmpSupuestos.length;

            switch (jQuery('#idTipoML').val()) {

                //  Marco Logico de tipo Fin
                case '1':
                    //  Vacio lst de Medios de Verificacion
                    parent.window.objMarcoLogico.fin.lstMedVerificacion = new Array();

                    for (var x = 0; x < numRegMV; x++) {
                        parent.window.objMarcoLogico.fin.lstMedVerificacion.push(lstTmpMedVerificacion[x]);
                    }

                    //  Vacio lst de Medios de Supuestos
                    parent.window.objMarcoLogico.fin.lstSupuestos = new Array();

                    for (var x = 0; x < numRegSup; x++) {
                        parent.window.objMarcoLogico.fin.lstSupuestos.push(lstTmpSupuestos[x]);
                    }

                    //  EnCero arreglo temporal de medios de verificacion
                    lstTmpMedVerificacion = [];

                    //  EnCero arreglo temporal de Supuestos
                    lstTmpSupuestos = [];
                    break;

                    //  Marco Logico de tipo Proposito
                case '2':
                    //  Vacio lst de Medios de Verificacion
                    parent.window.objMarcoLogico.proposito.lstMedVerificacion = new Array();

                    for (var x = 0; x < numRegMV; x++) {
                        parent.window.objMarcoLogico.proposito.lstMedVerificacion.push(lstTmpMedVerificacion[x]);
                    }

                    //  Vacio lst de Medios de Supuestos
                    parent.window.objMarcoLogico.proposito.lstSupuestos = new Array();

                    for (var x = 0; x < numRegSup; x++) {
                        parent.window.objMarcoLogico.proposito.lstSupuestos.push(lstTmpSupuestos[x]);
                    }

                    //  EnCero arreglo temporal de medios de verificacion
                    lstTmpMedVerificacion = [];

                    //  EnCero arreglo temporal de Supuestos
                    lstTmpSupuestos = [];
                    break;

                    //  Marco Logico de tipo lstComponentes
                case '3':
                    //  Obtengo la posicion de un componente en la lista de componentes
                    var pos = jQuery('#idRegML').val();

                    //  Vacio Informacion de medios de verificacion
                    parent.window.objMarcoLogico.lstComponentes[pos].lstMedVerificacion = new Array();

                    for (var x = 0; x < numRegMV; x++) {
                        parent.window.objMarcoLogico.lstComponentes[pos].lstMedVerificacion.push(lstTmpMedVerificacion[x]);
                    }

                    //  Vacio Informacion de Supuestos
                    parent.window.objMarcoLogico.lstComponentes[pos].lstSupuestos = new Array();

                    for (var x = 0; x < numRegSup; x++) {
                        parent.window.objMarcoLogico.lstComponentes[pos].lstSupuestos.push(lstTmpSupuestos[x]);
                    }
                    break;


                case '4':
                    var idCmp = jQuery('#idCmp').val();
                    var idAct = jQuery('#idAct').val();

                    //  Accedo a la lista de medios de verificacion
                    parent.window.objMarcoLogico.lstComponentes[idCmp].lstActividades[idAct].lstMedVerificacion = new Array();

                    for (var x = 0; x < numRegMV; x++) {
                        parent.window.objMarcoLogico.lstComponentes[idCmp].lstActividades[idAct].lstMedVerificacion.push(lstTmpMedVerificacion[x]);
                    }

                    //  Accedo a la lista de Supuestos de Proposito
                    parent.window.objMarcoLogico.lstComponentes[idCmp].lstActividades[idAct].lstSupuestos = new Array();

                    for (var x = 0; x < numRegSup; x++) {
                        parent.window.objMarcoLogico.lstComponentes[idCmp].lstActividades[idAct].lstSupuestos.push(lstTmpSupuestos[x]);
                    }

                    break;
            }
        }

        //  Cierro la ventana modal( popup )
        window.parent.SqueezeBox.close();
    };

});