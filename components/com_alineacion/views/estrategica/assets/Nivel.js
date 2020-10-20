/**
 * 
 * NIVEL
 */
function Nivel() {
    this.id = 0;
    this.idPadre = 0;
    this.nombre = '';
    this.item = new Item();
}

/**
 * SET de la data del nivel
 * @param {type} data
 * @returns {undefined}
 */
Nivel.prototype.setData = function(data) {
    this.id = (data.id) ? parseInt(data.id) : 0;
    this.idPadre = (data.idPadre) ? parseInt(data.idPadre) : '';
    this.nombre = (data.nombre) ? data.nombre : '';
    if (data.item) {
        this.setItemData(data.item);
    }
};

/**
 * Setea la inforamcion del ITEM
 * @param {type} data
 * @returns {undefined}
 */
Nivel.prototype.setItemData = function(data) {
    this.item.setData(data);
};

/**
 * Pide al Item que recupere su informacion desde el formulario;
 * @param {type} data
 * @returns {undefined}
 */
Nivel.prototype.getFormItemData = function() {
    var data = {
        idItem: parseInt(jQuery('#jform_' + this.id).val()),
        descripcion: jQuery('#jform_' + this.id + ' option:selected').text()
    };
    this.item.setData(data);
};

/**
 * CONSTRUYE el combo viculante del item
 * @returns {undefined}
 */
Nivel.prototype.buildCb = function() {
    var cad = '';

    cad += '<li>';
    cad += '    <label  id="jform_' + this.id + '-lbl" for="jform_' + this.id + '" class="hasTip" title="" style="min-width: 80px;">' + this.nombre + '</label>';
    cad += '    <select id="jform_' + this.id + '" name="jform[' + this.id + ']"   class="widthSelect inputbox itmAgenda" style="width:340px">';
    cad += '        <option value="0">' + LB_SELECCIONE_ + ' ' + this.nombre.toUpperCase() + '</option>';
    cad += '    </select>';
    cad += '</li>';

    jQuery("#lstAgendas").append(cad);
};
