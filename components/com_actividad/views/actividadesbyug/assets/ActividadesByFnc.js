var ActividadesByFnc = function(){
    this.lstFuncionarios = new Array();
};


ActividadesByFnc.prototype.addFnc = function( funcionario )
{
    this.lstFuncionarios.push( funcionario );
};