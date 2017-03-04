
<!-- ============================================================= HEADER : END ============================================================= -->       <section id="cart-page">
    <div class="container">
        <!-- ========================================= CONTENT ========================================= -->
        <div class="col-xs-12 col-md-9 items-holder no-margin">
        <?php $total=0; $pnum=0 ;  ?>
            <?php foreach ($data as $k => $v) : ?>
            <div class="row no-margin cart-item">
            
                <div class="col-xs-12 col-sm-2 no-margin">
                    <a href="#" class="thumb-holder">
                        <img class="lazy" alt="" src="http://<?php echo $data[$k]['cover']?>-smallcover" />
                    </a>
                </div>

                <div class="col-xs-12 col-sm-5 ">
                    <div class="title">
                        <a href="#">
                            <?php echo $data[$k]['title'] ;?>
                        </a>
                    </div>
                    <div class="brand"><?php echo $data[$k]['productid'] ;?></div>
                </div>

                <div class="col-xs-12 col-sm-3 no-margin">
                    <div class="quantity">
                        <div class="le-quantity">
                            <form>
                                <a class="minus" href="#reduce"></a>
                                <input name="productnum" readonly="readonly" type="text" id="<?php echo $data[$k]['cartid'] ?>" value="<?php 
                                echo $data[$k]['productnum'] ?>" />
                                <a class="plus" href="#add"></a>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2 no-margin">
                    <div class="price">
                       <?php echo $data[$k]['price'] ?>
                    </div>
                    <a class="close-btn" href="<?php echo yii\helpers\Url::to(['cart/del','cartid'=>$data[$k]['cartid']]); ?>"></a>
                </div>
            
            </div> 
        <?php $total += $data[$k]['price']*$data[$k]['productnum'] ;
              $pnum  += $data[$k]['productnum']; 
        ?>
            <?php endforeach; ?>      
            <!-- /.cart-item -->  
        </div>
        <!-- ========================================= CONTENT : END ========================================= -->

        <!-- ========================================= SIDEBAR ========================================= -->

        <div class="col-xs-12 col-md-3 no-margin sidebar ">
            <div class="widget cart-summary">
                <h1 class="border">商品购物车</h1>
                <div class="body">
                    <ul class="tabled-data no-border inverse-bold">
                        <li>
                            <label>购物车总价</label>
                            <div class="value pull-right">
                                <?php echo $total; ?>
                            </div>
                        </li>
                        <li>
                            <label>运费</label>
                            <div class="value pull-right">
                                <?php echo $pnum*10; ?>
                            </div>
                        </li>
                    </ul>
                    <ul id="total-price" class="tabled-data inverse-bold no-border">
                        <li>
                            <label>订单总价</label>
                            <div class="value pull-right"> 
                            <?php echo $total; ?></div>
                        </li>
                    </ul>
                    <div class="buttons-holder">
                        <a class="le-button big" href="checkout.html" >去结算</a>
                        <a class="simple-link block" href="index.html" >继续购物</a>
                    </div>
                </div>
            </div><!-- /.widget -->

            <div id="cupon-widget" class="widget">
                <h1 class="border">使用优惠券</h1>
                <div class="body">
                    <form>
                        <div class="inline-input">
                            <input data-placeholder="请输入优惠券码" type="text" />
                            <button class="le-button" type="submit">使用</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.widget -->
        </div><!-- /.sidebar -->

        <!-- ========================================= SIDEBAR : END ========================================= -->
    </div>
</section>      <!-- ============================================================= FOOTER ============================================================= -->
