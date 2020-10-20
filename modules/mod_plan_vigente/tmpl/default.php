<?php
defined('_JEXEC') or die;

?>
<div id="pathEstrategico" >
    <?php if ( is_object($pei) ):?>
        <?php if ( $pei->color == 1 ):?>
            <span id="pathPei" style="color: #0B55C4; font-weight: bold;" > <?php echo $pei->descripcionPln;?> | </span>
        <?php else:?>
            <span id="pathPei" style="color: red; font-weight: bold;"> <?php echo $pei->descripcionPln;?> | </span>
        <?php endif;?>
    <?php else:?>
            <span id="pathPei" style="color: red; font-weight: bold;"> Plan no asignado | </span>
    <?php endif;?>

    <?php if ( is_object($pppp) ):?>
        <?php if ( $pppp->color == 1 ):?>
            <span id="pathPppp" style="color: #0B55C4; font-weight: bold;" > <?php echo $pppp->descripcionPln;?> | </span>
        <?php else:?>
            <span id="pathPppp" style="color: red; font-weight: bold;"> <?php echo $pppp->descripcionPln;?> | </span>
        <?php endif;?>
    <?php else:?>
            <span id="pathPppp" style="color: red; font-weight: bold;"> Plan no asignado | </span>
    <?php endif;?>

    <?php if ( is_object($papp) ):?>
        <?php if ( $papp->color == 1 ):?>
            <span id="pathPapp" style="color: #0B55C4; font-weight: bold;" > <?php echo $papp->descripcionPln;?> </span>
        <?php else:?>
            <span id="pathPapp" style="color: red; font-weight: bold;"> <?php echo $papp->descripcionPln;?> </span>
        <?php endif;?>
    <?php else:?>
            <span id="pathPapp" style="color: red; font-weight: bold;"> Plan no asignado </span>
    <?php endif;?>
</div>

<?php 
    if ( $componente == "com_unidadgestion" || $componente == "com_funcionarios" ){
        echo '<div id="pathOperativo" style="float:right;">';
        $spanOpPei = '';
        if ( is_object($pei) ) {
            $spanOpPei .= ($pei->color == 1) 
                            ? '<span id="pathPapp" style="color: #0B55C4; font-weight: bold;" > ' . $pei->descripcionPln . ' | </span>' 
                            : '<span id="pathPapp" style="color: red; font-weight: bold;"> ' . $pei->descripcionPln . ' | </span>' ;
        } else {
            $spanOpPei = '<span id="pathPapp" style="color: red; font-weight: bold;"> Plan no asignado | </span>';
        }
        echo $spanOpPei;

        $spanOpPoa = '';
        if ( is_object($poa) ){
            $spanOpPoa .= ($pei->color == 1) 
                            ? '<span id="pathPapp" style="color: #0B55C4; font-weight: bold;" > ' . $poa->descripcionPln . ' | </span>' 
                            : '<span id="pathPapp" style="color: red; font-weight: bold;"> ' . $poa->descripcionPln . ' | </span>' ;
        } else {
            $spanOpPoa .= '<span id="pathPapp" style="color: red; font-weight: bold;"> Plan no asignado </span>';
        }
        echo $spanOpPoa;
        echo '</div>';
    }
?>

