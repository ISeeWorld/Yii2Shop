
        <!-- end sidebar -->
        <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>管理员列表</h3>
                        <div class="span10 pull-right">
                            <a href="<?php echo yii\helpers\Url::to(['manage/reg']); ?>" class="btn-flat success pull-right">
                <span>&#43;</span>添加新管理员</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="span2">管理员ID</th>
                                    <th class="span2">
                                        <span class="line"></span>管理员账号</th>
                                    <th class="span2">
                                        <span class="line"></span>管理员邮箱</th>
                                    <th class="span3">
                                        <span class="line"></span>最后登录时间</th>
                                    <th class="span3">
                                        <span class="line"></span>最后登录IP</th>
                                    <th class="span2">
                                        <span class="line"></span>添加时间</th>
                                    <th class="span2">
                                        <span class="line"></span>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row -->
                         <?php foreach ($manager_data as $manager) {?>
                                <tr>
                                    <td><?php echo $manager->adminid ?></td>
                                    <td><?php echo $manager->adminuser ?></td>
                                    <td><?php echo $manager->adminemail ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $manager->logintime); ?></td>
                                    <td><?php echo long2ip($manager->loginip); ?></td>
                                    <td><?php echo date('Y:m:d H:i:s', $manager->createtime); ?></td>
                                    <td class="align-right">
                                        <a href="<?php echo yii\helpers\Url::to(['manage/del', 'adminid' => $manager->adminid]); ?>">删除</a></td>
                                </tr>
                           <?php }?>

                            </tbody>
                        </table>
                    </div>
<?php
if (Yii::$app->session->hasFlash('info')) {
    echo Yii::$app->session->getFlash('info');
}
?>
                    <div class="pagination pull-right">
<?php echo yii\widgets\LinkPager::widget(['pagination' => $pager]); ?>
                    </div>
                    <!-- end users table</div> -->
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
