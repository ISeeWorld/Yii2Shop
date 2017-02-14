   <?php 
   use yii\bootstrap\ActiveForm;
   use yii\helpers\Html;

    ?>

    <link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen" />
    <!-- main container -->
    <div class="content">

        <div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>添加分类</h3>
                </div>

                <!-- Users table -->
                <div class="row-fluid table">
                    <table class="table table-hover">
                        <tbody>
                        <!-- row -->
                         <div class="container">
                                <?php
                                if (Yii::$app->session->hasFlash('info')) {
                                    echo Yii::$app->session->getFlash('info');
                                }

                                $form = ActiveForm::begin([
                                    'fieldConfig' => [
                                        'template' => '<div class="span12 field-box">{label}{input}</div>{error}',
                                    ],
                                    'options' => [
                                        'class' => 'new_user_form inline-input',
                                    ],
                                    ]);
                                echo $form->field($model, 'parentid')->dropDownList($list);

                                echo $form->field($model, 'title')->textInput(['class' => 'span9']);
                                ?>
                                <div class="span11 field-box actions ">
                                    <?php echo Html::submitButton('添加', ['class' => 'btn-glow primary']); ?>
                                    <span>或者</span>
                                    <?php echo Html::resetButton('取消', ['class' => 'reset']); ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                <!-- end users table -->
            </div>
        </div>
    </div>
    <!-- end main container -->
