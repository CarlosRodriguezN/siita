/**
 * 
 * ALINEACION
 */

oAlineacion = new Alineacion();

function Alineacion() {
    this.idRegistro = (regAlineacion != -1) ? regAlineacion 
                                            : oGestionAlineacion.lstAlineaciones.length;

    this.idAgenda       = 0;
    this.idAlineacion   = 0;
    this.nombre         = '';
    this.published      = 1;
    this.niveles        = new Array();
}

/**
 * Setea la Inforamcion basica del Nivel
 * @param {type} data
 * @returns {undefined}
 */
Alineacion.prototype.setData = function(data) {
    this.idRegistro = (data.idRegistro) ? data.idRegistro 
                                        : 0;

    this.idAgenda = (data.idAgenda) ? data.idAgenda 
                                    : 0;

    this.idAlineacion = (data.idAlineacion) ? data.idAlineacion 
                                            : 0;

    this.nombre = (data.nombre) ? data.nombre 
                                : '';

    this.published = (data.published)   ? data.published 
                                        : 0;

    if (data.niveles) {
        this.addLstNiveles(data.niveles);
    }
};


/**
 * 
 * @param {type} niveles
 * @returns {undefined}
 */
Alineacion.prototype.addLstNiveles = function(niveles) {
    if (niveles) {
        for (var j = 0; j < niveles.length; j++) {
            this.addNivel(niveles[j]);
        }
    }
};

/**
 * Agrega un nivel a la estructura de la Agenda
 * @param {type} data
 * @returns {undefined}
 */
Alineacion.prototype.addNivel = function(data) {
    var oNivel = new Nivel();
    oNivel.setData(data);
    this.niveles.push(oNivel);
};

/**
 * 
 * Recupera la informacion desde el formulario.
 * @returns {undefined}
 */
Alineacion.prototype.getFormItemsData = function() {
    if (this.niveles.length > 0) {
        for (var j = 0; j < this.niveles.length; j++) {
            this.niveles[j].getFormItemData();
        }
    }
};

/**
 * Dibuja los Niveles en el HTML
 * @returns {undefined}
 */
Alineacion.prototype.loadNivel = function() {
    recorrerCombo(jQuery('#jform_idAgenda option'), this.idAgenda);
    jQuery('#lstAgendas li:not(:first)').remove();
    if (this.niveles.length > 0) {
        for (var j = 0; j < this.niveles.length; j++) {
            this.niveles[j].buildCb();
        }
    }
};

/**
 * 
 * @returns {undefined}
 */
Alineacion.prototype.addTrAlineacion = function() {
    var cad = '<b>' + this.nombre + '</b><br/>';
    if (this.niveles.length > 0) {
        for (var j = 0; j < this.niveles.length; j++) {
            if ( this.niveles[j].item.idItem != 0 ){
                cad += '<br/>' + this.niveles[j].item.descripcion;
            } else {
                cad += '<br/> Sin ' + this.niveles[j].nombre;
            }
        }
    }
    var file = '';
    file += '<tr id="' + this.idRegistro + '">';
    file += '   <td>' + cad + '</td>';
    file += '   <td align="center" ><a href="#" class="updAln">' + LB_EDITAR + '</a></td>';
    file += '   <td align="center" ><a href="#" class="delAln">' + LB_ELIMINAR + '</a></td>';
    file += '</tr>';

    jQuery('#lstAgnd > tbody:last').append(file);
};

Alineacion.prototype.validateObj = function(){
    switch (true){
        case (this.idAgenda == 0):
            this.valido = SELECIONE_AGENDA; 
        break;
            
        case ( this.idAgenda != 0 && this.niveles.length > 0 ):
            if ( this.niveles[0].item.idItem != 0){
                this.valido = true;
            } else {
                this.valido = NO_VALIDO;
            }
        break;
    }
};


Alineacion.prototype.semaforo = function(){
    return;
}