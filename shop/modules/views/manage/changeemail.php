<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
        <!-- end sidebar -->
        <link rel="stylesheet" href="assets/admin/css/compiled/new-user.css" type="text/css" media="screen" />
        <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="new-user">
                    <div class="row-fluid header">
                        <h3>修改管理员邮箱</h3>
                        </div>
                    <div class="row-fluid form-wrapper">
                        <!-- left column -->
                        <div class="span9 with-sidebar">
                            <div class="container">
<?php
$form = ActiveForm::begin(
    [
        'options'     => ['class' => 'new_user_form inline-input'],
        'fieldConfig' => ['template' => '<div class="span12 field-box">{label}{input}{error}</div>
                '],
    ]);
?>
<!--                                     <input type="hidden" name="_csrf" value="My1Fc0FTZ2JBYyIVOWFKCAIVJCM5JRI3Qh0kPxQwLxRXahQKcyckEA=="> -->
<!-- //bug name="_csrf" ...... -->
                                    <div class="form-group field-admin-adminuser">
                                        <div class="span12 field-box">
<?php
echo $form->field($admin_model, 'adminuser')->textInput([
    "class" => "span9", 'disabled' => true,
]);
?>

<?php
echo $form->field($admin_model, 'adminpass')->passwordInput([
    "class" => "span9",
]);
?>

<?php
echo $form->field($admin_model, 'adminemail')->textInput([
    "class" => "span9",
]);
?>

<?php
if (Yii::$app->session->hasFlash('info')) {
    echo Yii::$app->session->getFlash('info');
}
?>
<div class="span11 field-box actions">
<?php echo Html::submitButton('电子邮箱修改', ['class' => 'btn-glow primary']); ?>
<span>或者</span>
<?php echo Html::resetButton('取消', ['class' => 'reset']); ?>
</div>

<?php
ActiveForm::end();
?>
                        </div>
                        </div>
                        </div>
                        </div>
                        <!-- side right column -->
                        <div class="span3 form-sidebar pull-right">
                            <div class="alert alert-info hidden-tablet">
                                <i class="icon-lightbulb pull-left"></i>请在左侧填写管理员相关信息，包括管理员账号，电子邮箱，以及密码</div>
                            <h6>重要提示：</h6>
                            <p>管理员可以管理后台功能模块</p>
                            <p>请谨慎添加</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- end main container -->
        <!-- scripts -->
        <script src="/assets/admin/js/jquery-latest.js"></script>
        <script src="/assets/admin/js/bootstrap.min.js"></script>
        <script src="/assets/admin/js/jquery-ui-1.10.2.custom.min.js"></script>
        <!-- knob -->
        <script src="/assets/admin/js/jquery.knob.js"></script>
        <!-- flot charts -->
        <script src="/assets/admin/js/jquery.flot.js"></script>
        <script src="/assets/admin/js/jquery.flot.stack.js"></script>
        <script src="/assets/admin/js/jquery.flot.resize.js"></script>
        <script src="/assets/admin/js/theme.js"></script>
        <script src="/assets/admin/js/wysihtml5-0.3.0.js"></script>
        <script src="/assets/admin/js/bootstrap-wysihtml5-0.0.2.js"></script>
        <script type="text/javascript">$(function() {

                // jQuery Knobs
                $(".knob").knob();

                // jQuery UI Sliders
                $(".slider-sample1").slider({
                    value: 100,
                    min: 1,
                    max: 500
                });
                $(".slider-sample2").slider({
                    range: "min",
                    value: 130,
                    min: 1,
                    max: 500
                });
                $(".slider-sample3").slider({
                    range: true,
                    min: 0,
                    max: 500,
                    values: [40, 170],
                });

                // jQuery Flot Chart
                var visits = [[1, 50], [2, 40], [3, 45], [4, 23], [5, 55], [6, 65], [7, 61], [8, 70], [9, 65], [10, 75], [11, 57], [12, 59]];
                var visitors = [[1, 25], [2, 50], [3, 23], [4, 48], [5, 38], [6, 40], [7, 47], [8, 55], [9, 43], [10, 50], [11, 47], [12, 39]];

                var plot = $.plot($("#statsChart"), [{
                    data: visits,
                    label: "注册量"
                },
                {
                    data: visitors,
                    label: "访客量"
                }], {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 1,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 0.1
                                },
                                {
                                    opacity: 0.13
                                }]
                            }
                        },
                        points: {
                            show: true,
                            lineWidth: 2,
                            radius: 3
                        },
                        shadowSize: 0,
                        stack: true
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#f9f9f9",
                        borderWidth: 0
                    },
                    legend: {
                        // show: false
                        labelBoxBorderColor: "#fff"
                    },
                    colors: ["#a7b5c5", "#30a0eb"],
                    xaxis: {
                        ticks: [[1, "一月"], [2, "二月"], [3, "三月"], [4, "四月"], [5, "五月"], [6, "六月"], [7, "七月"], [8, "八月"], [9, "九月"], [10, "十月"], [11, "十一月"], [12, "十二月"]],
                        font: {
                            size: 12,
                            family: "Open Sans, Arial",
                            variant: "small-caps",
                            color: "#697695"
                        }
                    },
                    yaxis: {
                        ticks: 3,
                        tickDecimals: 0,
                        font: {
                            size: 12,
                            color: "#9da3a9"
                        }
                    }
                });

                function showTooltip(x, y, contents) {
                    $('<div id="tooltip">' + contents + '</div>').css({
                        position: 'absolute',
                        display: 'none',
                        top: y - 30,
                        left: x - 50,
                        color: "#fff",
                        padding: '2px 5px',
                        'border-radius': '6px',
                        'background-color': '#000',
                        opacity: 0.80
                    }).appendTo("body").fadeIn(200);
                }

                var previousPoint = null;
                $("#statsChart").bind("plothover",
                function(event, pos, item) {
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(0),
                            y = item.datapoint[1].toFixed(0);

                            var month = item.series.xaxis.ticks[item.dataIndex].label;

                            showTooltip(item.pageX, item.pageY, item.series.label + " of " + month + ": " + y);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            });
            $(".wysihtml5").wysihtml5({
                "font-styles": false
            });
            $("#addpic").click(function() {
                var pic = $("#product-pics").clone();
                pic.attr("style", "margin-left:120px");
                $("#product-pics").parent().append(pic);
            });</script>
    </body>

</html>
