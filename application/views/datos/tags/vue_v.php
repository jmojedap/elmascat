<script>
    new Vue({
        el: '#etiquetas_app',
        created: function(){
            this.get_list();
        },
        data: {
            app_url: '<?= base_url() ?>',
            list: [],
            form_values_new: { nombre_tag: '', descripcion: '', padre_id: '00'},
            form_values: {nombre_tag: '', descripcion: '', padre_id: '00'},
            key: 0,
            element_id: 0
        },
        methods: {
            get_list: function(){
                axios.get(this.app_url + 'datos/get_tags/')
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });   
            },
            set_current: function(key){
                this.key = key;
                this.element_id = this.list[key].id;
            },
            send_form: function(){
                this.save();
            },
            save: function(){
                axios.post(this.app_url + 'datos/save_tag/' + this.element_id, $('#tag_form').serialize())
                .then(response => {
                    $('#modal_form').modal('hide');
                    if ( response.data.status == 1 ) {
                        toastr["success"]('Etiqueta guardada');
                        this.element_id = response.data.tag_id;
                        this.get_list();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            new_element: function(){
                this.element_id = 0;
                this.form_values = this.form_values_new;
                $('#field-item').focus();
            },
            edit_element: function(key){
                this.set_current(key);
                this.form_values = {
                    item: this.list[key].nombre_tag,
                    descripcion: this.list[key].descripcion,
                    padre_id: '0' + this.list[key].padre_id
                }
            },
            delete_element: function(){
                axios.get(this.app_url + 'datos/delete_tag/' + this.element_id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.list.splice(this.key, 1);
                        toastr['info']('Etiqueta eliminada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>