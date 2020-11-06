<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tests".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property TestItem[] $testItems
 * @property TestItem[] $notAnsweredTestItems
 */
class Test extends \yii\db\ActiveRecord
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_COMPLETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tests';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[TestItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestItems()
    {
        return $this->hasMany(TestItem::class, ['test_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotAnsweredTestItems()
    {
        return $this->hasMany(TestItem::class, ['test_id' => 'id'])
            ->onCondition(['status' => TestItem::STATUS_NOT_ANSWERED]);
    }
}
