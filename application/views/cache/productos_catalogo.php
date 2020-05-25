<div class="">
    <div class="">

        <script>
        //VARIABLES
        //---------------------------------------------------------------------------------------------------
        var base_url = 'http://www.districatolicas.com/tienda/';
        var producto_id = 0;

        //DOCUMENT
        //---------------------------------------------------------------------------------------------------

        $(document).ready(function() {
            $('.ajax_add_to_cart_button').click(function() {
                producto_id = $(this).data('producto_id');
                guardar_detalle();
            });
        });

        //FUNCIONES
        //---------------------------------------------------------------------------------------------------
        //Ajax
        function guardar_detalle() {
            $.ajax({
                type: 'POST',
                url: base_url + 'pedidos/guardar_detalle',
                data: {
                    producto_id: producto_id,
                    cantidad: 1
                },
                success: function() {
                    window.location = base_url + 'pedidos/carrito';
                }
            });
        }
        </script>
        <div class="row">
            <section class="col-main col-sm-9 col-sm-push-3 wow bounceInUp animated animated animated"
                style="visibility: visible;">


                <div class="category-products">

                    <div class="toolbar">
                        <div id="sort-by">
                            <label class="left">Ordenar por: </label>
                            <ul>
                                <li>
                                    <a href="#">
                                        Orden <span class="right-arrow"></span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="http://www.districatolicas.com/tienda/productos/catalogo/?o=slug"
                                                class="" title="">Nombre</a> </li>
                                        <li>
                                            <a href="http://www.districatolicas.com/tienda/productos/catalogo/?o=precio"
                                                class="" title="">Precio</a> </li>
                                    </ul>
                                </li>
                            </ul>
                            <a href="http://www.districatolicas.com/tienda/productos/catalogo/?ot=asc"
                                class="button-asc left" title="Orden ascendente"><span
                                    class="glyphicon glyphicon-arrow-up"></span></a> <a
                                href="http://www.districatolicas.com/tienda/productos/catalogo/?ot=desc"
                                class="button-asc left" title="Orden descendente"><span
                                    class="glyphicon glyphicon-arrow-down"></span></a>
                        </div>
                        <div class="pager">
                            <div class="pages">
                                <ul class="pagination">
                                    <li class="active"><a href="#">1</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=9"
                                            data-ci-pagination-page="9">2</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=18"
                                            data-ci-pagination-page="18">3</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=27"
                                            data-ci-pagination-page="27">4</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=36"
                                            data-ci-pagination-page="36">5</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=9"
                                            data-ci-pagination-page="9"><i class="fa fa-caret-right"></i></a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=3384"
                                            data-ci-pagination-page="3384"><i class="fa fa-step-forward"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <ul class="products-grid">

                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">


                                <div class="new-label new-top-right">Nuevo</div>

                                <div class="product-image-area">
                                    <a class="product-image" title="Minutos de Amor En Línea - Mayo 2020"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/17206">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2020/04/500px_252814e0cf5e8d75bb133cdccb889f70.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="17206">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Minutos de Amor En Línea - Mayo 2020"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/17206">
                                                Minutos de Amor En Línea - Mayo 2020 </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>7.000 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">


                                <div class="new-label new-top-right">Nuevo</div>

                                <div class="product-image-area">
                                    <a class="product-image" title="Minutos de Amor En Línea - Suscripción por 3 meses"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/17205">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2020/04/500px_ef1d459b98778b6fc3ab864863605bd0.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="17205">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Minutos de Amor En Línea - Suscripción por 3 meses"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/17205">
                                                Minutos de Amor En Línea - Suscripción por 3 meses </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>17.000 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">


                                <div class="new-label new-top-right">Nuevo</div>

                                <div class="product-image-area">
                                    <a class="product-image" title="Minutos de Amor En Línea - Suscripción por 6 meses"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/17204">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2020/04/500px_92dadf7b8b74bcc073125e95da260900.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="17204">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Minutos de Amor En Línea - Suscripción por 6 meses"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/17204">
                                                Minutos de Amor En Línea - Suscripción por 6 meses </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>33.000 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">



                                <div class="product-image-area">
                                    <a class="product-image" title="Mugs  Virgen Milagrosa"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/15824">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2019/06/500px_0d102e858a9a9105c0a042687de0fc55.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="15824">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Mugs  Virgen Milagrosa"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/15824">
                                                Mugs Virgen Milagrosa </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>12.000 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">



                                <div class="product-image-area">
                                    <a class="product-image"
                                        title="Penitencia por Amor, Un Camino para Sanar y Reparar tu Historia de vida"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/16741">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2019/03/500px_55aa7c4afb5d2e80c3e92475e0117949.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="16741">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Penitencia por Amor, Un Camino para Sanar y Reparar tu Historia de vida"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/16741">
                                                Penitencia por Amor, Un Camino para Sanar y Reparar tu Historia de vida
                                            </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>23.400 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">



                                <div class="product-image-area">
                                    <a class="product-image" title="San José en la Mística Ciudad de Dios"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/13368/san-jose-en-la-mistica-ciudad-de-dios">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2018/11/500px_34648a0b7b9a86f03e960af6123625f2.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="13368">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="San José en la Mística Ciudad de Dios"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/13368/san-jose-en-la-mistica-ciudad-de-dios">
                                                San José en la Mística Ciudad de Dios </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>26.500 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">



                                <div class="product-image-area">
                                    <a class="product-image" title="Oraciones para momentos difíciles y desesperados"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/13417/oraciones-para-momentos-dificiles">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2018/09/500px_0e73172546c948a10ce16220899439d5.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="13417">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Oraciones para momentos difíciles y desesperados"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/13417/oraciones-para-momentos-dificiles">
                                                Oraciones para momentos difíciles y desesperados </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>17.700 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">



                                <div class="product-image-area">
                                    <a class="product-image"
                                        title="Penitencia por Amor Extraordinaria, Para sanar desde el vientre materno tu mente, tu cuerpo y tu alma. "
                                        href="http://www.districatolicas.com/tienda/productos/visitar/16736">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2019/03/500px_95a463140cf9245f49c420c5549e7b1d.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="16736">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Penitencia por Amor Extraordinaria, Para sanar desde el vientre materno tu mente, tu cuerpo y tu alma. "
                                                href="http://www.districatolicas.com/tienda/productos/visitar/16736">
                                                Penitencia por Amor Extraordinaria, Para sanar desde el vientre materno
                                                tu mente, tu cuerpo y tu… </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>23.400 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="col-item">

                                <div class="sale-label sale-top-right">Oferta</div>


                                <div class="product-image-area">
                                    <a class="product-image" title="Kempis Imitación de Cristo - Pequeño"
                                        href="http://www.districatolicas.com/tienda/productos/visitar/13352/kempis-imitacion-de-cristo--pequeno">
                                        <div
                                            style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                                            <img src="http://www.districatolicas.com/tienda/uploads/2018/10/500px_8d24237337aed121b5f4217dfcb89f58.jpg"
                                                class="img-responsive" alt="Imagen producto"
                                                onerror="this.src='http://www.districatolicas.com/tienda/resources/images/app/262px_producto.png'"
                                                style="">
                                        </div>
                                    </a>
                                    <div class="hover_fly">
                                        <a class="exclusive ajax_add_to_cart_button" href=""
                                            title="Agregar al carrito de compras" data-producto_id="13352">
                                            <div>
                                                <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="info-inner">
                                        <div class="item-title">
                                            <a title="Kempis Imitación de Cristo - Pequeño"
                                                href="http://www.districatolicas.com/tienda/productos/visitar/13352/kempis-imitacion-de-cristo--pequeno">
                                                Kempis Imitación de Cristo - Pequeño </a>
                                        </div>
                                        <!--item-title-->
                                        <div class="item-content">
                                            <div class="price-box">
                                                <span class="old-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>11.800 </span>
                                                </span>
                                                <p class="special-price">
                                                    <span class="price">
                                                        <span class="currency_symbol">$</span>10.050 </span>
                                                </p>
                                            </div>
                                        </div>
                                        <!--item-content-->
                                    </div>
                                    <!--info-inner-->

                                    <div class="clearfix"> </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="toolbar">
                        <div class="pager">
                            <div class="pages">
                                <ul class="pagination">
                                    <li class="active"><a href="#">1</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=9"
                                            data-ci-pagination-page="9">2</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=18"
                                            data-ci-pagination-page="18">3</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=27"
                                            data-ci-pagination-page="27">4</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=36"
                                            data-ci-pagination-page="36">5</a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=9"
                                            data-ci-pagination-page="9"><i class="fa fa-caret-right"></i></a></li>
                                    <li class=""><a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;per_page=3384"
                                            data-ci-pagination-page="3384"><i class="fa fa-step-forward"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <aside class="col-left sidebar col-sm-3 col-xs-12 col-sm-pull-9 wow bounceInUp animated animated animated"
                style="visibility: visible;">

                <div class="side-nav-categories">
                    <div class="block-title"> Temas y Categorías </div>
                    <!--block-title-->
                    <!-- BEGIN BOX-cateGORY -->
                    <div class="box-content box-category">
                        <ul>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=431">Accesorios</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=516">Cruces
                                            de Madera</a> </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=494">Denarios</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=432">Escapularios</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=523">Medallería</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=454">Promociones
                                            y Kits</a> </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=301">Adoración al
                                    Santísimo</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=353">Biblia</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=608">Bolsos,
                                    Mochilas, Billeteras, Almohadas</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=525">Calendarios</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=449">Camándulas</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=354">Camisetas</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=517">Cuadernos</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=305">Divina
                                    Voluntad</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=450">Espíritu
                                    Santo</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=308">Espiritualidad</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=520">Esquelas</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=428">Estampas</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=356">Familia</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=433">Adolescencia</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=310">Infancia</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=434">Matrimonio</a>
                                    </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=437">Formación</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=303">Catequésis</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=306">Documentos
                                            Apostólicos</a> </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=355">Liturgia</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=309">Revistas
                                            Católicas</a> </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=499">Salmos</a>
                                    </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=497">Imágenes
                                    Religiosas</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=312">Liberación y
                                    Sanación</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=429">Exorcismos</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=311">Liberación</a>
                                    </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=451">Misericordia</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=614">Música</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=313">Novenas</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=314">Oraciones y
                                    Meditaciones</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=315">Otros
                                    temas</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=302">Apariciones
                                            y Revelaciones</a> </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=318">Profecías</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=319">Purgatorio
                                            y Paraíso</a> </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=316">Papa
                                    Francisco</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=317">Perdón</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=446">Pocillos y
                                    Mugs</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=619">Prendedores</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=627">Regalos</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=448">Sectas y
                                    Nueva Era</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=447">Semana
                                    Santa</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=320">Superación y
                                    valores</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=304">Devociones</a>
                                    </li>
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=307">Duelo</a>
                                    </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=529">Tarjetas y
                                    Bolsas</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=519">Velas y
                                    veladoras</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=425">Vida de
                                    Santos</a>
                                <span class="subDropdown plus"></span>

                                <ul class="">
                                    <li class="">
                                        <a
                                            href="http://www.districatolicas.com/tienda/productos/catalogo/?&amp;tag=321">Vida
                                            de santos</a> </li>
                                </ul>
                                <!--level0-->
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=573">Vinos</a>

                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=322">Virgen
                                    María</a>

                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?tag=323">Vocaciones</a>

                            </li>
                        </ul>
                    </div>
                    <!--box-content box-category-->
                </div>
                <div class="side-nav-categories">
                    <div class="block-title"> Editoriales y Marcas </div>
                    <!--block-title-->
                    <!-- BEGIN BOX-CATEGORY -->
                    <div class="box-content box-category">
                        <ul>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=607">Alma
                                    Mater</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=336">AMS</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=337">Apostolado
                                    Bíblico Católico</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=500">Apóstoles de
                                    la Palabra</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=540">Botonia</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=617">Canelo </a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=616">Cerería
                                    Nacional</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=438">Conferencia
                                    Episcopal</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=528">Corazón de
                                    Papel</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=606">Dipal</a>
                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=338">Districatólicas</a>
                            </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=531">Distritexto</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=430">Edgar
                                    Rodriguez</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=592">Fundación
                                    Creo</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=436">Fundación
                                    Verbo Divino</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=521">Gratia
                                    Plena</a> </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=541">Importado</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=522">José Luis
                                    Pivel</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=612">Joyas
                                    Católicas</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=571">La
                                    Tinaja</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=591">Lazos de
                                    Amor Mariano</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=530">Lecat</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=526">LeHogar</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=342">Librería
                                    Espiritual</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=343">Librería
                                    Inmaculada</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=453">Librería
                                    Minuto de Dios</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=578">Línea
                                    Mediterranea</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=344">Luis
                                    Hernández</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=527">Luz y Vida -
                                    Juegos</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=610">Marcela
                                    García</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=532">Marta
                                    Cárdenas</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=513">Minutos de
                                    Amor</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=345">Movimiento
                                    llama de amor</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=580">Mundo
                                    Artesanal</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=613">Nidia
                                    Rojas</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=587">P. José
                                    Eugenio Hernández B.</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=593">Padre
                                    Hernando Pinilla Rey</a> </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=483">Palabritas</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=346">Paulinas</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=347">Planeta</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=618">Prendedores
                                    FG</a> </li>
                            <li class="">
                                <a
                                    href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=498">Produvelas</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=435">San Alfonso
                                    María</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=348">San
                                    Pablo</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=615">SantaLú</a>
                            </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=579">Sociedad
                                    Bíblica Colombiana</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=588">Taller del
                                    Carpintero</a> </li>
                            <li class="">
                                <a href="http://www.districatolicas.com/tienda/productos/catalogo/?fab=539">Velas María
                                    Emma</a> </li>
                        </ul>
                    </div>
                    <!--box-content box-category-->
                </div>
            </aside>
        </div>

    </div>
</div>