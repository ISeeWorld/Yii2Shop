<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 *
 */
class User extends ActiveRecord
{
    public $repass = '';
    public static function tableName()
    {
        return "{{%user}}";
    }
    public function attributeLabels()
    {
        return [
            'username'  => '用户名',
            'userpass'  => '密码',
            'repass'    => '确认密码',
            'useremail' => '用户邮箱',

        ];

    }
    public function rules()
    {
        return [
            ['loginname', 'required', 'message' => '用户名不能为空', 'on' => ['login']],
            ['username', 'required', 'message' => '用户名不能为空', 'on' => ['reg']],
            ['username', 'unique', 'message' => '该用户已经被注册!', 'on' => ['reg']],
            ['useremail', 'required', 'message' => '邮箱不能为空', 'on' => ['reg']],
            ['useremail', 'email', 'message' => '邮箱格式不正确', 'on' => ['reg']],
            ['userpass', 'required', 'message' => '密码不能为空', 'on' => ['reg']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['reg', 'qqreg']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致', 'on' => ['reg', 'qqreg']],

        ];

    }
    /**
     * 新用户注册的函数
     * 2017年1月29日 20:39:32
     */
    public function reg($data, $scenario = 'reg')
    {
        $this->scenario = $scenario;
        //首先进行数据加载进行验证数据是否正确
        //2017年1月29日 20:49:43
        //！！！！！$this->validate() 无参数！！
        if ($this->load($data) && $this->validate()) {
            $this->createtime = time();
            $this->userpass   = md5($this->userpass);
            if ($this->save(false)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    //两表联合查询  用法特殊需要记忆关注
    //2017年1月31日 23:17:10
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }
    // 将账号发送到邮箱
    // 2017年2月3日 21:51:32
    public function regByMail($data)
    {
        $this->scenario           = 'regbymail';
        $data['User']['username'] = 'imooctest_' . uniqid();
        $data['User']['userpass'] = uniqid();
        //建立账号
        if ($this->load($data) && $this->validate()) {
            $mailer = Yii::$app->mailer->compose('regbymail', ['userpass' => $data['User']['userpass'], 'username' => $data['User']['username']]);
            $mailer ->setSubject['新用户账号']；
            $mailer ->setTo($data['User']['useremail']);
            $mailer ->setFrom('computerobot@163.com');
            if ($mailer->send()&& $this->reg($data,'regbymail')) {
                return true;                
            }

        }
        return false;

    }
}
