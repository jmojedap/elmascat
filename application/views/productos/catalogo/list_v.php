<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div v-show="!loading">
    <div class="products-grid-2">
        <div v-for="(product, key) in list">
            <div class="col-item">
                <div class="product-image-area">
                    <a class="product-image" v-bind:title="product.name" v-bind:href="url_app + 'productos/visitar/' + product.id + `/` + product.slug">
                        <div class="product-image-container">
                            <img
                                v-bind:src="product.url_thumbnail"
                                class="img-responsive"
                                alt="Imagen producto"
                                onError="this.src='<?= URL_IMG . 'app/262px_producto.png' ?>'"
                                style=""
                            >
                        </div>
                    </a>
                </div>
                <div class="info">
                    <div class="info-inner">
                        <div class="item-title">
                            <a v-bind:title="product.name" v-bind:href="url_app + 'productos/visitar/' + product.id + `/` + product.slug">
                                {{ product.name.substring(0,64) }}
                            </a> 
                        </div>
                        <!--item-title-->
                        <div class="item-content">
                            <div class="price-box">
                                
                                    <span class="old-price">
                                        <span class="price">
                                            {{ product.price }}
                                        </span>
                                    </span>
                                
                                <p class="special-price">
                                    <span class="price">
                                        {{ product.precio }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <!--item-content--> 
                    </div>
                    <!--info-inner-->

                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
    </div>
</div>