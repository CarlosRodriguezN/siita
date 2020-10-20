<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );

// load tooltip behavior
JHtml::_( 'behavior.tooltip' );
?>

<div id="content-box">
    <div id="element-box">
        <div id="system-message-container"></div>
        <div class="m">
            <div class="adminform">
                <div class="cpanel-page">
                    <div class="cpanel">
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="/index.php?option=com_proyectos&amp;view=proyectos">
                                    <img src="/administrator/templates/bluestork/images/header/icon-48-article-add.png" alt="">
                                    <span> <?php echo JText::_( 'COM_PROYECTOS_FORMULACION' ); ?> </span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="/index.php?option=com_proyectos&amp;view=proyectos">
                                    <img src="/administrator/templates/bluestork/images/header/icon-48-article-add.png" alt="">
                                    <span> <?php echo JText::_( 'COM_PROYECTOS_SEGUIMIENTO' ); ?> </span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="/index.php?option=com_proyectos&amp;view=proyectos">
                                    <img src="/administrator/templates/bluestork/images/header/icon-48-article-add.png" alt="">
                                    <span> <?php echo JText::_( 'COM_PROYECTOS_EVALUACION' ); ?> </span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="/index.php?option=com_proyectos&amp;view=reportesindicadores">
                                    <img src="/administrator/templates/bluestork/images/header/icon-48-article-add.png" alt="">
                                    <span> <?php echo JText::_( 'COM_PROYECTOS_INDICADORES' ); ?> </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

