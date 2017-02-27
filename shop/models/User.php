<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 *
 */
class User extends ActiveRecord
{
    public $repass;
    public $rememberMe = true;
    public $loginname;
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
            'loginname' => '用户名/邮箱',

        ];

    }
    public function rules()
    {
        return [
            ['loginname', 'required', 'message' => '用户名不能为空', 'on' => ['login']],
            ['username', 'required', 'message' => '用户名不能为空', 'on' => ['reg', 'regbymail']],
            ['username', 'unique', 'message' => '该用户已经被注册!', 'on' => ['reg', 'regbymail']],
            ['useremail', 'required', 'message' => '邮箱不能为空', 'on' => ['reg']],
            ['useremail', 'email', 'message' => '邮箱格式不正确', 'on' => ['reg', 'regbymail']],
            ['useremail', 'unique', 'message' => '邮箱已经被注册', 'on' => ['reg', 'regbymail']],
            ['userpass', 'required', 'message' => '密码不能为空', 'on' => ['reg', 'regbymail']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['reg', 'qqreg']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致', 'on' => ['reg', 'qqreg']],
            ['userpass','validatePass','on'=>['login']],

        ];

    }
    /**
     * 密码验证方法
     * 2017年2月6日
     * @return [type] [description]
     */
    public function validatePass()
    {
        if (!$this->hasErrors()) {
           $loginname = "username";
           $data = self::find()->where($loginname.' = :loginname and userpass = :pass', [':loginname' => $this->loginname, ':pass' => md5($this->userpass)])->one();


           if (preg_match('/@/',$this->loginname)) {
               $loginname="useremail";
           
           //这个语句很重要 但是太陌生
            $data = self::find()->where($loginname.' = :loginname and userpass = :pass', [':loginname' => $this->useremail, ':pass' => md5($this->userpass)])->one();
            }
            
           if (is_null($data)) {
               $this->addError("userpass","用户名或者密码错误！");
           }
        }
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
            // $this->username   = $data['User']['username'];
            if ($this->save(false)) {
                return true;
            }
        }
        return false;
    }
    /**
     * 系统登录的方法
     * 2017年2月4日 12:39:08
     */
    public function login($data)
    {
        $this->scenario = 'login';
        if ($this->load($data) && $this->validate()) {
            $lifetime = $this->rememberMe ? 24*3600 :0 ;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['loginname'] = $this->loginname;
            $session['isLogin']   = 1;
            return (bool)$session['isLogin'];
        }
        return false;
    }
    /**
     * 退出登录
     * 2017年2月6日 22:25:30
     * @return [type] [description]
     */
    public function logout(){
        if (Yii::$app->session->remove('loginname')&&Yii::$app->session->remove('isLogin')) {
            return true;
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
        $data['User']['username'] = 'imooctest_' . uniqid();
        $data['User']['userpass'] = uniqid();
        $this->scenario           = 'regbymail';
        //建立账号
        if ($this->load($data) && $this->validate()) {
            $mailer = Yii::$app->mailer->compose('regbymail', ['userpass' => $data['User']['userpass'], 'username' => $data['User']['username']]);
            $mailer->setSubject('新用户账号');
            $mailer->setTo($data['User']['useremail']);
            // $mailer->setTo('15230267260@163.com');
            $mailer->setFrom('computerobot@163.com');
            if ($mailer->send() && $this->reg($data, 'regbymail')) {
                return true;
            }

        }
        return false;

    }
}
