<?php

$path = $modx->getOption('lunchbox.core_path','',MODX_CORE_PATH.'components/lunchbox/');

// Add the package to the MODX extension_packages array
$modx->addExtensionPackage('lunchbox',$path.'model/');
$modx->addPackage('lunchbox',$path.'model/');
