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
                <button class="btn pull-left" title="Mostrar filtros avanzados" type="button"
                    v-on:click="toggleShowFilters" v-show="windowWidth < 990" v-bind:class="{'btn-info': showFilters }">
                    <i class="fa fa-sliders-h"></i>
                </button>
                <div class="text-right">
                    <i v-show="loading" class="fa fa-spin fa-spinner"></i>
                    <a class="btn text-muted">
                        <b class="text-danger" v-show="!loading">{{ search_num_rows }}</b> resultados &middot; p.
                        {{ num_page }}/{{ max_page }}
                    </a>
                    <?php $this->load->view('common/bs3/vue_pagination_v'); ?>
                </div>
            </div>
            <?php $this->load->view($views_folder . 'list_v'); ?>
        </div>
    </div>

    <?php //$this->load->view($views_folder . 'detail_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>