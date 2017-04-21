<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use app\models\Category;
use app\models\Order;
use app\models\Product;
use app\models\Orderdetail;
use app\models\Address;
use Yii;

// 搞清楚2个问题
// 1.数据库数据交互的细节 看图 同时搞清楚MVC的细节
// 2.数组的遍历循环等具体环节问题
class OrderController extends CommonController
{
    // public $layout = false;
    
    public function actionCheck()
    {
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }
        // 判明是否登录
        $orderid = Yii::$app->request->get('orderid');
        // $status = Order::find()->where('userid = :id',['id'=>$orderid])->one()->status;
        $status = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one()->status;
        if ($status != Order::CREATEORDER && $status != Order::CHECKORDER) {
            return $this->redirect(['order/index']);
        }
        // 获取订单ID
        $username = Yii::$app->session['loginname'];
        $usermodel= User::find()->where('username= :name or useremail = :email',[':name'=>$username,':email'=>$username])->one();
        if (!$usermodel) {
               throw new \Exception("无此用户");
               // echo 'user bad';        
        }
        $userid = $usermodel->userid;
        //得到用户ID
        $addresses = Address::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
        //获取用户地址
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();
        // 获取订单详情
        $data = [];
        foreach($details as $detail) {
            $model = Product::find()->where('productid = :pid' , [':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }
        // 遍历保存数据
        $express = Yii::$app->params['express'];
        $expressPrice = Yii::$app->params['expressPrice'];
        // 获取数组情况
        $this->layout = "layout1";
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
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
    /**
     * actionconfirm
     * 2017年4月20日 16:34:19
     * @return [type] [description]
     */
    public function actionConfirm()
    {
     
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }

        try {
        // 获取订单ID
        $username = Yii::$app->session['loginname'];
        $userid= User::find()->where('username= :name or useremail = :email',[':name'=>$username,':email'=>$username])->one()->userid;
        // $addressid = Address::find()->where('userid = :uid',[':uid']=>$userid)->getPrimaryKey();
         $addressmodel = new Address;
         $addressid = $addressmodel->getPrimaryKey();
        if (!$userid) {
               throw new \Exception("无此用户");
               // echo 'user bad';        
           }
        if (Yii::$app->request->ispost) {
                $post = Yii::$app->request->post();
                $orderid = $post['orderid'];
                $model = Order::find()->where('userid = :uid and orderid = :oid',[':uid'=>$userid,':oid'=>$orderid])->one();
                if (!$model) {
                    throw new Exception("订单错误！");              
                }
                $details = OrderDetail::find()->where('orderid = :oid',[':oid'=>$orderid])->all();
                $amount = 0;
                foreach ($details as $k) {
                    $amount += $k->productnum * $k->price;
                }
                if ($amount<=0) {
                    throw new Exception("价格不能小于零");    
                }
                $expressPrice = Yii::$app->params['expressPrice'][$post['expressid']];
                //参数问题 具体的参数如何出处理
                if ($expressPrice<0) {
                    throw new Exception("快递价格不能小于零"); 
                }

                $model->scenario = "update";
                $post['status'] = Order::CHECKORDER;
                $amount += $expressPrice;
                $post['amount'] = $amount;
                // $post['addressid'] = $addressid;
                $data['Order']  = $post;
                // var_dump($data);
                // echo $addressid;
                // if (empty($post['addressid'])) {
                // return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
                // }

                if ($model->load($data) && $model->save()) {
                    return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
                }else{
                    throw new \Exception("data save error!");                   
                }
            }else{
               throw new \Exception("提交方式不对！");
            }

        } catch (Exception $e) {
            return $this->redirect(['index/index']);  
        }  

        }

    /**
     * 阿里云支付宝问题
     * 2017年4月21日 22:27:18
     * @return [type] [description]
     */
      public function actionPay()
    {
        try{
            if (Yii::$app->session['isLogin'] != 1) {
                throw new \Exception();
            }
            $orderid = Yii::$app->request->get('orderid');
            $paymethod = Yii::$app->request->get('paymethod');
            if (empty($orderid) || empty($paymethod)) {
                throw new \Exception();
            }
            if ($paymethod == 'alipay') {
                return Pay::alipay($orderid);
            }
        }catch(\Exception $e) {}
        return $this->redirect(['order/index']);
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