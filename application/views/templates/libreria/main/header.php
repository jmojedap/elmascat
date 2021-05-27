<div class="header fixed-top">
    <div class="header-content">
        <div class="header-logo only-lg">
            <img src="<?= URL_BRAND ?>logo-front.png" alt="Logo districatólicas">
        </div>
        <div class="search-form">
            <div class="input-group">
                <input name="q" type="text" class="form-control" id="search-input" required title="Buscar..."
                    placeholder="Buscar..." v-model="form_values.q">
                <div class="input-group-append">
                    <button class="btn btn-warning" type="button"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="tools-cart only-lg">
            <a class="btn btn-cart" href="<?= base_url('pedidos/carrito') ?>">
                <i class="fa fa-shopping-cart"></i>
                <span class="order_qty_items">9</span>
            </a>
        </div>
        <div class="tools-whatsapp only-lg">
            <a class="btn btn-wa" title="Consultar por WhatsApp" href="https://wa.me/573013054053" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
        <div class="tools-user only-lg">
            <button class="btn btn-block">
                <i class="fa fa-user-circle mr-2"></i> <span class="only-lg">Mauricio</span>
            </button>
        </div>
        <button class="btn-navbar only-sm" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</div>