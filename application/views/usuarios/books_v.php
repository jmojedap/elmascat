<?php
    $arr_books = array();

    foreach ($books->result() as $book)
    {
        $att_img = $this->Archivo_model->att_img($book->imagen_id, '500px_');
        $book->img_src = $att_img['src'];
        $book->disponible = $this->pml->ago($book->publicado);
        $book->publicado_nice = $this->pml->date_format($book->publicado, 'M-d');
        $arr_books[] = $book;
    }
?>

<style>
    .cover_book{
        border: 1px solid #DDD;
        max-width: 80px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -webkit-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        -moz-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
    }

    .cover_book:hover{
        border: 1px solid #AAA;
        max-width: 81px;
    }
</style>

<div id="user_books" class="center_box_750">
    <?php if ( $this->session->userdata('role') <= 10 ) { ?>
        <div class="card mb-2">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="books_form" @submit.prevent="add_post" clas="form-horizontal">
                    <div class="form-group row">
                        <label for="book_id" class="col-md-2 col-form-label text-right">Libro</label>
                        <div class="col-md-8">
                            <?= form_dropdown('book_id', $options_book, '', 'required class="form-control" v-model="book_id"') ?>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
                                Agregar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
    <div class="table-responsive">
        <table class="table bg-white">
            <thead>
                <th width="120px"></th>
                <th>Descripción</th>
                <?php if ( $this->session->userdata('role') <= 10 ) : ?>
                    <th width="10px"></th>
                <?php endif; ?>
            </thead>
            <tbody>
                <tr v-for="(book, key) in books">
                    <td>
                        <a v-bind:href="`<?= base_url("books/read/") ?>` + `/` + book.code + `/` + book.meta_id + `/` + book.slug + `/` + book.format" v-show="book.estado == 1">
                            <img
                                v-bind:src="book.img_src"
                                class="cover_book"
                                alt="Carátula libro"
                                onerror="this.src='<?= URL_IMG ?>app/125px_producto.png'"
                            >
                        </a>
                        <img
                            v-bind:src="book.img_src"
                            class="cover_book"
                            alt="Carátula libro"
                            onerror="this.src='<?= URL_IMG ?>app/125px_producto.png'"
                            v-show="book.estado > 1"
                        >
                    </td>
                    <td>
                        <h4>{{ book.title }}</h4>
                        <p>{{ book.resumen }}</p>
                        <p v-show="book.estado > 1">
                            <span class="text-muted">Disponible: </span>
                            <span v-html="book.disponible"></span>
                            (<span class="text-muted">{{ book.publicado_nice }}</span>)
                        </p>
                    </td>
                    <?php if ( $this->session->userdata('role') <= 10 ) { ?>
                        <td>
                            <button class="btn btn-warning btn-sm" v-on:click="remove_post(book.id, book.meta_id)" title="Retirar contenido al usuario">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    <?php } ?>


                    
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    new Vue({
        el: '#user_books',
        created: function(){
            //this.get_list();
        },
        data: {
            user_id: <?= $row->id ?>,
            books: <?= json_encode($arr_books) ?>,
            book_id: 0
        },
        methods: {
            add_post: function(){
                axios.get(url_app + 'posts/add_to_user/' + this.book_id + '/' + this.user_id)
                .then(response => {
                    console.log(response.data)
                    window.location = url_app + 'usuarios/books/' + this.user_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            remove_post: function(book_id, meta_id){
                axios.get(url_app + 'posts/remove_to_user/' + book_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data)
                    window.location = url_app + 'usuarios/books/' + this.user_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>