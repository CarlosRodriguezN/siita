var proytreeWMS =null;
var childrensJSON =null;
window.addEvent('domready',function(){
    loadTreeWMS(JSONPrueba());
})
   
function loadTreeWMS( proyJSON ){
    if  ( proyJSON ){
        proyJSON = ltsWMSLayers;
        if( proyJSON.length == 0){
            proyJSON = nodeEmpty('Sin mapas definidos.');
        }
        var contenedor = document.id('treeWMS')
        if ( contenedor ){
            proytreeWMS = new Mif.Tree({
                container:  contenedor,// tree container
                forest: true,
                initialize: function(){
                    this.initCheckbox('simple');
                    new Mif.Tree.KeyNav(this);
                },
                types:{// node types
                    proyectos:{
                        hasCheckbox:'true'
                    },
                    layer:{
                        hasCheckbox:'true'
                    }
                },
                onCheck: function(node){//evento que se dispara al dar el check de un nodo
                    if (node && node.type[0]){
                        switch(node.type[0]){
                            case 'institucion':
                                checkAllLayers(node);// Marca con un check a todas las CAPAS de una INSTITUCIÓN
                                break;
                            case 'layer':
                                addLayer(node);//Agrega una capa al mapa
                                break;
                                    
                        }
                    }
                },
                onUnCheck: function(node){//evento que se dispara al quitar el check de un nodo
                    if (node && node.type[0]){
                        switch(node.type[0]){
                            case 'institucion':
                                unCheckAllLayers(node);// Marca con un check a todas las CAPAS de una INSTITUCIÓN
                                break;
                            case 'layer':
                                delLayer( node );//Quita una capa de el mapa
                                break;
                                    
                        }
                    }
                }
            });
            proytreeWMS.load({
                json: proyJSON
            });
        }
    }
}

function JSONPrueba(){
    var JSONArrayPrueba = 
    [
    {
        "property": {
            "name": "IGM"
        },
        "children": [
        {
            "property": {
                "name": "Poblados",
                "id":"0",
                "data":[{
                    "url":"http://www.geoportaligm.gob.ec/nacional/wms?",
                    "layer":"igm:poblados",
                    "format":"image/png"
                }]
            }
        },
        {
            "property": {
                "name": "Linea Costera",
                "id":"0",
                "data":[{
                    "url":"http://www.geoportaligm.gob.ec/nacional/wms?",
                    "layer":"igm:islas",
                    "format":"image/png"
                }]
            },
            "state": {
                "open": true
            }
        }
        ]
    }
    ]
    return JSONArrayPrueba
}
