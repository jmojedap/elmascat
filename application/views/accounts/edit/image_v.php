<div id="user_image_app" class="center_box_450">
    <div class="card">
        <img
            v-bind:src="user.url_image"
            class="card-img-top"
            alt="User image"
            onerror="this.src='<?= URL_IMG ?>users/user.png'"
        >
        <div class="card-body">
            <div v-show="user.image_id == 0">
                <?php $this->load->view('common/upload_file_form_v') ?>
            </div>
            <div v-show="user.image_id > 0">
                <a class="btn btn-light w120p" id="btn_crop" href="<?= base_url("accounts/edit/crop") ?>">
                    <i class="fa fa-crop"></i> Crop
                </a>
                <button class="btn btn-warning w120p" v-on:click="remove_image">
                    <i class="fa fa-times"></i> Remove
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#user_image_app',
        created: function(){
            //this.get_list();
        },
        data: {
            user: {
                image_id: <?= $row->image_id ?>,
                url_image: '<?= $row->url_image ?>'
            },
            default_image: '<?= URL_IMG ?>users/user.png'
        },
        methods: {
            send_file_form: function(){
                let form_data = new FormData();
                form_data.append('file_field', this.file);

                axios.post(url_api + 'accounts/set_image/', form_data, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    //Cargar imagen
                    if ( response.data.status == 1 )
                    { 
                        this.user.image_id = response.data.image_id;
                        this.user.url_image = response.data.url_image;
                        window.location = url_app + 'accounts/edit/crop';
                    }
                    //Mostrar respuesta html, si existe
                    if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                    //Limpiar formulario
                    $('#field-file').val(''); 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            handle_file_upload(){
                this.file = this.$refs.file_field.files[0];
            },
            remove_image: function(){
                axios.get(url_api + 'accounts/remove_image/')
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.user.image_id = 0;
                        this.user.url_image = this.default_image;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>