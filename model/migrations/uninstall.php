<?php
// See https://github.com/modxcms/revolution/issues/829
if ($Setting = $modx->getObject('modSystemSetting',array('key' => 'extension_packages'))) {
    $modx->removeExtensionPackage($object['namespace']);
}