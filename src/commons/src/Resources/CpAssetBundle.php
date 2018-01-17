<?php

namespace Solspace\Commons\Resources;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

abstract class CpAssetBundle extends AssetBundle
{
    /**
     * Initialize the source path and scripts
     */
    final public function init()
    {
        $this->sourcePath = $this->getSourcePath();
        $this->depends    = [CpAsset::class];

        $this->js  = $this->getScripts();
        $this->css = $this->getStylesheets();

        parent::init();
    }

    /**
     * Return the source path of this resource
     *
     * E.g. @Solspace/Calendar/Resources
     *      @Solspace/Freeform/Resources
     *      etc
     *
     * @return string
     */
    abstract protected function getSourcePath(): string;

    /**
     * @return array
     */
    public function getScripts(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getStylesheets(): array
    {
        return [];
    }
}
