<?php

namespace frontend\controllers;

use Symfony\Component\DomCrawler\Crawler;
use yii\web\Controller;

class VerbsController extends Controller
{
    public function actionIndex()
    {
        $crawler = new Crawler(file_get_contents(\Yii::getAlias('@common/uploads') . '/verbs-html.txt'));
        $lines = $crawler->filter('table tbody tr');

        $dataVerbs = $lines->each(function (Crawler $node) {
            return [
                $node->filter('td:nth-child(1)')->text(),
                $node->filter('td:nth-child(2)')->text(),
                $node->filter('td:nth-child(3)')->text(),
                $node->filter('td:nth-child(4)')->text(),
            ];
        });

        return $this->render('index', [
            'dataVerbs' => $dataVerbs,
        ]);
    }
}
