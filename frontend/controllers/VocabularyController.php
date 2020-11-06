<?php

namespace frontend\controllers;

use common\models\search\VocabularySearch;
use common\models\Vocabulary;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * VocabularyController implements the CRUD actions for Vocabulary model.
 */
class VocabularyController extends Controller
{
    /**
     * Lists all Vocabulary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VocabularySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Vocabulary();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect('/');
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Vocabulary();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }

        return $this->redirect(['site/index']);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Vocabulary::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
