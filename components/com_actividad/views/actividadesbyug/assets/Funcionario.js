var Funcionario = function() {
    this.idResponsable;
    this.nombreResponsable;
    this.idFuncionario;
    this.lstActividades = new Array();
    
};

Funcionario.prototype.setDtaFnc = function(data) {
    this.idResponsable      = data.idUgFnci;
    this.nombreResponsable  = data.nombreFnci;
    this.idFuncionario      = data.idFuncionario;
    this.lstActividades     = ( typeof(data.lstActividades) != "undefined" && data.lstActividades.length > 0 ) 
                            ? data.lstActividades 
                            : new Array();
};


