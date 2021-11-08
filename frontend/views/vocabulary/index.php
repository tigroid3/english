<?php

use common\models\Vocabulary;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VocabularySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var Vocabulary $model */

$this->title = 'Vocabularies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-index">
    <?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'word')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'translate')->textInput(['maxlength' => true]) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton('add', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'word',
            'translate',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d-m-Y H:i']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d-m-Y H:i']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
