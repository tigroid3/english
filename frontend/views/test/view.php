<?php

use common\models\Test;
use common\models\TestItem;
use frontend\assets\TestAsset;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/** @var Test $model */
/** @var ActiveDataProvider $dataProvider */

TestAsset::register($this);

$this->title = 'Last tests';
$this->params['breadcrumbs'][] = ['url' => Url::toRoute('/test/index'), 'label' => 'List tests'];
$this->params['breadcrumbs'][] = 'Test #' . $model->id;
?>
<div class="vocabulary-index">
    <?php Pjax::begin([
        'id' => 'pjax-test-view',
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-bordered',
        ],
        'rowOptions' => function ($model, $key, $index) {
            if ($model->status === TestItem::STATUS_RIGHT) {
                return ['class' => 'bg-success'];
            } elseif ($model->status === TestItem::STATUS_WRONG) {
                return ['class' => 'bg-danger'];
            }
        },
        'columns' => [
            [
                'attribute' => 'id',
                'label' => 'Id'
            ],
            [
                'attribute' => 'phrase.word',
                'content' => function (TestItem $model) {
                    $question = $model->type === TestItem::TYPE_PHRASE ? $model->phrase->word : $model->phrase->translate;

                    return $question;
                }
            ],
            [
                'attribute' => 'answer',
                'content' => function (TestItem $model) {
                    $options = [
                        'class' => 'answer-field',
                        'data-answer-id' => $model->id,
                    ];

                    $sendBtn = '';

                    if (!empty($model->answer)) {
                        $options['disabled'] = true;
                    } else {
                        $sendBtn = Html::a('Send', '#', [
                            'class' => 'send-answer btn btn-info btn-xs'
                        ]);
                    }

                    return Html::textInput('answer-' . $model->id, $model->answer, $options) . '  ' . $sendBtn;
                }
            ],
            [
                'label' => 'Correct answer',
                'content' => function (TestItem $model) {
                    if ($model->status !== TestItem::STATUS_NOT_ANSWERED) {
                        $answer = $question = $model->type === TestItem::TYPE_PHRASE ?
                            $model->phrase->translate :
                            $model->phrase->word;

                        return $answer;
                    }
                }
            ],
            [
                'attribute' => 'status',
                'content' => function (TestItem $model) {
                    switch ($model->status) {
                        case TestItem::STATUS_NOT_ANSWERED:
                            $status = '';
                            break;
                        case TestItem::STATUS_RIGHT:
                            $status = '+';
                            break;
                        case TestItem::STATUS_WRONG:
                            $status = '—';
                            break;
                        default:
                            $status = '';
                    }

                    return $status;
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{setSuccess} {reset}',
                'visible' => $model->status === Test::STATUS_ACTIVE,
                'buttons' => [
                    'setSuccess' => function ($url, $model, $key) {
                        return Html::a('', ['/test/set-success', 'id' => $model->id], [
                            'class' => 'set-success-test-item-button glyphicon glyphicon-ok',
                            'data-pjax' => 0,
                        ]);
                    },
                    'reset' => function ($url, $model, $key) {
                        return Html::a('', ['/test/reset-answer', 'id' => $model->id], [
                            'class' => 'reset-test-item-button glyphicon glyphicon-repeat',
                            'data-pjax' => 0,
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php
    $options = [
        'class' => 'btn btn-success',
    ];

    if (!empty($model->notAnsweredTestItems)) {
        $options += [
            'disabled' => 'disabled',
            'style' => "pointer-events: none",
        ];
    }
    ?>
    <?php if ($model->status === Test::STATUS_ACTIVE): ?>
        <div class="row">
            <?= Html::a('Complete test', ['/test/complete', 'id' => $model->id], $options) ?>
            <?= Html::a('Delete test', ['/test/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => "Are you sure you want to delete this item?"
            ]) ?>
        </div>
    <?php endif; ?>
    <?php Pjax::end() ?>
</div>
