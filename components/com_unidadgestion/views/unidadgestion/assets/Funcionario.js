var Funcionario = function(){
    this.idRegFnci; 
    this.idUgFnci; 
    this.idUg; 
    this.nombreUg; 
    this.idCargoFnci; 
    this.descCargoFnci;
    this.idFuncionario;
    this.nombreFnci; 
    this.fechaInicio; 
    this.fechaFin;
    this.fechaUpd;
    this.published = 1;
    this.lstGrupos;
};


Funcionario.prototype.setDtaFuncionario = function( dtaFnci )
{
    this.idRegFnci      = dtaFnci.idRegFnci; 
    this.idUgFnci       = dtaFnci.idUgFnci; 
    this.idUg           = dtaFnci.idUg; 
    this.nombreUg       = dtaFnci.nombreUg; 
    this.idCargoFnci    = dtaFnci.idCargoFnci; 
    this.descCargoFnci  = dtaFnci.descCargoFnci;
    this.idFuncionario  = dtaFnci.idFuncionario;
    this.nombreFnci     = dtaFnci.nombreFnci; 
    this.fechaInicio    = dtaFnci.fechaInicio; 
    this.fechaFin       = dtaFnci.fechaFin;
    this.fechaUpd       = dtaFnci.fechaUpd;
    this.published      = dtaFnci.published;
    this.lstGrupos      = dtaFnci.lstGrupos;
    
};