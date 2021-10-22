<div id="files_generator_app">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="book_code" class="col-md-4 col-form-label text-right">Libro</label>
                        <div class="col-md-8">
                            <select name="book_code" v-model="book_code" class="form-control" required>
                                <option v-for="option_book in books" v-bind:value="option_book.code">{{ option_book.nombre_post }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="book_code" class="col-md-4 col-form-label text-right">Código libro</label>
                        <div class="col-md-8">
                            {{ book_code }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prefix" class="col-md-4 col-form-label text-right">Prefijo nombre</label>
                        <div class="col-md-8">
                            <input
                                name="prefix" type="text" class="form-control"
                                title="Prefijo nombre" placeholder=""
                                v-model="prefix"
                            >
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary btn-block" type="button" v-on:click="rename_pages">Renombrar páginas</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary btn-block" type="button" v-on:click="create_files('create_drive')">Crear Drive</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary btn-block" type="button" v-on:click="create_files('create_read')">Crear Read</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary btn-block" type="button" v-on:click="create_files('create_mini')">Crear Mini</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="text-center" v-show="loading">
                <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
            </div>
            <div v-show="!loading">
                <h3>Renombrados ({{ renamed_pages.length }})</h3>
                <table class="table bg-white">
                    <thead>
                        <th>Cambios</th>
                    </thead>
                    <tbody>
                        <tr v-for="renamed in renamed_pages">
                            <td>{{ renamed }}</td>
                        </tr>
                    </tbody>
                </table>
                <h3>Read creados ({{ created.length }})</h3>
                <table class="table bg-white">
                    <thead>
                        <th>Creados</th>
                    </thead>
                    <tbody>
                        <tr v-for="created_file in created">
                            <td>{{ created_file }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var files_generator_app = new Vue({
    el: '#files_generator_app',
    created: function(){
        //this.get_list()
    },
    data: {
        book_code: '<?= $book_code ?>',
        books: <?= json_encode($books->result()) ?>,
        prefix: '',
        step: 1,
        loading: false,
        renamed_pages: [],
        created: [],
    },
    methods: {
        rename_pages: function(){
            console.log('Rename pages')
            this.loading = true
            var form_data = new FormData()
            form_data.append('prefix', this.prefix)
            form_data.append('book_code', this.book_code)
            axios.post(url_api + 'books/rename_pages/', form_data)
            .then(response => {
                this.renamed_pages = response.data.renamed_pages
                this.loading = false
                this.step = 1
            })
            .catch( function(error) {console.log(error)} )
        },
        create_files: function(function_name){
            this.loading = true
            var form_data = new FormData()
            form_data.append('book_code', this.book_code)
            axios.post(url_api + 'books/' + function_name + '/', form_data)
            .then(response => {
                this.created = response.data.created
                this.loading = false
                this.step = 2
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>