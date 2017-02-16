<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Category extends ActiveRecord
{

    public static function  tableName()
    {
        return "{{%category}}";
    }

    public function rules(){
         return [
          ['parentid','required','message'=>'顶级分类不能为空'],
          ['title','required','message'=>'标题不能为空'],
          ['createtime','safe']
         ];
    }
    
    public function attributeLabels(){
   
    return [
           'parentid' =>'顶级分类',
           'title'    =>'标题'
    ];
    }
    /**
     *添加并验证数据
     *2017年2月15日
     * @param [type] $data [description]
     */
    public function add($data){
      $data['Category']['createtime'] = time();
      if ($this->load($data)&& $this->save()) {
          return true;
      }
      return false;

    }
    /**
     * 获取分类数据
     * @return [type] [description]
     */
    public function getData()
    {
       $data = self::find()->all();
       $data = ArrayHelper::toArray($data);
       return $data;
    }
    /**
     * 获取分类的树形图
     * 2017年2月15日 22:20:19
     * @param  [type]  $cates [description]
     * @param  integer $pid   [description]
     * @return [type]         [description]
     */
    public function getTree($cates, $pid = 0)
    {
        $tree = [];
        foreach($cates as $cate) {
            if ($cate['parentid'] == $pid) {
                $tree[] = $cate;
                //递归方式去进行数据添加
                $tree = array_merge($tree, $this->getTree($cates, $cate['cateid']));
            }
        }
        return $tree;
    }
    /**
     * 设置前缀
     * 2017年2月15日 22:19:33
     * @param [type] $data [description]
     * @param string $p    [description]
     */
    public function setPrefix($data, $p = "|-----")
    {
        $tree = [];
        $num = 1;
        $prefix = [0 => 1];
        while($val = current($data)) {
            $key = key($data);
            if ($key > 0) {
                if ($data[$key - 1]['parentid'] != $val['parentid']) {
                    $num ++;
                }
            }
            if (array_key_exists($val['parentid'], $prefix)) {
                $num = $prefix[$val['parentid']];
            }
            $val['title'] = str_repeat($p, $num).$val['title'];
            $prefix[$val['parentid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }

    /**
     * 获取选项
     * 2017年2月15日 22:18:55
     * @return [type] [description]
     */
       public function getOptions()
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        $options = ['添加顶级分类'];
        foreach($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }
    
    /**
     * 获取数据列表
     * 2017年2月16日 21:33:27
     * @return [type] [description]
     */
    public function getTreeList()
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        return $tree = $this->setPrefix($tree);
    }

}







 ?>