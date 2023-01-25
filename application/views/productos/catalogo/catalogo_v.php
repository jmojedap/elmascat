<div id="app_explore">
    <div class="row">
        <div class="col-md-5">
            
        </div>

        <div class="col-md-3">
            
        </div>
        
        <div class="col-md-4 mb-2 text-right">
            <a class="btn text-muted">
                {{ search_num_rows }} resultados &middot; Pág {{ num_page }} / {{ max_page }}
            </a>
            <?php $this->load->view('common/bs3/vue_pagination_v'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        <div class="col-md-9">
            <?php $this->load->view($views_folder . 'list_v'); ?>
        </div>
    </div>

    <?php $this->load->view($views_folder . 'detail_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>