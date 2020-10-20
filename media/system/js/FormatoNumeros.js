function formatNumber( num, prefix ){
    prefix = prefix || '';
    num = num || 0;
    num = parseFloat(num).toFixed(2);
    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = ( splitStr.length > 1 )? '.' + splitStr[1]
                                            : '';
    var regx = /(\d+)(\d{3})/;
    
    while( regx.test( splitLeft ) ){
        splitLeft = splitLeft.replace( regx, '$1' + ',' + '$2' );
    }

    return prefix + ' ' +splitLeft + splitRight;
}

function unformatNumber(num) {
    
    var number;
    
    if( typeOf( num ) === "number" ){
        number = num;
    }else{
        number = num.replace(/([^0-9\.\-])/g, '') * 1;
    }
    
    return number;
}