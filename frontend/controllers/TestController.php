<?php

namespace frontend\controllers;

use common\models\Test;
use common\models\TestItem;
use common\models\Vocabulary;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class TestController
 * @package frontend\controllers
 */
class TestController extends Controller
{
    /**
     * Lists all Vocabulary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Test::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'defaultPageSize' => 30,
            ]
        ]);

        $queryAnswers = TestItem::find()
            ->select(['test_id', 'status', 'cnt' => new Expression('count(*)')])
            ->where(['in', 'test_id', ArrayHelper::getColumn($dataProvider->getModels(), 'id')])
            ->groupBy(['test_id', 'status'])
            ->asArray()
            ->all();

        $testAnswers = [];
        array_walk($queryAnswers, function ($v) use (&$testAnswers) {
            if (!isset($testAnswers[$v['test_id']])) {
                $testAnswers[$v['test_id']] = [
                    $v['status'] => $v['cnt']
                ];
            } else {
                $testAnswers[$v['test_id']][$v['status']] = $v['cnt'];
            }
        });


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'testAnswers' => $testAnswers,
        ]);
    }

    /**
     * Lists all Vocabulary models.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $test = Test::find()
            ->with(['notAnsweredTestItems'])
            ->where(['id' => $id])
            ->one();

        if ($test === null) {
            throw new NotFoundHttpException('Test №' . $id . ' not found');
        }

        $query = TestItem::find()
            ->with(['phrase'])
            ->where(['test_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'defaultPageSize' => 100,
            ]
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $test,
        ]);
    }

    public function actionRun()
    {
        $test = Test::find()
            ->where(['status' => Test::STATUS_ACTIVE])
            ->one();

        if ($test === null) {
            $test = new Test();

            if (!$test->save()) {
                throw new Exception('Test not saved', $test->getErrors());
            }

            $limit = Test::LIMIT_ITEMS_IN_TEST;
            $phrases = Vocabulary::find()
                ->select('id')->orderBy('random()')
                ->limit($limit)
                ->column();
            $tx = \Yii::$app->db->beginTransaction();

            for ($i = 0; $i < $limit; $i++) {
                $testItem = new TestItem([
                    'phrase_id' => $phrases[$i],
                    'test_id' => $test->id,
                    'type' => mt_rand(0, 1),
                ]);

                if (!$testItem->save(true, ['phrase_id', 'test_id', 'type', 'status'])) {
                    throw new Exception('TestItem not saved', $testItem->getErrors());
                }
            }
            $tx->commit();
        }

        return $this->redirect(['test/view', 'id' => $test->id]);
    }

    public function actionSetPhrase()
    {
        $testItemId = \Yii::$app->request->post('testItemId');
        $phrase = \Yii::$app->request->post('phrase');

        $testItem = TestItem::find()
            ->joinWith(['test', 'phrase'])
            ->where([TestItem::tableName() . '.id' => $testItemId])
            ->one();

        $status = TestItem::STATUS_WRONG;

        if ($testItem->type === TestItem::TYPE_PHRASE && $phrase === $testItem->phrase->translate) {
            $status = TestItem::STATUS_RIGHT;
        }

        if ($testItem->type === TestItem::TYPE_TRANSLATE && $phrase === $testItem->phrase->word) {
            $status = TestItem::STATUS_RIGHT;
        }

        $testItem->status = $status;
        $testItem->answer = $phrase;

        $error = '';
        if (!$testItem->save()) {
            $error = current($testItem->getErrors())[0];
        }

        $this->checkCompleteTest($testItem->test_id);

        return $this->asJson([
            'error' => $error,
            'status' => $status,
            'answer' => $testItem->type === TestItem::TYPE_PHRASE ? $testItem->phrase->translate : $testItem->phrase->word,
        ]);
    }

    public function actionSetSuccess($id)
    {
        $testItem = TestItem::find()
            ->where([TestItem::tableName() . '.id' => $id])
            ->one();

        $testItem->status = TestItem::STATUS_RIGHT;
        $testItem->save();

        return 1;
    }

    public function actionResetAnswer($id)
    {
        $testItem = TestItem::find()
            ->where([TestItem::tableName() . '.id' => $id])
            ->one();

        $testItem->status = TestItem::STATUS_NOT_ANSWERED;
        $testItem->answer = '';
        $testItem->save(false);

        $this->checkCompleteTest($testItem->test_id);

        return 1;
    }

    public function actionComplete($id)
    {
        $test = Test::find()
            ->where(['id' => $id])
            ->one();

        if ($test === null) {
            throw new NotFoundHttpException('Test not found');
        }

        $existsNotAnswered = TestItem::find()
            ->where(['=', 'status', TestItem::STATUS_NOT_ANSWERED])
            ->exists();

        if ($existsNotAnswered) {
            throw new NotFoundHttpException('Exists not answered test items');
        }

        return $this->redirect('/test/index');
    }

    public function actionDelete($id)
    {
        $test = Test::find()
            ->where(['id' => $id])
            ->one();

        if ($test === null) {
            throw new NotFoundHttpException('Test not found');
        }

        TestItem::deleteAll(['test_id' => $test->id]);
        $test->delete();

        return $this->redirect('/test/index');
    }

    private function checkCompleteTest(int $testId): void
    {
        $test = Test::find()
            ->where(['id' => $testId])
            ->one();

        if (empty($test->notAnsweredTestItems)) {
            $test->status = Test::STATUS_COMPLETED;
            $test->save(true, ['status']);
        }
    }
}
