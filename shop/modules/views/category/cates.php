    <link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen" />
    <!-- main container -->
    <div class="content">

        <div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>分类列表</h3>
                    <div class="span10 pull-right">
                        <a href="<?php echo yii\helpers\Url::to(['category/add']) ?>" class="btn-flat success pull-right">
                            <span>&#43;</span>
                            添加新顶级分类
                        </a>
                    </div>
                </div>

                <!-- Users table -->
                <div class="row-fluid table">
                <?php 
            if (\Yii::$app->session->hasFlash('info')) {
           echo \Yii::$app->session->getFlash('info');
             }

                 ?>
                    <table class="table table-hover">
                    <tbody> 
                        <thead>
                            <tr>
                                <th class="span3 sortable">
                                    <span class="line"></span>序号
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>分类名称
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>操作
                                </th>
<!--                                 <th class="span3 sortable">
                                    <span class="line"></span>性别
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>年龄
                                </th> -->
            
                        </thead>
                    </tbody>
                    <?php 
                      foreach ($cates as $v){
                     ?>
                     <tr class="first">
                         <td>
                             <?php echo $v['cateid']; ?>
                         </td>
                         <td>
                             <?php echo $v['title']; ?>
                         </td>
                          <td >
                          <a href="<?php echo yii\helpers\Url::to(['category/edit','cateid' =>$v['cateid']]);?>">编辑</a>
                         <a href="<?php echo yii\helpers\Url::to(['category/del','cateid' =>$v['cateid']]);?>">删除</a>
                          </td>
                     </tr>
                   <?php }; ?>
                <!-- end users table -->
            </div>
        </div>
    </div>
    <!-- end main container -->
