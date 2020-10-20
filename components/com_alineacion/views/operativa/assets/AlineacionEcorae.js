function Alineacion() {
    this.regAlineacion; // registro de la alineacion
    this.idAlineacion;  // identificador de la alineacion
    this.idOwner;       // a donde pertenece el objetivo a alinear // [ECORAE|UNIDAD GESTION]
    this.idObjetivo;    // identificador del objetivo al que se alinea
    this.descripcion;   // descripcion del objetivo al que se alinea
    this.grDescripcion;   // descripcion del objetivo al que se alinea
    this.published;     // 
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
Alineacion.prototype.setData = function(data) {
    this.regAlineacion = ( data.regAlineacion ) ? parseInt(data.regAlineacion) 
                                                : 0;

    this.idAlineacion = ( data.idAlineacion )   ? parseInt(data.idAlineacion) 
                                                : 0;

    this.idOwner = ( data.idOwner ) ? parseInt(data.idOwner) 
                                    : 0;

    this.descripcion = ( data.descripcion ) ? data.descripcion 
                                            : "";

    if( this.descripcion.length == 0 ){
        this.descripcion = ( data.nombre )  ? data.nombre 
                                            : "";
    }

    this.grDescripcion = ( data.grDescripcion ) ? data.grDescripcion 
                                                : "";

    this.idObjetivo = ( data.idObjetivo )   ? parseInt(data.idObjetivo) 
                                            : 0;

    this.published = ( data.published ) ? parseInt(data.published) 
                                        : 0;
};

/**
 * RECUPERA la DATA desde el FORMULARIO
 * @returns {undefined}
 */
Alineacion.prototype.getDataForm = function() {
    this.idAlineacion = jQuery("#jform_idAlineacion").val();
    this.idOwner = jQuery("#jform_Alineacion").val();
    this.idObjetivo = jQuery("#jform_Objetivo").val();
    this.descripcion = jQuery("#jform_Objetivo option:selected").text();
    this.grDescripcion = jQuery('#jform_Objetivo :selected').parent().attr('label');
    this.published = 1;
};

/**
 * ASIGNA la DATA en el formulario
 * @returns {undefined}
 */
Alineacion.prototype.loadFormData = function() {
    recorrerCombo(jQuery('#jform_Alineacion option'), this.idOwner);
    jQuery('#jform_Alineacion').trigger('change', [this.idObjetivo]);
    jQuery("#jform_idAlineacion").val(this.idAlineacion);

};

/**
 * AGREGA una FILA a la tabla "lstAlineacion"
 * @returns {undefined}
 */
Alineacion.prototype.addTrAlineacion = function() {
    var cad = '';
    cad += '<b>' + this.grDescripcion + '</b><br>' + this.descripcion;
    var file = '';
    file += '<tr id="' + this.regAlineacion + '">';
    file += '   <td>' + cad + '</td>';
    file += '   <td align="center" ><a href="#" class="updAln">' + LB_EDITAR + '</a></td>';
    file += '   <td align="center" ><a href="#" class="delAln">' + LB_ELIMINAR + '</a></td>';
    file += '</tr>';
    jQuery('#lstAlineacion > tbody:last').append(file);
};
/**
 *  ELIMINA una FILA de la tabla 'lstAlineacion'
 * @param {int} regAlineacion   Numero de registro de la alineacion
 * @returns {undefined}
 */
Alineacion.prototype.delTrAlineacion = function(regAlineacion) {
    jQuery('#' + regAlineacion).remove();
};