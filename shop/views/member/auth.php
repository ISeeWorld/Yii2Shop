<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!-- ============================================================= HEADER : END ============================================================= -->       <!-- ========================================= MAIN ========================================= -->
<main id="authentication" class="inner-bottom-md">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <section class="section sign-in inner-right-xs">
                    <h2 class="bordered">登录</h2>
                    <p>欢迎您回来，请您输入您的账户名密码</p>

                    <div class="social-auth-buttons">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn-block btn-lg btn btn-facebook"><i class="fa fa-qq"></i> 使用QQ账号登录</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn-block btn-lg btn btn-twitter"><i class="fa fa-weibo"></i> 使用新浪微博账号登录</button>
                            </div>
                        </div>
                    </div>

                    <form role="form" class="login-form cf-style-1">
                        <div class="field-row">
                            <label>电子邮箱</label>
                            <input type="text" class="le-input">
                        </div><!-- /.field-row -->

                        <div class="field-row">
                            <label>密码</label>
                            <input type="text" class="le-input">
                        </div><!-- /.field-row -->

                        <div class="field-row clearfix">
                            <span class="pull-left">
                                <label class="content-color"><input type="checkbox" class="le-checbox auto-width inline"> <span class="bold">记住我</span></label>
                            </span>
                            <span class="pull-right">
                                <a href="#" class="content-color bold">忘记密码 ?</a>
                            </span>
                        </div>

                        <div class="buttons-holder">
                            <button type="submit" class="le-button huge">安全登录</button>
                        </div><!-- /.buttons-holder -->
                    </form><!-- /.cf-style-1 -->

                </section><!-- /.sign-in -->
            </div><!-- /.col -->

            <div class="col-md-6">
                <section class="section register inner-left-xs">
                    <h2 class="bordered">新建账户</h2>
                    <p>创建一个属于你自己的账户</p>
<?php
if (Yii::$app->session->hasFlash('info')) {
    echo Yii::$app->session->getFlash('info');
}
$form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => '<div class="field-row">{label}{input}</div>{error}',
    ],
    'options'     => [
        'class' => 'register-form cf-style-1',
        'role'  => 'form',
    ],
    'action'      => ['member/reg'],
]);
?>
                        <?php echo $form->field($reg_model, 'useremail')->textInput(['class' => 'le-input']); ?>
                        <div class="buttons-holder">
                            <?php echo Html::submitButton('注册', ['class' => 'le-button huge']); ?>
                        </div><!-- /.buttons-holder -->
                    <?php ActiveForm::end();?>

                    <h2 class="semi-bold">加入我们您将会享受到前所未有的购物体验 :</h2>

                    <ul class="list-unstyled list-benefits">
                        <li><i class="fa fa-check primary-color"></i> 快捷的购物体验</li>
                        <li><i class="fa fa-check primary-color"></i> 便捷的下单方式</li>
                        <li><i class="fa fa-check primary-color"></i> 更加低廉的商品</li>
                    </ul>

                </section><!-- /.register -->

            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container -->
</main><!-- /.authentication -->
<!-- ========================================= MAIN : END ========================================= -->     <!-- ============================================================= FOOTER ============================================================= -->
