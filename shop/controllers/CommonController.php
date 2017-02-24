<?php   
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Category;
use app\models\Product;
use app\models\User;
use app\models\Cart;

/**
 * 获取模型中的数据给视图
 * 2017年2月23日 23:18:00
 */
class CommonController extends Controller
{
    /**
     * 覆盖重写父类的方法
     * 2017年2月23日 23:19:11
     * @return [type] [description]
     */
     public function init()
    {
     //获取菜单分类数据
     $menu = Category::getMenu();
     $this->view->params['menu'] = $menu;
     // 准备存储的二维数组
     $data =[];
     $data['products']=[];
     $total = 0;
     //根据session获取用户登录信息 查询到当前用户
     if (Yii::$app->session['isLogin']) {
     // 调去当前用户的信息
         $usermodel = User::find()->where('username = :user',[':user'=>Yii::$app->session['loginname']])->one();
         if (!empty($usermodel) && !empty($usermodel->userid)) {
            $userid = $usermodel->userid;
            //获取购物车中的物品
            $carts = Cart::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
            foreach($carts as $k=>$pro) {
                    $product = Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one();
                    $data['products'][$k]['cover'] = $product->cover;
                    $data['products'][$k]['title'] = $product->title;
                    $data['products'][$k]['productnum'] = $pro['productnum'];
                    $data['products'][$k]['price'] = $pro['price'];
                    $data['products'][$k]['productid'] = $pro['productid'];
                    $data['products'][$k]['cartid'] = $pro['cartid'];
                    $total += $data['products'][$k]['price'] * $data['products'][$k]['productnum'];
                }
         }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;
        $tui = Product::find()->where('istui = "1" and ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $new = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $hot = Product::find()->where('ison = "1" and ishot = "1"')->orderby('createtime desc')->limit(3)->all();
        $sale = Product::find()->where('ison = "1" and issale = "1"')->orderby('createtime desc')->limit(3)->all();
        $this->view->params['tui'] = (array)$tui;
        $this->view->params['new'] = (array)$new;
        $this->view->params['hot'] = (array)$hot;
        $this->view->params['sale'] = (array)$sale;

     }
    }
}













 ?>