<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class VerbsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/verbs.js',
    ];
    public $depends = [
        AppAsset::class
    ];
}
