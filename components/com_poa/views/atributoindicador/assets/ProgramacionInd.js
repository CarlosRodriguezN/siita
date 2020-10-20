jQuery( document ).ready( function () {
    jQuery('.programacionInd').live("click", function () {
        
    });
    
    function calPrgInd (data, numAnio) 
    {
        var base = data;
        var umbral = data;
        
        var valIcrAnual = (umbral - base) / numAnio;
        var sUmbral = valIcrAnual;
        var sBase = base;
        
        for (var j=0; j<= numAnio; j++) {
            var valIcrSem = (sUmbral - sBase) / 2;
            sBase = valIcrSem;
        }
        
        return valIcrAnual;
    }
    
    
});


