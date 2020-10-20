
var GestionFuncionarios = function(){
    this.lstFuncionarios = new Array();
};


GestionFuncionarios.prototype.addFuncionario = function( objFnco )
{
    this.lstFuncionarios.push( objFnco );
};

