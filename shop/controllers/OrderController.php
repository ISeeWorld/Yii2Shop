<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use app\models\Category;
use app\models\Order;
use app\models\Product;
use app\models\Orderdetail;
use Yii;

class OrderController extends CommonController
{
    // public $layout = false;
    
    public function actionCheck()
    {
        $this->layout = "layout1";
        return $this->render('check');
    }
    public function actionIndex()
    {
        $this->layout = "layout2";
        return $this->render('index');
    }
    /**
     * 用户添加操作
     * 2017年3月4日 16:02:49
     * @return [type] [description]
     */
    public function actionAdd()
    {
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }
        //开始事务处理 防止回滚
        //2017年3月4日 16:22:13
        $transaction = Yii::$app->db->beginTransaction();
        try{
           if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->post();
            $ordermodel = new Order;
            $ordermodel->scenario = 'add';
            $username = Yii::$app->session['loginname'];
            // echo 'username:'.$username.'<br>';
            $usermodel= User::find()->where('username= :name or useremail = :email',[':name'=>$username,':email'=>$username])->one();

            if (!$usermodel) {
               throw new \Exception("无此用户");
               // echo 'user bad';        
            }
            $userid = $usermodel->userid;
            $ordermodel->userid = $userid;
            $ordermodel->status =  Order::CREATEORDER;
            $ordermodel->createtime = time();
            // 保存数据
            if(!$ordermodel->save()){
                throw new \Exception("订单保存错误");
            }
            // 新用法 获取用户的主键值
            $orderid = $ordermodel->getPrimaryKey();
            //数组遍历 如何进行 
            //2017年3月4日 18:08:38
            //数组知识必须补充
            foreach ($postdata['OrderDetail'] as $pro) {
               $detailmodel = new Orderdetail;
               $pro['orderid'] = $orderid;
               $pro['createtime'] = time();
               $data['OrderDetail'] = $pro;
               if (!$detailmodel->add($data)) {
                 throw new \Exception("detail error!");
               }
               Product::updateAllCounters(['num' => +$pro['productnum']], 'productid = :pid', [':pid' => $pro['productid']]);
           }
           }
           //注意检查数据插入的类型  调试尝试注意去掉 TRY CATCH语句 
           //防止错误被包裹 无法查处
           //2017年4月19日 13:46:08
           $transaction->commit();
           // 提交数据
        }
        catch(\Exception $e){
            $transaction->rollback();
            // 回滚
            $this->redirect(['cart/index']);
            // echo "rollback!";
        }

       return $this->redirect(['order/check', 'orderid' => $orderid]);
    }

    
}

   
/**
 * BUG 修复记录 
 * 出现故障首先考虑 系统结构流程 系统把握 分解过程之后分析
 * 视图出现问题很难查找 前端技术薄弱
 * 学会使用r=debug 工具
 * 2017年3月5日 21:51:47
 * _csrf很奇怪
 * 需要好好研究
 * 1、学会R=DEBUG
 * 2、定时清除缓存 F9
 * 3、写代码前先写好思路 理清细节 写完汉字代码  在开始写CODE 
 * 并且测试要有成熟的思路 办法 工具 不要乱猜想 一个环节一个环节进行测试
 * 4、前端视图多出问题 且隐蔽 以后重点注意 不要放松
 * 
 *<?php $form = ActiveForm::begin([
            'action' => yii\helpers\Url::to(['order/add']),
        ]) ?>
        <!-- ========================================= CONTENT ========================================= -->
        <div class="col-xs-12 col-md-9 items-holder no-margin">
        <?php $total=0; $pnum=0 ;  ?>
            <?php foreach ($data as $k => $v) : ?>
            <input type="hidden" name="OrderDetail[<?php echo $k?>][productid]" value="<?php echo $v['productid'] ?>">
            <input type="hidden" name="OrderDetail[<?php echo $k?>][price]" value="<?php echo $v['price'] ?>">
            <input type="hidden" name="OrderDetail[<?php echo $k?>][productnum]" value="<?php echo $v['productnum'] ?>">
            <div class="row no-margin cart-item">
 */