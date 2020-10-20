// Codigo Mejorado version 1.01 Paolo Grimaldi
var pi = 3.14159265358979;

/* Constantes del elipsoide para  WGS84) */
var sm_a = 6378137.0;
var sm_b = 6356752.314;
var sm_EccSquared = 6.69437999013e-03;

var UTMScaleFactor = 0.9996;


/*
    * Funcion DegToRad
    * Grados a Radianes
    */
function DegToRad (deg)
{
    return (deg / 180.0 * pi)
}


/*
    * Fucnion RatoDeg
    *
    * Radianes a Grados.
    *
    */
function RadToDeg (rad)
{
    return (rad / pi * 180.0)
}




/*
    * Funcion ArcLengthOfMeridian
    *
    * Calcula la distancia del elipsoide desde el equador hasta una latitud dada.
    *
  
    * Entradas:
    *     phi - Latitud del punto en radianes.
    *
    * Globales:
    *     sm_a - Modelo de elipsoide del eje mayor.
    *     sm_b - MOdelo de elipsoide del eje menor.
    *
    * Salidas:
    *    La distansia del elipsoide al el punto deseado del ecuador en metros.
    *
    */
function ArcLengthOfMeridian (phi)
{
    var alpha, beta, gamma, delta, epsilon, n;
    var result;

    /* Precalcular n */
    n = (sm_a - sm_b) / (sm_a + sm_b);

    /* Precalculate alpha */
    alpha = ((sm_a + sm_b) / 2.0)
    * (1.0 + (Math.pow (n, 2.0) / 4.0) + (Math.pow (n, 4.0) / 64.0));

    /* Precalculaar beta */
    beta = (-3.0 * n / 2.0) + (9.0 * Math.pow (n, 3.0) / 16.0)
    + (-3.0 * Math.pow (n, 5.0) / 32.0);

    /* Precalcular gamma */
    gamma = (15.0 * Math.pow (n, 2.0) / 16.0)
    + (-15.0 * Math.pow (n, 4.0) / 32.0);
    
    /* Precalcular delta */
    delta = (-35.0 * Math.pow (n, 3.0) / 48.0)
    + (105.0 * Math.pow (n, 5.0) / 256.0);
    
    /* Precalcular epsilon */
    epsilon = (315.0 * Math.pow (n, 4.0) / 512.0);
    
    /* Calcular la suma de la serie y regresar el valor... ufff */
    result = alpha
    * (phi + (beta * Math.sin (2.0 * phi))
        + (gamma * Math.sin (4.0 * phi))
        + (delta * Math.sin (6.0 * phi))
        + (epsilon * Math.sin (8.0 * phi)));

    return result;
}



/*
    * UTMCentralMeridian
    *
    * Determina el meridiano centrala para una zona UTM.
    *
    * Entrada:
    *     zone - Un valor entero designando la zona UTM, rango [1,60].
    *
    * Salida:
    *   El meridiano central de la zona dada, en radianes o cero
    *   si la zona esta fuera de rango [1,60].
    *   El rango del meridiano central es el equivalente de [-177,+177].
    *
    */
function UTMCentralMeridian (zone)
{
    var cmeridian;

    cmeridian = DegToRad (-183.0 + (zone * 6.0));
    
    return cmeridian;
}



/*
    * FootpointLatitude
    *
    * Conputa la huella de latitud para usar en la conversion de coordenaComputes the footpoint latitude for use in converting transverse
    * a coordenadas elipsoidales.
    *
    *
    * Entrada:
    *   y - La coordenada con tendencia al norte, en metros.
    *
    * Salida:
    *   La huella de latitud, en radianes.
    *
    */
function FootpointLatitude (y)
{
    var y_, alpha_, beta_, gamma_, delta_, epsilon_, n;
    var result;
        
    /* Precalcular n  */
    n = (sm_a - sm_b) / (sm_a + sm_b);
        	
    /* Precalcular alpha_  */
  
    alpha_ = ((sm_a + sm_b) / 2.0)
    * (1 + (Math.pow (n, 2.0) / 4) + (Math.pow (n, 4.0) / 64));
        
    /* Precalcular y_  */
    y_ = y / alpha_;
        
    /* Precalcular beta_  */
    beta_ = (3.0 * n / 2.0) + (-27.0 * Math.pow (n, 3.0) / 32.0)
    + (269.0 * Math.pow (n, 5.0) / 512.0);
        
    /* Precalcular gamma_  */
    gamma_ = (21.0 * Math.pow (n, 2.0) / 16.0)
    + (-55.0 * Math.pow (n, 4.0) / 32.0);
        	
    /* Precalcular delta_  */
    delta_ = (151.0 * Math.pow (n, 3.0) / 96.0)
    + (-417.0 * Math.pow (n, 5.0) / 128.0);
        	
    /* Precalcular epsilon_  */
    epsilon_ = (1097.0 * Math.pow (n, 4.0) / 512.0);
        	
    /* Calcular la suma de la serie  */
    result = y_ + (beta_ * Math.sin (2.0 * y_))
    + (gamma_ * Math.sin (4.0 * y_))
    + (delta_ * Math.sin (6.0 * y_))
    + (epsilon_ * Math.sin (8.0 * y_));
        
    return result;
}



