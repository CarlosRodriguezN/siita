var contCopyrigt='';
/*
 *@name addLayer
 *@param node
 *@description Crea una capa que sera ubicada en el mapa
 */
function addLayer( node ){
    if (node && node.type[0] == 'layer'){
        var customParams = [//parametros obligatorios 
        "FORMAT="+node.data.format,
        "LAYERS="+node.data.layer
        ];
        var urlService="";//url de el servidor del que se consumira los WMS
        if(node.parentNode && node.parentNode.data)
            if (urlService.charAt(urlService.length)=='?'){// validación de la cadena URL
                urlService =node.parentNode.data.URLService;
            }else{
                urlService =node.parentNode.data.URLService+'?';
            }
        loadWMS( node.id, map, urlService, customParams );//llamada la función que le permitira mostrar la capa en el mapa
        addCopyrigth(node.parentNode);        //show copyright
    }
}
/*
 *@name delLayer
 *@param node 
 *@description Quita una capa de el mapa
 */
function delLayer( node ){
    if (node.id){
        map.overlayMapTypes.setAt(node.id,null);
        delCopyrigth();
    }
}

/*
 *@name addCopyrigth
 *@param node 
 *@description Agrega el copy right de una institucion de una CAPA
 */
function addCopyrigth(node){
    if (node && node.type[0]=='institucion'){ // si el nodo EXISTE y es de tipo INSITUCION
        var divContentCP = document.getElementById('copyright-control')
        if(divContentCP){// si existe el contenedor
            divContentCP.innerHTML =node.data.copyright
        }else{//creamos el elemento si no existe
            copyrightDIV = document.createElement('div');
            copyrightDIV.id = 'copyright-control';
            copyrightDIV.style.fontSize = '11px';
            copyrightDIV.style.fontFamily = 'Arial, sans-serif';
            copyrightDIV.style.margin = '0 2px 2px 0';
            copyrightDIV.style.whiteSpace = 'nowrap';
            copyrightDIV.index = 0;
            copyrightDIV.innerHTML = node.data.copyright;// le asignamos el contenido al DIV
            map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(copyrightDIV);// colocamos el DIV en el MAPA
        }
    }
}

/*
 *@name delCopyrigth
 *@description Quita el contenido del Copyright de el  mapa, <br>
 *             si existe otras capas seleccionas tomara la utlima como referencia para el <br>
 *             valor del Copyright
 */
function delCopyrigth(){
    var divContentCP = document.getElementById('copyright-control')
    if( divContentCP ){// si existe el contenedor
        divContentCP.innerHTML='';
        getlastCopyright()// Pone el copyright de el ultimo nodo tipo LAYER chekeado
    }
}
/*
 * @name getlastCopyright
 * @description  Pone el copyright de el ultimo nodo tipo LAYER chekeado
 */
function getlastCopyright(){
    contCopyrigt=null;
    proytreeWMS.root.recursive(function(){// Funcion recursiva para recuperar el ultimo nodo tipo "layer" creck
        if ( this.type[0]=='layer' ){
            if(this.state.checked == 'checked' )
                contCopyrigt = this;          
        }
    });
    
    if( contCopyrigt ){
        addCopyrigth(contCopyrigt.parentNode);// Pone el Copyright de el ultimo nodo que esta check
    }
}
/*
 * @name checkAllLayers
 * @description Marca con un check a todas las CAPAS de una INSTITUCIÓN
 * @param node
 */
function checkAllLayers(node){
    if (node && node.type[0]=='institucion'){//Valida si existe nodo y si es de tipo institucion
        var childrens = node.getChildren();
        if ( childrens.length >= 0){
            for(var i=0; i <  childrens.length;i++){ // Recorido de los nodos children de ese nodo
                if ( childrens[i].state.checked != 'checked')
                    childrens[i]['switch']();// Evento click 
            }
        }
    }
}
/*
 * @name unCheckAllLayers
 * @description Quita el  check a todas las CAPAS de una INSTITUCIÓN
 * @param node
 */
function unCheckAllLayers(node){
    if (node && node.type[0] == 'institucion'){//Valida si existe nodo y si es de tipo institución
        var childrens = node.getChildren();
        if (childrens.length >= 0){
            for(var i=0; i < childrens.length;i++){// Recorido de los nodos children de ese nodo
                if (childrens[i].state.checked == 'checked')
                    childrens[i]['switch']();// Evento click 
            }
        }
    }
}
