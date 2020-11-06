<?php

use common\models\Test;
use common\models\TestItem;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VocabularySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var array $testAnswers */

$this->title = 'Last tests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'label' => '№'
            ],
            [
                'attribute' => 'status',
                'content' => function (Test $model) {
                    switch ($model->status) {
                        case Test::STATUS_ACTIVE:
                            $status = 'Active';
                            break;
                        case Test::STATUS_COMPLETED:
                            $status = 'Completed';
                            break;
                        default:
                            $status = 'Error';
                    }

                    return $status;
                }
            ],
            [
                'label' => 'Right',
                'content' => function ($model) use ($testAnswers) {
                    if (!isset($testAnswers[$model->id][TestItem::STATUS_RIGHT])) {
                        return "0/" . Test::LIMIT_ITEMS_IN_TEST;
                    }

                    return $testAnswers[$model->id][TestItem::STATUS_RIGHT] . "/" . Test::LIMIT_ITEMS_IN_TEST;
                }
            ],
            [
                'label' => 'Wrong',
                'content' => function ($model) use ($testAnswers) {
                    if (!isset($testAnswers[$model->id][TestItem::STATUS_WRONG])) {
                        return "0/" . Test::LIMIT_ITEMS_IN_TEST;
                    }

                    return $testAnswers[$model->id][TestItem::STATUS_WRONG] . "/" . Test::LIMIT_ITEMS_IN_TEST;
                }
            ],

            [
                'label' => 'Wait',
                'content' => function ($model) use ($testAnswers) {
                    if (!isset($testAnswers[$model->id][TestItem::STATUS_NOT_ANSWERED])) {
                        return "0/" . Test::LIMIT_ITEMS_IN_TEST;
                    }

                    return $testAnswers[$model->id][TestItem::STATUS_NOT_ANSWERED] . "/" . Test::LIMIT_ITEMS_IN_TEST;
                }
            ],
            ['attribute' => 'created_at', 'format' => ['date', 'php:d-m-Y H:i']],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
