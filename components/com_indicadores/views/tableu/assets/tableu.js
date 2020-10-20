jQuery( document ).ready( function(){
    Joomla.submitbutton = function( task )
    {
        if( task === 'tableu.cancel' ){
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})