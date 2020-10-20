var Poa = function(){
    this.idRegPoa; 
    this.idPoa; 
    this.idTipoPlanPoa; 
    this.idInstitucionPoa; 
    this.idEntidadPoa; 
    this.idPadrePoa; 
    this.descripcionPoa;
    this.fechaInicioPoa;
    this.fechaFinPoa;
    this.vigenciaPoa; 
    this.aliasPoa;
    
    this.lstObjetivos = new Array();
    
    this.published = 1;
};


Poa.prototype.setDtaPoa = function( dtaPoa )
{
    this.idRegPoa           = dtaPoa.idRegPoa; 
    this.idPoa              = dtaPoa.idPoa; 
    this.idTipoPlanPoa      = dtaPoa.idTipoPlanPoa; 
    this.idInstitucionPoa   = dtaPoa.idInstitucionPoa;
    this.idEntidadPoa       = dtaPoa.idEntidadPoa; 
    this.idPadrePoa         = dtaPoa.idPadrePoa; 
    this.descripcionPoa     = dtaPoa.descripcionPoa;
    this.fechaInicioPoa     = dtaPoa.fechaInicioPoa;
    this.fechaFinPoa        = dtaPoa.fechaFinPoa;
    this.vigenciaPoa        = dtaPoa.vigenciaPoa; 
    this.aliasPoa           = dtaPoa.aliasPoa;
    
    this.lstObjetivos = ( typeof (dtaPoa.lstObjetivos) != "undefined" && dtaPoa.lstObjetivos.length > 0 ) 
                        ? dtaPoa.lstObjetivos 
                        : new Array() ;
};
