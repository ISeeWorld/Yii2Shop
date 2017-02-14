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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="span3 sortable">
                                    <span class="line"></span>用户名
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>真实姓名
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>昵称
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>性别
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>年龄
                                </th>
            
                        </thead>

                <div class="pagination pull-right">

                </div>
                <!-- end users table -->
            </div>
        </div>
    </div>
    <!-- end main container -->
