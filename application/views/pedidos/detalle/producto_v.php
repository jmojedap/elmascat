<?php
    $src_alt = $src_alt = URL_IMG . 'app/250px_producto.png';   //Imagen alternativa

    $att_img['src'] = RUTA_UPLOADS . $row_archivo->carpeta . '500px_' . $row_archivo->nombre_archivo;
    $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
    $att_img['id'] = "img_producto";
?>

<script>
    $(document).ready(function(){
        $('.mini_img').click(function(){
            $('#img_producto').attr('src', $(this).data('src'));
            //$('#img_producto').attr('src', '');
        });
    });
</script>

<div class="row" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
    <div class="col-sm-3">
        <div class="left-sidebar">
            <?php $this->load->view('busquedas/widget_categorias_v'); ?>
            <?php $this->load->view('busquedas/widget_fabricantes_v'); ?>
        </div>
    </div>

    <div class="col-sm-9 padding-right">
        <div class="product-details"><!--product-details-->
            <div class="col-sm-5">
                <div class="view-product">
                    <?= img($att_img) ?>
                    <h3>ZOOM</h3>
                </div>
                <div id="similar-product" class="carousel slide" data-ride="carousel">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <?php foreach ($imagenes->result() as $row_imagen) : ?>
                                <?php
                                    $att_mini = array(
                                        'src' => $row_imagen->url_carpeta . '250px_' . $row_imagen->nombre_archivo,
                                        'width' => '60px',
                                        'data-src' => $row_imagen->url_carpeta . '250px_' . $row_imagen->nombre_archivo,
                                        'class' => 'mini_img'
                                    );
                                ?>
                                <a href="#">
                                    <?= img($att_mini) ?>
                                </a>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left item-control" href="#similar-product" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a class="right item-control" href="#similar-product" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
            <div class="col-sm-7">
                <div class="product-information"><!--/product-information-->
                    <img src="<?= URL_ASSETS ?>eshopper/images/product-details/new.jpg" class="newarrival" alt="" />
                    <h2><?= $row->nombre_producto ?></h2>
                    <p>Web ID: <?= $row->id ?></p>
                    <img src="<?= URL_ASSETS ?>eshopper/images/product-details/rating.png" alt="" />
                    <span>
                        <span>COP $<?= number_format($row->precio, 0, ',', '.') ?></span>
                        <label>Cant:</label>
                        <input type="text" value="1" />
                        <button type="button" class="btn btn-fefault cart">
                            <i class="fa fa-shopping-cart"></i>
                            Al carrito
                        </button>
                    </span>
                    <p>
                        
                    </p>
                    <p><b>Disponibles:</b> <?= $row->cant_disponibles ?></p>
                    <p><b>Editorial:</b> <?= $this->App_model->nombre_item($row->fabricante_id) ?></p>
                    <a href=""><img src="<?= URL_ASSETS ?>eshopper/images/product-details/share.png" class="share img-responsive"  alt="" /></a>
                </div><!--/product-information-->
            </div>
        </div><!--/product-details-->

        <div class="category-tab shop-details-tab"><!--category-tab-->
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#companyprofile" data-toggle="tab">Detalles</a></li>
                    <li class=""><a href="#reviews" data-toggle="tab">Comentarios (5)</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="companyprofile" style="padding: 2%;">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-info-circle"></i> Descripción</h4>
                            <p>
                                <?= $row->descripcion ?>
                            </p>
                        </div>
                    </div>
                    
                    <hr/>
                    
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Dimensiones</td>
                                <td>
                                    <?= $row->alto ?> x 
                                    <?= $row->ancho ?> cm
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Peso</td>
                                <td>
                                    <?= $row->peso ?> gramos
                                </td>
                            </tr>
                            
                            <?php foreach ($metadatos->result() as $row_metadato) : ?>
                                <tr>
                                    <td><?= $row_metadato->nombre_metadato ?></td>
                                    <td>
                                        <?= $row_metadato->valor ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    
                </div>

                <div class="tab-pane fade" id="reviews" >
                    <div class="col-sm-12">
                        <ul>
                            <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                            <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                            <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        <p><b>Write Your Review</b></p>

                        <form action="#">
                            <span>
                                <input type="text" placeholder="Your Name"/>
                                <input type="email" placeholder="Email Address"/>
                            </span>
                            <textarea name="" ></textarea>
                            <b>Rating: </b> <img src="images/product-details/rating.png" alt="" />
                            <button type="button" class="btn btn-default pull-right">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div><!--/category-tab-->

        <div class="recommended_items"><!--recommended_items-->
            <h2 class="title text-center">recommended items</h2>

            <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="item active">	
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend1.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend2.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend3.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">	
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend1.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend2.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/home/recommend3.jpg" alt="" />
                                        <h2>$56</h2>
                                        <p>Easy Polo Black Edition</p>
                                        <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>			
            </div>
        </div><!--/recommended_items-->

    </div>
</div>