/*
    * MapLatLonToXY
    *
    * Convierte un par de latitud / longitud a coordenadas Transversas de Mercator x y Y
    *  Note that Transverse Mercator is not
    * Notar que estas coordenadas no son UTM todavia falta hacer una conversion de escala.
    *
    * Entrada:
    *    phi - Latitud del punto, en radians.
    *    lambda - Longitud del punto, en radians.
    *    lambda0 -Longitud del meridiano central a utilizar, en radianes.
    *
    * Salida:
    *    xy - Un arreglo de 2 elementos conteniendo las coordenadas x y y del punto computado
    *
    * Regresa:
    *    La funcion no regresa ningun valor
    *
    */
function MapLatLonToXY (phi, lambda, lambda0, xy)
{
    var N, nu2, ep2, t, t2, l;
    var l3coef, l4coef, l5coef, l6coef, l7coef, l8coef;
    var tmp;

    /* Precalcular ep2 */
    ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
    
    /* Precalcular nu2 */
    nu2 = ep2 * Math.pow (Math.cos (phi), 2.0);
    
    /* Precalcular N */
    N = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nu2));
    
    /* Precalcular t */
    t = Math.tan (phi);
    t2 = t * t;
    tmp = (t2 * t2 * t2) - Math.pow (t, 6.0);

    /* Precalcular l */
    l = lambda - lambda0;
    
    /* Precalcular los coeficientes para l**n en las ecuaciones de abajo
       na pueda leer los valores de x y y
           and northing
           -- l**1 y l**2 tienen coeficientes de 1.0 */
    l3coef = 1.0 - t2 + nu2;
    
    l4coef = 5.0 - t2 + 9 * nu2 + 4.0 * (nu2 * nu2);
    
    l5coef = 5.0 - 18.0 * t2 + (t2 * t2) + 14.0 * nu2
    - 58.0 * t2 * nu2;
    
    l6coef = 61.0 - 58.0 * t2 + (t2 * t2) + 270.0 * nu2
    - 330.0 * t2 * nu2;
    
    l7coef = 61.0 - 479.0 * t2 + 179.0 * (t2 * t2) - (t2 * t2 * t2);
    
    l8coef = 1385.0 - 3111.0 * t2 + 543.0 * (t2 * t2) - (t2 * t2 * t2);
    
    /* Calcular este (x) */
    xy[0] = N * Math.cos (phi) * l
    + (N / 6.0 * Math.pow (Math.cos (phi), 3.0) * l3coef * Math.pow (l, 3.0))
    + (N / 120.0 * Math.pow (Math.cos (phi), 5.0) * l5coef * Math.pow (l, 5.0))
    + (N / 5040.0 * Math.pow (Math.cos (phi), 7.0) * l7coef * Math.pow (l, 7.0));
    
    /* Calcular norte (y) */
    xy[1] = ArcLengthOfMeridian (phi)
    + (t / 2.0 * N * Math.pow (Math.cos (phi), 2.0) * Math.pow (l, 2.0))
    + (t / 24.0 * N * Math.pow (Math.cos (phi), 4.0) * l4coef * Math.pow (l, 4.0))
    + (t / 720.0 * N * Math.pow (Math.cos (phi), 6.0) * l6coef * Math.pow (l, 6.0))
    + (t / 40320.0 * N * Math.pow (Math.cos (phi), 8.0) * l8coef * Math.pow (l, 8.0));
    
    return;
}
    
    
    
/*
    * MapXYToLatLon
    *
    * Convierte  coordenadas Transversas de Mercator x y Y a un par de latitud / longitud 
    *  Note that Transverse Mercator is not
    * Notar que estas coordenadas no son UTM todavia falta hacer una conversion de escala.
    *
    *
    * Entradas:
    *   x - el este del punto, en metros.
    *   y - el norte del punto, en metross.
    *   lambda0 - Longitud del meridiano central a utilizar en radianes.
    *
    * Salidas:
    *   philambda - Un arreglo de 2 elementos conteniendo latitud y longitud en radianes
    *               
    *
    * Regresa:
    *   Esta funcion no regresa ningun valor.
    *
    *   Nota
    *   x1frac, x2frac, x2poly, x3poly, etc. Son para mejorar la legibilidad y optimizar el computo
    *  
    *
    */
