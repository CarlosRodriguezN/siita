(function($) {

    /**
     * 
     * Valido el contenido de un objeto
     * 
     * @returns {Number}
     */
    $.fn.validarElemento = function() {
        var ban = 1;

        if (this.val() === "" || this.val() === "0") {
            ban = 0;
            this.attr('class', 'required invalid');

            var lbl = this.selector + '-lbl';
            jQuery(lbl).attr('class', 'hasTip required invalid');
            jQuery(lbl).attr('aria-invalid', 'true');
        }

        return ban;
    };

    /**
     * 
     * Elimino caracteristicas de error de un objeto
     * 
     * @returns {undefined}
     */
    $.fn.delValidaciones = function() {
        this.attr('class', '');
        var lbl = this.selector + '-lbl';

        jQuery(lbl).attr('class', '');
        jQuery(lbl).attr('aria-invalid', '');
    }


    /**
     * 
     * Recorre un comboBox a una determinada posicion
     * 
     * @param {int} posicion    Identificador de la posicion
     *
     */
    $.fn.recorrerCombo = function(posicion) {
        this.each(function() {
            if (parseInt(jQuery(this).val()) === parseInt( posicion ) ){
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

    /**
     * 
     * Vacia el contenido de un ComboBox
     * 
     */
    $.fn.enCerarCombo = function() {
        //  Recorro contenido del combo
        this.each(function() {
            if( parseInt(jQuery(this).val()) !== 0 ) {
                //  Actualizo contenido del combo
                jQuery(this).remove();
            }
        });
    }

}(jQuery));