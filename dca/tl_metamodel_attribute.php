<?php

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['default']['title'][] = 'identifierCol';


$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['identifierCol']= array
(
    'label'     => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['identifierCol'],
    'inputType' => 'checkbox',
    'eval'      => array
    (
        'tl_class' => 'cbx clr'
    ),
);
