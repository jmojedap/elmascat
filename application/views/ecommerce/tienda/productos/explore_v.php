<div id="app_explore">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('ecommerce/tienda/productos/search_form_v') ?>
        </div>
        <div class="col-md-9 mb-2">
            <?php $this->load->view($views_folder . 'list_v'); ?>
            <div class="mt-2">
                <a class="btn text-muted">
                    {{ search_num_rows }} resultados &middot; Pág {{ num_page }} / {{ max_page }}
                </a>
                <?php $this->load->view('common/vue_pagination_v'); ?>
            </div>
        </div>
    </div>

    <?php $this->load->view($views_folder . 'detail_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>