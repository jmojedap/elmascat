<?php $this->load->view('assets/revslider') ?>

<div id="appCatalogo">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        <div class="col-md-9">
            <div v-show="!filtered" class="only-lg">
                <?php $this->load->view('widgets/carousel_desktop_v') ?>
            </div>
            <div class="mb-2">
                <button class="btn pull-left btn-info" title="Mostrar filtros avanzados" type="button"
                    v-on:click="toggleShowFilters" v-show="windowWidth < 990">
                    Filtros <i class="fa fa-chevron-down" v-show="!showFilters"></i> <i class="fa fa-chevron-up" v-show="showFilters"></i>
                </button>
                <div class="text-right">
                    <a class="btn text-muted">
                        <b class="text-danger" v-show="!loading">{{ search_num_rows }}</b> res &middot; p.
                        {{ num_page }}/{{ max_page }}
                    </a>
                    <?php $this->load->view('common/bs3/vue_pagination_v'); ?>
                </div>
            </div>
            <?php $this->load->view($views_folder . 'list_v'); ?>  
            <div class="text-right">
                <a class="btn text-muted">
                    <b class="text-danger" v-show="!loading">{{ search_num_rows }}</b> res &middot; p.
                    {{ num_page }}/{{ max_page }}
                </a>
                <?php $this->load->view('common/bs3/vue_pagination_v'); ?>
            </div>
        </div>
    </div>

    <?php //$this->load->view($views_folder . 'detail_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>