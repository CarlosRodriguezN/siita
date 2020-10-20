//
//  CLASE MARCO LOGICO
//
var MarcoLogico = function(){
    this.fin;
    this.proposito;
    
    //  Lista de componentes relacionados con un determinado Marco Logico
    this.lstComponentes = new Array();
}

/**
 * 
 * Gestiona el registro del fin de un Marco Logico
 * 
 * @param {Object} fin  Objeto Fin
 * 
 * @returns {undefined}
 */
MarcoLogico.prototype.addFin = function( fin )
{
    this.fin = fin;
}

/**
 * 
 * Gestiona el Registro del Proposito de un Marco Logico
 * 
 * @param {Object} proposito  Objeto proposito
 * @returns {undefined}
 */
MarcoLogico.prototype.addProposito = function( proposito )
{
    this.proposito = proposito;
}

/**
 * 
 * Retorna el numero de elementos de la lista de componentes
 * 
 * @returns { integer }
 */
MarcoLogico.prototype.numRegComponentes = function()
{
   return this.lstComponentes.length;
}

/**
 * 
 * Retorna el N+1 de la lista de componentes registrados
 * 
 * @returns {integer}
 */
MarcoLogico.prototype.nuevoIdComponente = function()
{
    var numReg = this.lstComponentes.length;
    return ++numReg;
}

/**
 * 
 * Verifica la existencia de un componente en la lista de componentes
 * 
 * @param {type} dataComponente Informacion de un componente
 * 
 * @returns {Boolean}
 * 
 */
MarcoLogico.prototype.existeRegComponente = function( cmp )
{
    var nrc = this.numRegComponentes();
    for( var x = 0; x < nrc; x++ ){
        if( cmp.toString() == this.lstComponentes[x].toString() && cmp.published == this.lstComponentes[x].published ){
            return true;
        }
    }
    
    return false;
}

/**
 * Busca por nombre un determinado componente
 * @param {type} nombre Nombre del componente a buscar
 * @returns {Boolean}
 */
MarcoLogico.prototype.posCmpNombre = function( nombre )
{
    var nrc = this.numRegComponentes();
    for( var x = 0; x < nrc; x++ ){
        if( this.lstComponentes[x].toString() == nombre ){
            return x;
        }
    }
    
    return false;
}

/**
 * Retorna objeto componente de una determinada 
 * posicion de la lista de componentes
 * @param {type} pos    Posicion en la lista de componentes
 * @returns {undefined}
 */
MarcoLogico.prototype.getInfoComponente = function( pos )
{
    var nrlc = this.lstComponentes.length;
    
    for( var x = 0; x < nrlc; x++ ){
        if( this.lstComponentes[x].idRegComponente == pos ){
            return this.lstComponentes[x];
        }
    }
    
    return false;
}

/**
 * 
 * Actualiza informacion de un determinado componente perteneciente 
 * a la lista de componentes
 * 
 * @param {int} pos     Posicion en la lista de componentes
 * @param {objeto} cmp  Componente con informacion a actualizar
 * 
 * @returns {Boolean}
 * 
 */
MarcoLogico.prototype.updComponente = function( pos, cmp )
{
    var nrlc = this.lstComponentes.length;
    
    for( var x = 0; x < nrlc; x++ ){
        if( this.lstComponentes[x].idRegComponente == pos ){
            this.lstComponentes[x].updDtaComponente( cmp.nombreCmp, cmp.descripcionCmp );
            
            return true;
        }
    }
    
    return false;
}

/**
 * 
 * Gestiona la eliminacion de un componente, cambiando 
 * el valor del atributo published 1
 * 
 * @param {type} pos    Posicion del componente a eliminar
 * @returns {Boolean}
 */
MarcoLogico.prototype.delComponente = function( pos )
{
    var nrlc = this.lstComponentes.length;
    
    for( var x = 0; x < nrlc; x++ ){
        if( this.lstComponentes[x].idRegComponente == pos ){
            this.lstComponentes[x].delComponente();
            return true;
        }
    }
    
    return false;
}


/**
 * 
 * Elimino una actividad de un determinado componente
 * 
 * @param {type} idCmp  Identificador del componente
 * @param {type} idAct  Identificador de Actividad
 * 
 * @returns {undefined}
 * 
 */
MarcoLogico.prototype.delActividadComponente = function( idCmp, idAct )
{
    var nrlc = this.lstComponentes.length;
    var ban = false;
    
    for( var x = 0; x < nrlc; x++ ){
        if( parseInt( this.lstComponentes[x].idRegComponente ) === idCmp ){
            var nrAct = this.lstComponentes[x].lstActividades.length;
            var dtaAct = this.lstComponentes[x].lstActividades;
            
            for( var y = 0; y < nrAct; y++ ){
                if( parseInt( dtaAct[y].idRegActividad ) === idAct ){
                    dtaAct[y].delActividad();
                    ban = true;
                }
            }
        }
    }
    
    return ban;
}