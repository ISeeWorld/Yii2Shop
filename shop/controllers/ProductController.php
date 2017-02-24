<?php
namespace app\controllers;

use yii\web\Controller;
use app\controllers\CommonController;
use Yii;
use app\models\Product;
use yii\data\Pagination;
class ProductController extends CommonController
{
    
    public function actionIndex()
    {
        $this->layout = "layout2";
        $cid = Yii::$app->request->get("cateid");
        //查处对应分类且上架的商品  
        //语句书写风格很好 值得学习
        //2017年2月25日 00:17:11
        $where = "cateid = :cid and ison = '1'";
        $params = [':cid' => $cid];
        $model = Product::find()->where($where, $params);
        $all = $model->asArray()->all();
        // 数组形式获取所有相关材料
        
        $count = $model->count();
        $pageSize = 8;
        // $pageSize = Yii::$app->params['pageSize']['frontproduct'];
        // 使用分页类进行使用
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $all = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        
        $tui = $model->Where($where . ' and istui = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $hot = $model->Where($where . ' and ishot = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->Where($where . ' and issale = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        return $this->render("index", ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count]);
    }
    /**
     * 商品详细信息的获取
     * @return [type] [description]
     */
    public function actionDetail()
    {
         $this->layout = "layout2";
        $productid = Yii::$app->request->get("productid");
        $where = 'productid = :id';
        $params= [':id' => $productid];
        $product = Product::find()->where($where,$params)->asArray()->one();
        // $product = Product::find()->where('productid = :id', [':id' => $productid])->asArray()->one();
        // 获取相关商品
        $data['all'] = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(7)->all();
        // 数据的全部获取
        return $this->render("detail", ['product' => $product, 'data' => $data]);
    }
}
