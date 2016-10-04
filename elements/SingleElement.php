<?php

namespace MetaModels\FrontendIntegration\Content;

use MetaModels\Factory;
use MetaModels\MetaModel;

class SingleElement extends \ContentElement {

	protected $strTemplate = 'ce_singleElement';

    protected $metaModel=null;
    protected $renderSettings=null;

	public function __construct($objModule, $strColumn = 'main') {
		parent:: __construct($objModule, $strColumn);
	}
    
    public function setScripts(){
        $GLOBALS['TL_JAVASCRIPT']['venobox'] = '/composer/vendor/nicolafranchini/venobox/venobox/venobox.min.js';
        $GLOBALS["TL_CSS"][]='composer/vendor/nicolafranchini/venobox/venobox/venobox.css';
        $GLOBALS['TL_BODY'][]="<script>
            $(document).ready(function(){
                 $('.venobox').venobox(); 
                 var venoBoxOpenParam=getQueryParams('video_id');
                 if(venoBoxOpenParam!=''){
                    $('#'+venoBoxOpenParam+'.venobox').trigger('click'); 
                 }
                 
            });
                 function getQueryParams(qs) {
                var urlString = document.location.search
                urlString = urlString.split('+').join(' ');
             
                var params = {},
                    tokens,
                    re = /[?&]?([^=]+)=([^&]*)/g;
             
                while (tokens = re.exec(urlString)) {
                    params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
                }
             
                if(qs!=undefined){
                    if(params.hasOwnProperty(qs) && params[qs]!=undefined)
                        return params[qs];
                        else
                        return '';
                }
             
                return params;
            }
            </script>";
    }

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
        $this->setScripts();

	    if(isset($this->renderSettings) && isset($this->metaModel) && isset($this->module) && !empty($this->module)) {
            $item                     = $this->metaModel->findById($this->module);
            $out                      = $item->parseValue($this->renderSettings->get("format"), $this->renderSettings);
            $out['class']             = "";
            $this->Template->data     = array($out);
            $this->Template->settings = $this->renderSettings;
        }

	}

}
