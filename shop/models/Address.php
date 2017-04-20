<?php

namespace app\models;

use yii\db\ActiveRecord;
/**
 * 在此发现一个问题
 * 数据无法保存 首先 查看MODELS 中的rules方法
 * 查看检验规则 然后一般问题在于此处！
 * 2017年4月20日 11:13:02
 */
class Address extends ActiveRecord
{
    public function rules()
    {
        return [
            // [['firstname', 'lastname', 'company', 'address', 'postcode','email','telephone','userid','createtime'],'required'],
             [[ 'firstname', 'lastname', 'company','email', 'telephone'], 'required'],
            [['createtime', 'postcode','userid','address'],'safe'],
        ];
    }

    public static function tableName()
    {
        return "{{%address}}";
    }

    public function add($data)
    {
        if ($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }



}
