<script>
    /*var form_values = {
        post_name: 'Asunto por definir',
        excerpt: 'Este es el texto de la anotación, no tiene que ser tan larga pero si al menos dejar constancia de la situación, se hizo a las <?= date('Y-m-d H:i:s') ?>',
        cat_1: ''
    }*/
    var form_values = {
        precio: '',
        producto_id: '',
        nota: '',
    }

// Variables
//-----------------------------------------------------------------------------
    var arr_extra_types = <?= json_encode($arr_extra_types); ?>;

// Filtros
//-----------------------------------------------------------------------------

    Vue.filter('extra_name', function (value) {
        if (!value) return '';
        value = arr_extra_types[value];
        return value;
    });

    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = new Intl.NumberFormat('es-CO', {
            style: 'currency', currency: 'COP', minimumFractionDigits: 0
        }).format(value);
        return value;
    });

    new Vue({
        el: '#app_extras',
        created: function(){
            this.get_list();
        },
        data: {
            row_key: -1,
            row_id: 0,
            edition_id: 0,
            list: [],
            num_page: 1,
            max_page: 1,
            pedido_id: '<?= $row->id ?>',
            form_values: form_values,
            show_add: false,
            editable: <?= $editable; ?>
        },
        methods: {
            get_list: function(){
                axios.post(url_app + 'pedidos/extras_get/' + this.pedido_id, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    console.log(this.num_page);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Cargar el formulario con datos de un elemento (key) de la list
            set_form: function (key){
                console.log('set_Form');
                this.set_current(key);
                this.set_show_form(true);
                this.row_id = this.list[key].id;
                this.edition_id = this.list[key].id;
                
                //this.form_values = this.list[key];
                this.form_values.producto_id = '0' + this.list[key].producto_id;
                this.form_values.precio = this.list[key].precio;
                this.form_values.nota = this.list[key].nota;
                
                //$('#edition_extra_' + this.row_id).append($("#extra_form"));
                //document.getElementById("field-precio").focus();
            },
            send_form: function(){
                axios.post(url_app + 'pedidos/extras_save/', $('#extra_form').serialize())
                .then(response => {
                    console.log(response.data);
                    
                    if ( response.data.status == 1 ) 
                    {
                        toastr['success']('Guardado');
                        this.get_list();
                        this.cancel_edition();
                        this.set_show_form(false);
                        this.row_id = response.data.saved_id;
                    }
                    
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            //Establece un elemento como el actual
            set_current: function(key) {
                this.row_id = this.list[key].id;
                this.row_key = key;
            },
            delete_element: function() {
                axios.get(url_app + 'pedidos/extras_delete/' + this.pedido_id + '/' + this.row_id)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.status == 1 )
                    {
                        $('#extra_' + this.row_id).hide('slow');
                        toastr['info']('Eliminado');
                        this.search_num_rows -= 1;
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                $('#new_extra').append($("#extra_form"));
                for ( key in this.form_values ) {
                    this.form_values[key] = '';
                }
                this.form_values.producto_id = '';
                this.form_values.precio = '';
                this.form_values.nota = '';
            },
            cancel_edition: function(){
                this.edition_id = 0;
                this.row_id = 0;
                this.row_key = -1;
                this.clean_form();
                this.set_show_form(false);
            },
            set_show_form: function(value){
                this.show_add = value;
                //$('#new_extra').append($("#extra_form"));
            },
        }
    });
</script>