function MapXYToLatLon (x, y, lambda0, philambda)
{
    var phif, Nf, Nfpow, nuf2, ep2, tf, tf2, tf4, cf;
    var x1frac, x2frac, x3frac, x4frac, x5frac, x6frac, x7frac, x8frac;
    var x2poly, x3poly, x4poly, x5poly, x6poly, x7poly, x8poly;
    	
    /* Obtener el valor de phif, la huella de latitud. */
    phif = FootpointLatitude (y);
        	
    /* Precalcular ep2 */
    ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0))
    / Math.pow (sm_b, 2.0);
        	
    /* Precalcular cos (phif) */
    cf = Math.cos (phif);
        	
    /* Precalcular nuf2 */
    nuf2 = ep2 * Math.pow (cf, 2.0);
        	
    /* Precalcular Nf y initializar Nfpow */
    Nf = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nuf2));
    Nfpow = Nf;
        	
    /* Precalcular tf */
    tf = Math.tan (phif);
    tf2 = tf * tf;
    tf4 = tf2 * tf2;
        
    /* Precalcular los coeficientes fraccionarios de x**n en las equaciones de abajo
          para simplificar las expresiones de latitude y longitude. */
    x1frac = 1.0 / (Nfpow * cf);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**2) */
    x2frac = tf / (2.0 * Nfpow);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**3) */
    x3frac = 1.0 / (6.0 * Nfpow * cf);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**4) */
    x4frac = tf / (24.0 * Nfpow);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**5) */
    x5frac = 1.0 / (120.0 * Nfpow * cf);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**6) */
    x6frac = tf / (720.0 * Nfpow);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**7) */
    x7frac = 1.0 / (5040.0 * Nfpow * cf);
        
    Nfpow *= Nf;   /* ahora es igual a Nf**8) */
    x8frac = tf / (40320.0 * Nfpow);
        
    /* Precalcular los coeficientes polinomiales de x**n.
           -- x**1 no tiene un coeficiente polinomial. */
    x2poly = -1.0 - nuf2;
        
    x3poly = -1.0 - 2 * tf2 - nuf2;
        
    x4poly = 5.0 + 3.0 * tf2 + 6.0 * nuf2 - 6.0 * tf2 * nuf2
    - 3.0 * (nuf2 *nuf2) - 9.0 * tf2 * (nuf2 * nuf2);
        
    x5poly = 5.0 + 28.0 * tf2 + 24.0 * tf4 + 6.0 * nuf2 + 8.0 * tf2 * nuf2;
        
    x6poly = -61.0 - 90.0 * tf2 - 45.0 * tf4 - 107.0 * nuf2
    + 162.0 * tf2 * nuf2;
        
    x7poly = -61.0 - 662.0 * tf2 - 1320.0 * tf4 - 720.0 * (tf4 * tf2);
        
    x8poly = 1385.0 + 3633.0 * tf2 + 4095.0 * tf4 + 1575 * (tf4 * tf2);
        	
    /* Calcular latitud */
    philambda[0] = phif + x2frac * x2poly * (x * x)
    + x4frac * x4poly * Math.pow (x, 4.0)
    + x6frac * x6poly * Math.pow (x, 6.0)
    + x8frac * x8poly * Math.pow (x, 8.0);
        	
    /* Calcular longitud */
    philambda[1] = lambda0 + x1frac * x
    + x3frac * x3poly * Math.pow (x, 3.0)
    + x5frac * x5poly * Math.pow (x, 5.0)
    + x7frac * x7poly * Math.pow (x, 7.0);
        	
    return;
}




/*
    * LatLonToUTMXY
    *
    * Convierte un par latitude/longitud a coordenadas x and y UTM
    * 
    *
    * Entrada:
    *   lat - Latitude del punto, en radianes.
    *   lon - Longitud del punto, en radianes.
    *   zone - la zona UTM usada para calcular los valores x y y.
    *          si la zona es menor a 1 o mayor a 60 determinara un valor apropiado
    *          para la longitud
    *
    * Salidas:
    *   xy - Un arreglo de 2 elementos x y y UTM.
    *
    * Regresa:
    *   la zona UTM usada para los calculos.
    *
    */
function LatLonToUTMXY (lat, lon, zone, xy)
{
    MapLatLonToXY (lat, lon, UTMCentralMeridian (zone), xy);

    /* Ajustar el falso norte y este. */
    xy[0] = xy[0] * UTMScaleFactor + 500000.0;
    xy[1] = xy[1] * UTMScaleFactor;
    if (xy[1] < 0.0)
        xy[1] = xy[1] + 10000000.0;

    return zone;
}
    
    
    
/*
    * UTMXYToLatLon
    *
    * convierte coordenadas x y y UTM a geografica decimal
    * 
    *
    * Entrada:
    *	x - coordenada UTM x en metros.
    *	y - coordenada UTM y en metros.
    *	zone - zona de la proyeccion.
    *	southhemi - verdadero si esta en el hemisferio sur;
    *               falso si no lo esta.
    *
    * Salida:
    *	latlon - Un arreglo de 2 elementos conteniendo latitud y longitud en radianes
    *            
    *
    * Regresa:
    *	La funcioni no regresa ningun valor.
    *
    */
function UTMXYToLatLon (x, y, zone, southhemi, latlon)
{
    var cmeridian;
        	
    x -= 500000.0;
    x /= UTMScaleFactor;
        	
    /* si esta en el hemisferio sur ajustar con un factor de escala */
    if (southhemi)
        y -= 10000000.0;
        		
    y /= UTMScaleFactor;
        
    cmeridian = UTMCentralMeridian (zone);
    MapXYToLatLon (x, y, cmeridian, latlon);
        	
    return;
}
    