

<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div class="catalog" v-show="!loading">
    <div class="card product" v-for="(element, key) in list" v-bind:id="`row_` + element.id">
        <a v-bind:href="`<?= base_url("tienda/producto/") ?>` + element.id + `/` + element.slug">
            <img
                v-bind:src="element.url_thumbnail"
                class="card-img-top"
                alt="imagen producto"
                onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'"
            >
        </a>
        <div class="card-body">
            <h5>
                <a class="product-name" v-bind:href="`<?= base_url("tienda/producto/") ?>` + element.id + `/` + element.slug">
                    {{ element.name }}
                </a>
            </h5>
            <h6 class="catalog-price text-center">$ {{ element.price | currency }}</h6>
        </div>
    </div>
</div>