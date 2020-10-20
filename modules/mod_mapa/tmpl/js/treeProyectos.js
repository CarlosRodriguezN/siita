/*
 * Árbol
 * @description codificacione necesaria para formar el árbol.
 */

var proytree =null;
var childrensJSON =null;
window.addEvent('domready',function(){
    loadTreeProyectos ( ltsProgramas ); // es la primera carga , se cargan los datos de la región oriente
});
   
function loadTreeProyectos ( proyJSON ){
    if  ( proyJSON ){
        ltsProgramas = proyJSON;
        if( proyJSON.length <= 0){
            ltsProgramas = nodeEmpty('Sin Proyectos disponibles');
            document.id('lblProyectos').set('html','Programas');
        } else {
            document.id('lblProyectos').set('html','Programas ('+  proyJSON.length+')');
        }
        var contenedor = document.id('treeProyectos');
        if ( contenedor ){
            proytree = new Mif.Tree({
                container:  contenedor,// tree container
                forest: true,
                initialize: function(){
                    this.initCheckbox('simple');
                    new Mif.Tree.KeyNav(this);
                },
                types: {// node types
                    programa:{
                        hasCheckbox:'true'
                    },
                    proyectos:{
                        hasCheckbox:'true'
                    }
                },
                onCheck: function(node){// evento al dar check en cualquier nodo.
                    if (node && node.type[0]){
                        switch(node.type[0]){
                            case 'programa':
                                checkAllProgramsProyects(node);// Marca el check todos los PROYECTOS de un PROGRAMA
                                showPieChart(node); 
                                break;
                            case 'proyecto':
                                draw ( node );// muestra el marker de un proyecto
                                break;
                        }
                    }
                },
                onUnCheck: function(node){//evento al quitar el check de cualquier nodo
                    if (node && node.type[0]){
                        switch(node.type[0]){
                            case 'programa':
                                unCheckAllProgrmasProyect(node);// Marca el check todos los PROYECTOS de un PROGRAMA
                                hidePieChart(node);
                                break;
                            case 'proyecto':
                                hideGrafic( node.id );// muestra el marker de un proyecto
                                break;
                        }
                    }
                        
                }
            });
            proytree.load({
                json: ltsProgramas
            });
        }
    }
}



function nodeEmpty(text){
    var node= [
    {
        "property": {
            "name": text,
            "id":"root",
            "loadable":"false"
        }
    }];
    return node;
}
