<?php 
namespace app\modules\controllers;

use app\models\Category;
use Yii;
use yii\web\Controller;
use app\modules\controllers\CommonController;

class CategoryController extends CommonController
{
   /**
    * 分类列表显示函数
    * 2017年2月14日 23:03:49
    * @return [type] [description]
    */
   public function actionList(){
    
    $this->layout = 'layout1';
    $models = new Category;
    $list = $models->getTreeList();
    return $this->render('cates',['model'=>$models,'cates'=>$list]);
   }
 
   /**
    * 顶级分类添加
    * 2017年2月14日 23:04:17
    * @return [type] [description]
    */
   public function actionAdd()
   {
       $models = new Category;
       $this->layout = 'layout1';
       $list = $models->getOptions();
       if (Yii::$app->request->isPost) {
           $post = Yii::$app->request->post();
           if ($models->add($post)) {
              Yii::$app->session->setFlash('info','添加成功！');
           }
       }
       return $this->render('add',['model'=>$models,'list'=>$list]);

   }
   /**
    * 删除分类操作
    * 2017年2月17日 21:34:47
    * 重点学习try catch 语句使用
    * 及数据库操作语言的使用
    * @return [type] [description]
    */
   public function actionDel()
   {
      try{
        $id = Yii::$app->request->get('cateid');
        if (empty($id)) {
           throw new \Exception("ID参数错误！");
        }
        $data = Category::find()->where('parentid = :id',[':id'=>$id])->one();
        if ($data) {
          throw new \Exception("该分类下有子类，不允许删除!");
        }
        if (!Category::deleteAll('cateid = :id',[':id' => $id])){
           throw new \Exception('删除失败！');
        }
      }catch(\Exception $e){
        Yii::$app->session->setFlash('info',$e->getMessage());
      }
      return $this->redirect(['category/list']);
   }

    /**
     * 编辑分类
     * 2017年2月16日 21:43:31
     * @return [type] [description]
     */
   public function actionEdit()
   {
     $this->layout = "layout1";
     $id = Yii::$app->request->get('cateid');
     $model = Category::find()->where('cateid = :id',[':id'=>$id])->one();
     if (Yii::$app->request->isPost) {
         $post = Yii::$app->request->post();
         if ($model->load($post) && $model->save()) {
             Yii::$app->session->setFlash('info','修改成功!');
         }
     }
     $list = $model->getOptions();
     return $this->render('add',['model'=>$model,'list'=>$list]);

   }



}









 ?>