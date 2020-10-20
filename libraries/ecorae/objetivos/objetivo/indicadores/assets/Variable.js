/**
 * 
 * Gestion de informacion de una variable
 * 
 * @param {type} idReg          Identificador de Registro de Variables
 * @param {type} idIndVariable  Identificador de asociacion Indicador Variable
 * @param {type} idIndicador    Identificador de Indicador
 * @param {type} idVariable     Identificador de Variable
 * @param {type} nombre         Nombre de Variable
 * @param {type} descripcion    Descripcion de Variable
 * @param {type} idTpoUM        Identificador de Tipo de Unidad de Medida
 * @param {type} idUndMedida    Identificador de Unidad de Medida
 * @param {type} undMedida      Nombre de Unidad de Medida
 * @param {type} idUndAnalisis  Identificador de Unidad de Analisis
 * @param {type} undAnalisis    Nombre de Unidad de Analisis
 * 
 * @param {type} ban            Bandera con la finalidad de diferenciar una variable nueva de una ya registrada
 *                              0:  Registrada
                                1:  Nueva
 * 
 * @returns {Variable}
 */
var Variable = function(){
    this.idRegVar;
    this.idIndVariable;
    this.idIndicador;
    this.idVariable;
    this.nombre;
    this.descripcion;
    this.idTpoUM;
    this.idUndMedida;
    this.undMedida;
    this.idUndAnalisis;
    this.undAnalisis = '';
    
    //  Unidad Gestion Responsable
    this.idUGResponsable;
    
    //  Unidad de Gestion del Funcionario Responsable
    this.idUGFuncionario;
    
    //  Funcionario Responsable
    this.idFunResponsable;
    
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
 */
Variable.prototype.setDtaVariable = function( objVariable ){
    this.idRegVar = objVariable.idRegVar;
    this.idIndVariable = objVariable.idIndVariable;
    this.idIndicador = objVariable.idIndicador;
    this.idVariable = objVariable.idVariable;
    this.nombre = objVariable.nombre;
    this.descripcion = objVariable.descripcion;
    this.idTpoUM = objVariable.idTpoUM;
    this.idUndMedida = objVariable.idUndMedida;
    this.undMedida = objVariable.undMedida;
    this.idUndAnalisis = objVariable.idUndAnalisis;
    this.undAnalisis = objVariable.undAnalisis;
    
    //  Unidad Gestion Responsable
    this.idUGResponsable = objVariable.idUGResponsable;
    
    //  Unidad de Gestion del Funcionario Responsable
    this.idUGFuncionario = objVariable.idUGFuncionario;
    
    //  Funcionario Responsable
    this.idFunResponsable = objVariable.idFunResponsable;
    
    this.ban = objVariable.ban;
    
    this.published = 1;
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
    var fila = ( ban == 0 ) ? "<tr id='"+ this.idRegVar +"'>" 
                            : "";

    fila += "   <td align='center'>"+ this.nombre +"</td>"
            + " <td align='center'>"+ this.undMedida +"</td>"
            + " <td align='center'>"+ this.undAnalisis +"</td>"
            + " <td align='center'> <a class='updVar'> Editar </a> </td>"
            + " <td align='center'> <a class='delVar'> Eliminar </a> </td>";

    fila += ( ban == 0 )?  "</tr>" 
                        : "";

    return fila;
}