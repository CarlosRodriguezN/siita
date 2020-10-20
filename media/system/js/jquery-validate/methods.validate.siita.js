/**
 * 
 *  Metodsa adicionales de validacion pa ra el sistema SIITA
 *
 */

/**
 * Agrega un metodo para la validacion de select's
 */
jQuery.validator.addMethod('requiredlist', function (value) {
    return (value != '0');
}, "Seleccionar un elemento es requerido");

/**
 * Controla el ingreso de fechad validas con el formato anio-mes-dia
 */
jQuery.validator.addMethod("dateAMD", function(value, element) {
	return this.optional(element) || /^([12]\d)?(\d\d)[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12]\d|3[01])$/.test(value);
}, "Ingrese una fecha correcta con el formato aaaa-mm-dd");

/**
 * Controla el ingreso de valores monetarios diferentes de CERO
 */
jQuery.validator.addMethod('montoUS', function (value) {
    return (value != '$ 0.00');
}, "Monto requerido");

/**
 * Controla el ingreso de valores monetarios diferentes de CERO
 */
jQuery.validator.addMethod('coorLatitud', function (value) {
    var valInput = parseFloat(value);
    return ( valInput >= -85.051128 && valInput <= 85.051128 );
}, "Ingrese un valor en el rango +/-85.051128");

/**
 * 
 */
jQuery.validator.addMethod('coorLongitud', function (value) {
    var valInput = parseFloat(value);
    return ( valInput >= -179.99999999999999 && valInput <= 179.99999999999999 );
}, "Ingrese un valor en el rango +/-179.99999999999999");
