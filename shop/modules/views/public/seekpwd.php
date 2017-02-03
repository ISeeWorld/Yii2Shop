<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?><!DOCTYPE html>
<html class="login-bg">

    <head>
        <title>慕课商城 - 找回密码</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- bootstrap -->
        <link href="assets/admin/css/bootstrap/bootstrap.css" rel="stylesheet" />
        <link href="assets/admin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
        <link href="assets/admin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
        <!-- global styles -->
        <link rel="stylesheet" type="text/css" href="assets/admin/css/layout.css" />
        <link rel="stylesheet" type="text/css" href="assets/admin/css/elements.css" />
        <link rel="stylesheet" type="text/css" href="assets/admin/css/icons.css" />
        <!-- libraries -->
        <link rel="stylesheet" type="text/css" href="assets/admin/css/lib/font-awesome.css" />
        <!-- this page specific styles -->
        <link rel="stylesheet" href="assets/admin/css/compiled/signin.css" type="text/css" media="screen" />
        <!-- open sans font -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

    <body>
        <div class="row-fluid login-wrapper">
            <a class="brand" href="index.html"></a>
<!--             <form id="w0" action="/index.php?r=admin/public/login" method="post" role="form"> -->
            <?php $form = ActiveForm::begin(
    [
        'fieldConfig' => [
            'template' => '{input}{error}',
        ],
    ]

);?>
   <!-- cause bug!!!    <input type="hidden" name="_csrf" value="VGVVTVVZb0UmKzIrLWtCL2VdNB0tLxoQJVU0AQA6JzMwIgQ0Zy0sNw=="> -->
                <div class="span4 box">
                    <div class="content-wrap">
                        <h6>慕课商城 - 找回密码</h6>
<?php
if (Yii::$app->session->hasFlash('info')) {
    echo Yii::$app->session->getFlash('info');
}
?>
                        <div class="form-group field-admin-adminuser">
                            <p class="help-block help-block-error"></p>
<!--                             <input type="text" id="admin-adminuser" class="span12" name="Admin[adminuser]" placeholder="管理员账号"></div> -->
<?php
echo $form->field($admin_model, 'adminuser')->textInput([
    "class" => "span12", "placeholder" => "管理员账号",
    "id"    => "admin-adminuser",
]);
?>
                        <div class="form-group field-admin-adminpass">
                            <p class="help-block help-block-error"></p>
<?php
echo $form->field($admin_model, 'adminemail')->textInput([
    "class" => "span12", "placeholder" => "管理员邮箱",
    "id"    => "admin-adminemail",
]);
?>
<!--                             <input type="password" id="admin-adminpass" class="span12" name="Admin[adminpass]" placeholder="管理员密码"></div> -->

    <a href="<?php echo yii\helpers\Url::to(['public/login']); ?>" class="forgot">返回登录</a>
    <div class="form-group field-remember-me">
        <div class="remember">

<!-- <input type="checkbox" id="remember-me" name="Admin[rememberMe]" value="1" checked><label for="remember-me">记住我</label></div> -->
                        </div>
<?php
echo Html::SubmitButton('找回密码', ['class' => 'btn-glow primary login']);

?>
<!--                         <button type="submit" class="btn-glow primary login">登录</button></div>
                </div> -->
            <!-- </form> -->
            <?php ActiveForm::end();?>

        </div>
        <!-- scripts -->
        <script src="assets/admin/js/jquery-latest.js"></script>
        <script src="assets/admin/js/bootstrap.min.js"></script>
        <script src="assets/admin/js/theme.js"></script>
        <!-- pre load bg imgs -->
        <script type="text/javascript">$(function() {
                // bg switcher
                var $btns = $(".bg-switch .bg");
                $btns.click(function(e) {
                    e.preventDefault();
                    $btns.removeClass("active");
                    $(this).addClass("active");
                    var bg = $(this).data("img");

                    $("html").css("background-image", "url('img/bgs/" + bg + "')");
                });

            });</script>
    </body>

</html>