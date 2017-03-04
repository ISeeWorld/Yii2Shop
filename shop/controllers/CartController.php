<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\Product;
use app\models\User;
use app\models\Cart;
use app\controllers\CommonController;
use Yii;
class CartController extends CommonController
{
    /**
     * 跳转到首页
     * 2017年2月28日 22:57:04
     * @return [type] [description]
     */
    public function actionIndex()
    {
     $this->layout = 'layout1';
     //判断用户是否登录
     if (Yii::$app->session['isLogin'] !=1 ) {
        $this->redirect(['member/auth']);
     }
     // 获取登录用户名称
     $name = Yii::$app->session['loginname'];
     $userid = User::find()->where('username = :name',[':name'=>$name])->one()->userid;
     // 根据用户名称获取购物车
     $cart = Cart::find()->where('userid = :id',[':id'=>$userid])->asArray()->all();
     //asArray()函数获取的数据是一个多维数组 注意判断处理 
     $data= [];
     // $k显示为 0 1
     foreach ($cart as $k => $pro) {
      // 查询到对应商品
          $product = Product::find()->where('productid = :pid',[':pid'=>$pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            // 购物车获取相关信息 2017年3月4日 11:04:18
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
     }
     // exit();
     return $this->render('index',['data'=>$data]);

    }
    /**
     * 购物车添加操作
     * 2017年2月26日 21:30:02
     * @return [type] [description]
     */
    public function actionAdd()
    {
        //判断是否登录用户
       if (!Yii::$app->session['isLogin']) {
          return  $this->redirect(['member/auth']);
       }
       // 获取用户名称
       $name = Yii::$app->session['loginname'];
       $userid = User::find()->where('username = :name',[':name'=>$name])->one()->userid;
       // var_dump($userid);
       // exit();
        //分情况处理 post方法
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $num  = Yii::$app->request->post()['productnum'];
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userid;
        }
        // get方法
       if (Yii::$app->request->isGet) {
           $productid = Yii::$app->request->get('productid');
           $model = Product::find()->where('productid = :pid',[':pid'=>$productid])->one();
           $price = $model->issale ? $model->saleprice :$model->price;
           $num = 1;
           $data['Cart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];     
       }
        //购物车 数据查找
       $model = Cart::find()->where('productid = :pid and userid = :uid',[':pid'=>$data['Cart']['productid'],':uid'=>$data['Cart']['userid']])->one();

       if (!$model) {
         $model = new Cart;
       }else{
         $data['Cart']['productnum'] = $model->productnum + $num;
       }
       // $data['Cart']['cartid']     =uniqid();
       $data['Cart']['createtime'] = time();
       $model->load($data);
       $model->save();
       return $this->redirect(['cart/index']);
    }
    /**
     * 修改数量
     * 2017年3月1日 17:51:41
     * @return [type] [description]
     */
     public function actionMod()
    {
      //ajax 通信GET方法获取数字
      $cartid = Yii::$app->request->get('cartid');
      $productnum = Yii::$app->request->get('productnum');
      // echo $cartid;exit();
      // 刷新数据
      Cart::updateAll(['productnum'=>$productnum],'cartid = :cid',
        [':cid' => $cartid]);
      //jquery代码有问题 无法区分不同的购物车 只会给第一个购物车添加
        //2017年3月2日 21:55:30、
        //需要改进
    }
    /**
     * 删除购物车
     * 2017年3月1日 18:43:20
     * @return [type] [description]
     */
    public function actionDel()
    {
      $cartid = Yii::$app->request->get('cartid');
      // 删除数据
      Cart::deleteAll('cartid = :cid',[':cid'=>$cartid]);
      return $this->redirect(['cart/index']);
    }

}
