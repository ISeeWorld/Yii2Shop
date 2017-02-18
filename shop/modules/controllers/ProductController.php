<?php 
namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use crazyfd\qiniu\Qiniu;
class ProductController extends Controller
{
    /**
     * 商品展示
     * 2017年2月18日 20:19:10
     * @return [type] [description]
     */
    public function actionList()
    {
       $this->layout = 'layout1';
       $model = new Product;
       $products = [];
       $pager = [];
       return $this->render('products',['model'=>$model,'products'=>$products,'pager'=>$pager]);
    }
    /**
     * 添加商品
     * 2017年2月18日 20:18:55
     * @return [type] [description]
     */
    public function actionAdd()
    {
       $this->layout = 'layout1';
       $model = new Product;
       $cates = new Category;
       $opts = $cates->getOptions();
       unset($opts[0]);


       if (Yii::$app->requeset->isPost) {
           $post = Yii::$app->requeset->post();
           $pics = $this->upload();
           if (!$pics) {
             $model->addError('cover','封面不能为空！');
           }
       }
       return $this->render('add',['model' =>$model,'opts'=>$opts]);
    }
    /**
     * 上传方法
     * 2017年2月18日 21:11:56
     * @return [type] [description]
     */
    private function upload()
    {
      if ($_FILES['Product']['error']['cover']<0) {
        return false;
      }
      $qiniu = new Qiniu(Category::AK,Category::SK,);

    }
}










 ?>