/**
 * 
 * @returns {Item}
 */
function Item() {
    this.idItem = 0;
    this.descripcion = '';
    this.nivel = "";
}

/**
 * SET  de la data de un item
 * @param {type} data
 * @returns {undefined}
 */
Item.prototype.setData = function(data) {
    this.idItem = (data.idItem) ? parseInt(data.idItem) : 0;
    this.descripcion = (data.descripcion) ? data.descripcion : '';
    this.nivel = (data.nivel) ? data.nivel : '';
};
