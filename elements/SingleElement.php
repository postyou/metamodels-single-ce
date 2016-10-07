<?php

namespace MetaModels\FrontendIntegration\Content;

use MetaModels\Factory;
use MetaModels\MetaModel;

class SingleElement extends \ContentElement {

        protected $strTemplate = 'ce_singleElement';

        protected $metaModel=null;
        protected $renderSettings=null;

	public function generate() {
            //Backend Ausgabe
            if (TL_MODE == 'BE')
            {
                $objTemplate = new \BackendTemplate('be_wildcard');
                $objTemplate->wildcard = '### '.utf8_strtoupper("Content Custom Element").' ###';
                $objTemplate->title = $this->headline;
                $objTemplate->id = $this->id;
                $objTemplate->link = $this->name;
                $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
    
                return $objTemplate->parse();
            }
    
            $serviceCont=Factory::getDefaultFactory()->getServiceContainer();
            $factory=$serviceCont->getFactory();
    
            if(isset($this->article) && !empty($this->article)) {
                $this->metaModel      = $factory->getMetaModel($factory->translateIdToMetaModelName($this->article));
                $this->renderSettings = $serviceCont->getRenderSettingFactory()->createCollection($this->metaModel);
                $this->strTemplate=$this->renderSettings->get("template");
            }
	    return parent::generate();
	}


	protected function compile() {
	    if(isset($this->renderSettings) && isset($this->metaModel) && isset($this->module) && !empty($this->module)) {
                $item                     = $this->metaModel->findById($this->module);
                $out                      = $item->parseValue($this->renderSettings->get("format"), $this->renderSettings);
                $out['class']             = "";
                $this->Template->data     = array($out);
                $this->Template->settings = $this->renderSettings;
            }

	}

}
