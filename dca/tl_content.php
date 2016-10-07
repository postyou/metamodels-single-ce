<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['metamodel_single_element'] =
    '{type_legend},type;{mm_legend},metaModelID,elementID;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
		



$GLOBALS['TL_DCA']['tl_content']['fields']['metaModelID']=array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['metaModelID'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'eval'                    => array('doNotSaveEmpty'=>true,'includeBlankOption'=>true,'submitOnChange' => true,'tl_class'=>'w50',),
        'foreignKey'              => 'tl_metamodel.name',
        'relation'                => array('type'=>'hasMany', 'load'=>'lazy'),
        'load_callback'=>array(array('tl_content_mm_single','loadCallBackMetaModelID')),
        'save_callback'=>array(array('tl_content_mm_single','saveCallBackMetaModelID'))
);

$GLOBALS['TL_DCA']['tl_content']['fields']['elementID']=array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['elementID'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'eval'                    => array('doNotSaveEmpty'=>true,'includeBlankOption'=>true,'tl_class'=>'w50'),
        'options_callback'        => array('tl_content_mm_single', 'getMetaModels4Optns'),
        'load_callback'=>array(array('tl_content_mm_single','loadCallBackElementID')),
        'save_callback'=>array(array('tl_content_mm_single','saveCallBackElementID'))
);

class tl_content_mm_single extends \Backend
{

    public function getMetaModels4Optns($dc){
        $rtnArr=array();
        if($dc->activeRecord!=null && !empty($dc->activeRecord->article)){
            $dbRes=$this->Database->prepare("SELECT tl_metamodel.tableName as tableName,
                                                    tl_metamodel_attribute.colname as colname
                                             FROM   tl_metamodel,
                                                    tl_metamodel_attribute 
                                             WHERE  tl_metamodel.id=? AND 
                                                    tl_metamodel_attribute.pid=tl_metamodel.id AND
                                                    tl_metamodel_attribute.identifierCol='1' 
                                             ")->execute($dc->activeRecord->article);
            if($dbRes->count($dbRes)){
                $dbRes2=$dc->Database->execute("SELECT id,".$dbRes->colname." FROM ".$dbRes->tableName)->fetchAllAssoc();
                if($dbRes2){
                    foreach ($dbRes2 as $row){
                        $rtnArr[$row['id']]=$row[$dbRes->colname];
                    }
                }
            }
        }

        return $rtnArr;
    }

    public function loadCallBackMetaModelID($varValue, $dc)
    {
        $res = $dc->Database->prepare("Select article from tl_content where id=?")->execute($dc->id)->fetchAssoc();
        return $res['article'];
    }

    public function saveCallBackMetaModelID($varValue,$dc)
    {
        $res = $dc->Database->prepare("Update tl_content set article=? where id=?")->execute($varValue, $dc->id);
        return null;
    }

    public function loadCallBackElementID($varValue, $dc)
    {
        $res = $dc->Database->prepare("Select module from tl_content where id=?")->execute($dc->id)->fetchAssoc();
        return $res['module'];
    }

    public function saveCallBackElementID($varValue,$dc)
    {
        $res = $dc->Database->prepare("Update tl_content set module=? where id=?")->execute($varValue, $dc->id);
        return null;
    }
}