<?php
namespace app\modules\models;

use Yii;
use yii\db\ActiveRecord;

class Admin extends ActiveRecord
{
    public $RemberMe = true;
    public $RePass;
    /**
     * 返回数据库名称
     * @return [type] [description]
     */
    public static function tableName()
    {
        return "{{%admin}}";
        //bug %书写
    }
    /**
     * 汉语名称的配置
     * 2016年10月8日 10:13:59
     * @return [type] [description]
     */
    public function attributeLabels()
    {
        return [
            'adminuser'  => "管理员名称",
            'adminemail' => "管理员邮箱",
            'adminpass'  => "管理员密码",
            'RePass'     => "确认密码",
        ];

    }
    /**
     * 默认的规则 用于验证填写数据的规范性
     * @return [type] [description]
     */
    public function rules()
    {
        return [
            ['adminuser', 'required', 'message' => '管理员账号不能为空', 'on' => ['login', 'seekpass', 'adminadd']],
            ['adminpass', 'required', 'message' => '管理员密码不能为空', 'on' => ['login', 'changepass', 'adminadd', 'changeemail']],
            ['RemberMe', 'boolean', 'on' => 'login'],
            ['adminpass', 'validatePass', 'on' => ['login', 'changeemail']],
            ['adminemail', 'required', 'message' => '电子邮件不能为空', 'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['adminemail', 'email', 'message' => '电子邮箱格式不正确', 'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['adminemail', 'unique', 'message' => '电子邮箱已经被注册', 'on' => ['changeemail', 'adminadd']],
            ['adminuser', 'unique', 'message' => '管理员用户已经被注册', 'on' => 'adminadd'],
            ['adminemail', 'validateEmail', 'on' => 'seekpass'],
            ['RePass', 'required', 'message' => '密码不能为空', 'on' => ['changepass', 'adminadd']],
            ['RePass', 'compare', 'compareAttribute' => 'adminpass', 'message' => '两次密码输入不一致', 'on' => ['changepass', 'adminadd']],
        ];
    }
    /**
     * 自定义的验证规则函数 验证密码
     * @return [type] [description]
     */
    public function validatePass()
    {

        if (!$this->hasErrors()) {
            $data = self::find()->where('adminuser = :user and adminpass = :pass', [':user' => $this->adminuser, ':pass' => md5($this->adminpass)])->one();
            if (is_null($data)) {
                $this->addError("adminpass", "用户名或者密码错误");
            }
        }

    }
    /**
     * 邮箱验证
     * @return [type] [description]
     */
    public function validateEmail()
    {
        if (!$this->hasErrors()) {
            $data = self::find()->where('adminuser = :user and adminemail = :email', [':user' => $this->adminuser, ':email' => $this->adminemail])->one();
            if (is_null($data)) {
                $this->addError("adminemail", "管理员邮箱不匹配");
                //bug addError haserrors区别
            }
        }
    }
    /**
     * 关键的登陆函数
     * Load方法和validate方法 进行数据加载和验证 属于类的自己的验证方法
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function login($data)
    {
        $this->scenario = 'login';
        if ($this->load($data) && $this->validate()) {
            $lifetime = $this->RemberMe ? 24 * 3600 : 0;
            $session  = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['admin'] = [
                'adminuser' => $this->adminuser,
                'isLogin'   => 1,
            ];
            // 数据更新操作
            $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)], 'adminuser = :user', [':user' => $this->adminuser]);
            return (bool) $session['admin']['isLogin'];
        }
        return false;
    }
    /**
     * 找回密码
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function seekPass($data)
    {
        $this->scenario = 'seekpass';
        $time           = time();
        if ($this->load($data) && $this->validate()) {
            $mail = Yii::$app->mailer->compose('seekpass', ['adminuser' => $this->adminuser, 'time' => $time,
                'token'                                                     => $this->createToken($time, $this->adminuser)]);
            $mail->setFrom('computerobot@163.com');
            $mail->setTo($this->adminemail);
            $mail->setSubject('修改密码');
            if ($mail->send()) {
                return true;
            };
        }
        return false;
    }
    /**
     * 创建TOKEN
     * @param  [type] $time [description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function createToken($time, $user)
    {
        return md5($time . $user);
    }
    /**
     * 修改密码
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function changePass($data)
    {
        $this->scenario = 'changepass';
        if ($this->load($data) && $this->validate()) {
            return (bool) $this->updateAll(['adminpass' => md5($this->adminpass)], 'adminuser = :user', [':user' => $this->adminuser]);
            //语句太难写十分晦涩 2016年10月6日 17:28:23

        }
        return false;
    }
    /**
     * 添加管理员用户
     * 2016年10月7日 22:29:58
     * @return [type] [description]
     */
    public function adminAdd($data)
    {
        $this->scenario = 'adminadd';
        if ($this->load($data) && $this->validate()) {
            $this->adminpass = md5($this->adminpass);
            if ($this->save(false)) {
                // 数据保存的操作比较隐晦要仔细理解
                return true;
                //数据不进行验证
            }
            return false;
        }
        return false;
    }

    /**
     * [changeEmail description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function changeEmail($data)
    {
        $this->scenario = 'changeemail';
        if ($this->load($data) && $this->validate()) {
            // return true;
            return (bool) $this->updateAll(['adminemail' => $this->adminemail], 'adminuser = :user', [':user' => $this->adminuser]);
        }
        return false;
    }

}
