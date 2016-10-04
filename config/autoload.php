<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'MetaModels',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'MetaModels\FrontendIntegration\Content\SingleElement' => 'system/modules/metamodels_single_ce/elements/SingleElement.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_singleElement' => 'system/modules/metamodels_single_ce/templates',
));
