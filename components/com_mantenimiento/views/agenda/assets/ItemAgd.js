var ItemAgd = function(){
    this.registroItem;
    this.registroOwner;
    this.registroEtr;
    this.idItem;     
    this.idAgenda;     
    this.idEstructura; 
    this.idOwner; 
    this.descripcionItem;
    this.nivelItem;
    this.published = 1;
    
    this.itemsHijos = new Array();
    
};


ItemAgd.prototype.setDtaItemAgd = function( dtaItem )
{
    this.registroItem       = dtaItem.registroItem;
    this.registroOwner      = dtaItem.registroOwner;
    this.registroEtr        = dtaItem.registroEtr;
    this.idItem             = dtaItem.idItem;     
    this.idAgenda           = dtaItem.idAgenda;     
    this.idEstructura       = dtaItem.idEstructura; 
    this.idOwner            = dtaItem.idOwner; 
    this.descripcionItem    = dtaItem.descripcionItem;
    this.nivelItem          = dtaItem.nivelItem;
    this.published          = dtaItem.published;
    this.itemsHijos         = dtaItem.itemsHijos;
    
}