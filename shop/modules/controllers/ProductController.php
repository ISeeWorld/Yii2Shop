<?php 
namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;
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
        $model = Product::find();
        $count = $model->count();
        //$pageSize = Yii::$app->params['pageSize']['product'];
        $pageSize =6;
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render("products", ['pager' => $pager, 'products' => $products]);
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


       if (Yii::$app->request->isPost) {
           $post = Yii::$app->request->post();
           $pics = $this->upload();
           if (!$pics) {
             $model->addError('cover','封面不能为空！');
           }else{
            $post['Product']['cover'] = $pics['cover'];
            $post['Product']['pics'] = $pics['pics'];
           }
           if ($pics && $model->add($post)) {
               Yii::$app->session->setFlash('info','添加成功！');
           }else{
               Yii::$app->session->setFlash('info','添加失败！');
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
      //关键语句  注意图片的实际链接网址
      $qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
      $key = uniqid();
      //封面照片 关键方法 具体查看
      $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
      $cover = $qiniu->getLink($key);
      // var_dump($cover);exit();
      $pics = [];
      //其他照片
      foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;
            }
            $key = uniqid();
            $qiniu->uploadFile($file, $key);
            $pics[$key] = $qiniu->getLink($key);
        }
        return ['cover' => $cover, 'pics' => json_encode($pics)];

    }
}

 ?>