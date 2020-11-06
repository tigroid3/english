<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "test_items".
 *
 * @property int $id
 * @property int $test_id
 * @property int $phrase_id
 * @property string $answer
 * @property int $status
 * @property int $type
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Test $test
 * @property Vocabulary $phrase
 */
class TestItem extends \yii\db\ActiveRecord
{
    public const STATUS_NOT_ANSWERED = 0;
    public const STATUS_RIGHT = 1;
    public const STATUS_WRONG = 2;

    public const TYPE_PHRASE = 0;
    public const TYPE_TRANSLATE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_items';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);


    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_id', 'phrase_id', 'answer'], 'required'],
            [['test_id', 'phrase_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['test_id', 'phrase_id', 'status', 'created_at', 'updated_at', 'type'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_NOT_ANSWERED],
            [['answer'], 'string', 'max' => 255],
            [
                ['test_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Test::class,
                'targetAttribute' => ['test_id' => 'id']
            ],
            [
                ['phrase_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Vocabulary::class,
                'targetAttribute' => ['phrase_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_id' => 'Test ID',
            'phrase_id' => 'Phrase ID',
            'answer' => 'Answer',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * Gets query for [[Phrase]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhrase()
    {
        return $this->hasOne(Vocabulary::class, ['id' => 'phrase_id']);
    }
}
