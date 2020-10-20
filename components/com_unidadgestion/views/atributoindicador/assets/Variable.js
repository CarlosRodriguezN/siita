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
    this.idIndicador;
    this.idIndVariable;
    
    this.idTpoElemento = 1;
    this.idElemento = 0;
    
    this.idTpoEntidad;
    this.idEntidad;
    this.idTpoUM;
    this.idUndMedida;
    this.idUndAnalisis;

    this.idUGResponsable;
    this.idUGFuncionario;
    this.idFunResponsable;

    this.nombre;
    this.descripcion;
    this.undMedida;
    this.undAnalisis = '';
    
    this.UGResponsable = '';
    this.UGFuncionario = '';
    this.FunResponsable = '';
    
    this.published = 1;
    
    this.ban = 0;
}

Variable.prototype.toString = function()
{
    return this.nombre + ' ( ' + this.undMedida + ' )';
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
    this.idRegVar = objVariable.idRegVar;
    this.idIndVariable = objVariable.idIndVariable;
    this.idIndicador = objVariable.idIndicador;

    this.idTpoElemento = objVariable.idTpoElemento;
    this.idElemento = objVariable.idElemento;
    
    this.idTpoEntidad = objVariable.idTpoEntidad;
    this.idEntidad = objVariable.idEntidad;
    this.idTpoUM = objVariable.idTpoUM;
    this.idUndMedida = objVariable.idUndMedida;
    this.idUndAnalisis = objVariable.idUndAnalisis;

    this.nombre = objVariable.nombre;
    this.descripcion = objVariable.descripcion;
    this.undAnalisis = objVariable.undAnalisis;
    this.undMedida = objVariable.undMedida;

    //  Unidad Gestion Responsable
    this.idUGResponsable = objVariable.idUGResponsable;
    this.UGResponsable = objVariable.UGResponsable;
    
    //  Funcionario: Unidad de Gestion del Funcionario Responsable
    this.idUGFuncionario = objVariable.idUGFuncionario;
    this.UGFuncionario = objVariable.UGFuncionario;
    
    //  Funcionario Responsable
    this.idFunResponsable = objVariable.idFunResponsable;
    this.FunResponsable = objVariable.FunResponsable;
    
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

    if( this.idTpoElemento == 1 ){
        fila += "<td align='center' width='50px'>"+ COM_PEI_ELEMENTO_VARIABLE +"</td>";
    }else{
        fila += "<td align='center' width='50px'>"+ COM_PEI_ELEMENTO_INDICADOR +"</td>";
    }

    fila += "   <td align='center'>"+ this.toString() +"</td>"
            + " <td align='center'> <a class='updVar'> Editar </a> </td>"
            + " <td align='center'> <a class='delVar'> Eliminar </a> </td>";

    fila += ( ban == 0 )?  "</tr>" 
                        : "";

    return fila;
}