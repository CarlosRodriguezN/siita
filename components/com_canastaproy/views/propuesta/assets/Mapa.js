var mapProyecto;
var proyGraficos=new Array();

function initialize() {
    var LatLngc = new google.maps.LatLng(-1.66872587,-78.65233094);
    var myOptions = {
        center: LatLngc,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    mapProyecto = new google.maps.Map( document.getElementById( "mapa_propuestasdiv" ), myOptions );
} 

jQuery( document ).ready( function(){
    jQuery( '#ugCoordenadas' ).click(function(){
        initialize();
    })
});


/*
 * @name drawHidePoint
 * @param idCoordenada Identificador de las coordenadas dentro de el array "lstCoordenadas"
 */
function drawHidePoint( idRegCoordenada ){
    for(var i=0;i<lstCoordenadas.length;i++){
        if( lstCoordenadas[i].idRegCoordenada == idRegCoordenada ){
            var glPoint= getGLatlng(parseFloat(lstCoordenadas[i].latitud), parseFloat(lstCoordenadas[i].longitud));
            if(glPoint){// si se puedo crear el objeto de google lat,long
                if(lstCoordenadas[i].glPointMarket == null){//  draw
                    //  Creo el Marcador
                    var pointMarker = new google.maps.Marker({
                        position: glPoint,
                        map: mapProyecto
                    });
                    //  Agrego el Objeto de Google al array 
                    lstCoordenadas[i].glPointMarket=pointMarker;
                    mapProyecto.setCenter(glPoint);
                    mapProyecto.setZoom(8);
                    //  Cambio el texto del ancla
                    jQuery("#grafCoor-"+idRegCoordenada).html("Ocultar")
                }else{//    hide
                    //  Oculto el marcador del Array
                    lstCoordenadas[i].glPointMarket.setMap(null);
                    //  Pongo en nulo el objeto
                    lstCoordenadas[i].glPointMarket=null;
                    //  Cambio el texto del ancla
                    jQuery("#grafCoor-"+idRegCoordenada).html("Ver")
                }
            }
            else{
                jAlert("No se puede ubicar el punto.", "SIITA - ECORAE");
            }
        }
    }
}

/*
 *@name getGLatlng
 *@param lat Latitud de una coordenada.
 *@param lng Longitud de una coordenada.
 *@return  Coordenada lat,lng de google.
 *@type gloogle.point
 */
function getGLatlng(lat,lng){
    //aqui validar el rango
    var glPoint= new google.maps.LatLng( lat,lng );
    return glPoint
}

/*
 * 
 */
function drawHideGrafic(idRegGrafico){
    var puntos = new Array();
    //  Recuperamos solo las coordenadas del ese gráfico
    for(var j=0;j<lstUbicacionesGeo.length;j++){
        if(lstUbicacionesGeo[j].idRegGrafico == idRegGrafico){
            puntos = lstUbicacionesGeo[j].lstCoordenadas;
            var idTpoGraf = parseInt(lstUbicacionesGeo[j].tpoGrafico);
        }
    }
    //  Mandamos a dibujar esas coordenadas
    drawHideGooleGrafic(puntos, idTpoGraf, idRegGrafico);
}

/*
 * 
 */
function drawHideGooleGrafic(coordenadas, tipoGrafico, idRegGrafico){
    switch(tipoGrafico) {
        case 1://   Punto
            drawHideGraficPoint(coordenadas, idRegGrafico);
            break;
        case 2://   Linea(Polilinea)
            drawHideGraficPoliLine(coordenadas, idRegGrafico);
            break;
        case 3://   Area(Poligono)
            drawHideGraficPoligone(coordenadas, idRegGrafico);
            break;
        case 4://   Circulo
            drawHideGraficCircle(coordenadas, idRegGrafico)
            break;
    }
}
 
/*
 * 
 */
function drawHideGraficPoint(coordenadas, idRegGrafico){
    if(coordenadas.length > 0){
        if(proyGraficos && !proyGraficos[idRegGrafico] ){
            var glPoint= getGLatlng(parseFloat(coordenadas[0].latitud), parseFloat(coordenadas[0].longitud));
            //  Creo el Marker
            var pointMarker = new google.maps.Marker({
                position: glPoint,
                map: mapProyecto
            });
            proyGraficos[idRegGrafico] = pointMarker;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ocultar");
        }
        else{
            proyGraficos[idRegGrafico].setMap(null);
            proyGraficos[idRegGrafico]=null;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ver");
        }
    }
}

/*
 * 
 */
function drawHideGraficPoliLine(coordenadas, idRegGrafico){
    if(coordenadas.length > 0){
        if(proyGraficos && !proyGraficos[idRegGrafico] ){
            var points = getArrayGooglePoints(coordenadas);
            
            var glPoliline = new google.maps.Polyline({
                path: points,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: mapProyecto
            });
            proyGraficos[idRegGrafico] = glPoliline;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ocultar");
        }else{
            proyGraficos[idRegGrafico].setMap(null);
            proyGraficos[idRegGrafico] = null;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ver");
        }
    }
    
}
/*
 * 
 */
function drawHideGraficPoligone(coordenadas, idRegGrafico){
    if(coordenadas.length > 0){
        if(proyGraficos && !proyGraficos[idRegGrafico] ){
            var points = getArrayGooglePoints(coordenadas);
            points.push(points[0]);//el ultimo es el primero para cerrar el poligono
            var glPoligone = new google.maps.Polygon({
                paths: points,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
                map: mapProyecto
            });
            proyGraficos[idRegGrafico] = glPoligone;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ocultar");
        }else{
            proyGraficos[idRegGrafico].setMap(null);
            proyGraficos[idRegGrafico] = null;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ver");
        }
    }
    
}
/*
 * 
 */
function drawHideGraficCircle(coordenadas, idRegGrafico){
    if(coordenadas.length > 0){
        if(proyGraficos && !proyGraficos[idRegGrafico] ){
            if(coordenadas[0]&& coordenadas[1]){
                var centro=getGLatlng(coordenadas[0].latitud,coordenadas[0].longitud);
                var borde=getGLatlng(coordenadas[1].latitud,coordenadas[1].longitud);
                var radio = getRadioRevese(centro, borde);
                var populationOptions = {
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    map: mapProyecto,
                    center: getGLatlng(coordenadas[0].latitud,coordenadas[0].longitud),
                    radius: radio 
                };
                //todo Es necesario hacer un bound
                
                mapProyecto.setCenter(getGLatlng(coordenadas[0].latitud,coordenadas[0].longitud));
                var glCircle = new google.maps.Circle(populationOptions);
                proyGraficos[idRegGrafico] = glCircle;
            }
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ocultar");
        }else{
            proyGraficos[idRegGrafico].setMap(null);
            proyGraficos[idRegGrafico] = null;
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ver");
        }
    }
    
}

/*
 * 
 */
function getArrayGooglePoints(coordenadas){
    var points =new Array();
    for(var j=0;j<coordenadas.length;j++){
        var point = getGLatlng(parseFloat(coordenadas[j].latitud), parseFloat(coordenadas[j].longitud));
        points.push( point );
    }
    return points;
}