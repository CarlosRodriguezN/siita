/**
 * 
 * Gestion de informacion de una variable
 * 
 * @param {type} idReg          Identificador de Registro de Variables
 * @param {type} idIndicador    Identificador de Indicador
 * @param {type} idIndVariable  Identificador de asociacion Indicador Variable
 * 
 * @param {type} idTpoElemento  Identificador del Tipo de Variable
 *                              1: Variable
 *                              2: Indicador
 *                              
 * @param {type} idElemento     Identificador de Indicador / Variable asociado a la formula que forma parte del indicador
 *                              por default cero ( 0 )
 * 
 * @param {type} nombre         Nombre de Variable
 * @param {type} descripcion    Descripcion de Variable
 * @param {type} idTpoUM        Identificador de Tipo de Unidad de Medida
 * @param {type} idUndMedida    Identificador de Unidad de Medida
 * @param {type} undMedida      Nombre de Unidad de Medida
 * @param {type} idUndAnalisis  Identificador de Unidad de Analisis
 * @param {type} undAnalisis    Nombre de Unidad de Analisis
 * 
 * 
 * @param {type} ban            Bandera con la finalidad de diferenciar una variable nueva de una ya registrada
 *                              0:  Registrada
                                1:  Nueva
 * 
 * @returns {Variable}
 * 
 */
var Variable = function(){
    this.idRegVar;   
    this.idTpoUM;

    this.idVariable
    this.nombre;
    this.alias;
    this.descripcion;
    
    this.idUndMedida;
    this.undMedida;
    
    this.idUndAnalisis;
    this.undAnalisis = '';

    this.published = 1;
    
    this.ban = 0;
}

Variable.prototype.toString = function()
{
    return this.nombre;
}


/**
 * 
 * Seteo Informacion de Variable
 * 
 * @param {obj} objVariable     Objeto con informacion de una determinada variable
 * @returns {undefined}
 * 
 */
Variable.prototype.setDtaVariable = function( objVariable ){
    this.idRegVar       = objVariable.idRegVar;

    this.idVariable     = objVariable.idVariable;
    this.nombre         = objVariable.nombre;
    this.alias          = objVariable.alias;
    this.descripcion    = objVariable.descripcion;
    
    this.idTpoUM        = objVariable.idTpoUM;
    this.idUndMedida    = objVariable.idUndMedida;
    this.undMedida      = objVariable.undMedida;
    
    this.idUndAnalisis  = objVariable.idUndAnalisis;
    this.undAnalisis    = objVariable.undAnalisis;
    
    this.ban = objVariable.ban;
    
    this.published = ( typeOf( objVariable.published ) === "null" ) 
                        ? 1
                        : objVariable.published;
}


/**
 * 
 * Gestiono la informacion de una fila en una tabla
 * 
 * @param {int} ban     Bandera
 * 
 * @returns {String}
 * 
 */
Variable.prototype.addFilaVar = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='"+ this.idRegVar +"'>" 
                            : "";

    if( this.idTpoElemento === 1 ){
        fila += "<td align='center' width='50px' style='vertical-align: middle;'>"+ COM_INDICADORES_ELEMENTO_VARIABLE +"</td>";
    }else{
        fila += "<td align='center' width='50px' style='vertical-align: middle;'>"+ COM_INDICADORES_ELEMENTO_INDICADOR +"</td>";
    }

    fila += "   <td align='left'> <a class='addFormula'> "+ this.toString() +"</a> </td>"
            + " <td align='center' style='vertical-align: middle;'> <a class='updVar'> Editar </a> </td>"
            + " <td align='center' style='vertical-align: middle;'> <a class='delVar'> Eliminar </a> </td>";

    fila += ( ban === 0 )?  "</tr>" 
                        : "";

    return fila;
}