<?php

use common\models\Test;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VocabularySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
            ['attribute' => 'created_at', 'format' => ['date', 'd-m-Y H:i:s']],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
