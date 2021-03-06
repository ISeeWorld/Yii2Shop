<?php 
namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;
use app\modules\controllers\CommonController;

class ProductController extends CommonController
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
    /**
     * 修改用户数据
     * 2017年2月21日 22:20:31
     * 过于复杂需要仔细理解学习
     * @return [type] [description]
     */
    public function actionMod()
    {
        $this->layout = 'layout1';
        // $model = new Product;
        $cates = new Category;
        $opts = $cates->getOptions();
        $id = Yii::$app->request->get('productid');
        if (!empty($id)) {
        $model = Product::find()->where('productid = :id',[':id'=>$id])->one();
        }
        //
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            $post['Product']['cover'] = $model->cover;
            if ($_FILES['Product']['error']['cover'] == 0) {
                $key = uniqid();
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));

            }
            $pics = [];
            foreach($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;

                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);

            }  
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            //不太理解
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');
            }
          
       }
       return $this->render('add',['model' =>$model,'opts'=>$opts]);
    }
    /**
     * 删除图片
     * 2017年2月22日 20:52:00
     * @return [type] [description]
     */
    public function actionRemovepic()
    {
      $pid = Yii::$app->request->get('productid');
      $key = Yii::$app->request->get('key');
      //先删除数据库中的数据
      $model = Product::find()->where('productid = :pid',[':pid'=>$pid])->one();
      $pics = json_decode($model->pics,true);
      unset($pics[$key]);
      //删除7牛中的数
      $qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
      $qiniu->delete($key);
      // 更新数据
      Product::updateAll(['pics'=>json_encode($pics)],'productid = :pid',[':pid'=>$pid]);
      //函数语句不熟悉
      return $this->redirect(['product/mod','productid'=>$pid]);
    }
    /**
     * 删除的方法
     */
    public function actionDel()
    {
     $pid = Yii::$app->request->get('productid');
     $model = Product::find()->where('productid= :pid',[':pid'=>$pid])->one();
     //获取随机键值
     $key = basename($model->cover);
     $qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
     $qiniu->delete($key);
     $pics = json_decode($model->pics,true);
     foreach ($pics as $key => $value) {
         $qiniu->delete($key);
     }
     Product::deleteAll('productid = :pid',[':pid'=>$pid]);
     return $this->redirect(['product/list']);
    }
    /**
     * 商品上级操作
     * 2017年2月22日 21:22:25
     * @return boolean [description]
     */
    public function actionOn()
    {
     $pid = Yii::$app->request->get('productid');
     Product::updateAll(['isOn'=>'1'],'productid = :pid',[':pid'=>$pid]);
     return $this->redirect(['product/list']);
    }
    /**
     * 商品下架
     * 2017年2月22日 21:23:07
     * @return boolean [description]
     */
    public function actionOff()
    {
        $pid = Yii::$app->request->get('productid');
        Product::updateAll(['isOn'=>'0'],'productid = :pid',[':pid'=>$pid]);
        return $this->redirect(['product/list']);

    }



}

 ?>