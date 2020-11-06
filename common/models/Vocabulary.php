<?php

namespace common\models;

/**
 * This is the model class for table "vocabulary".
 *
 * @property int $id
 * @property string $word
 * @property string $translate
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Vocabulary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word', 'translate'], 'required'],
            [['word'], 'unique', 'targetAttribute' => ['word', 'translate']],
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['word', 'translate'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word' => 'Word',
            'translate' => 'Translate',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
