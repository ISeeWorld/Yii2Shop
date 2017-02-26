<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\Product;
use app\controllers\CommonController;
use Yii;
class CartController extends CommonController
{

    public function actionIndex()
    {
        $this->layout = "layout1";
        return $this->render('index');
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
           $price = $model->issale : $model->saleprice :$model->price;
           $num = 1;
           $data['Cart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];     
       }



    }


}
