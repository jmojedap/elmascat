<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('nice_num_page', function (value) {
        if (!value) return '';
        var nice_num_page = value - 1;
        if ( nice_num_page <= 0 ) nice_num_page = 'P';
        return nice_num_page;
    });

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_book',
        created: function(){
            this.get_list();
        },
        data: {
            key_page: 0,
            pages: <?= json_encode($pages); ?>,
            drive_pages: <?= $drive_pages; ?>,
            page: '',
            book_index: <?= $book_index ?>,
            index_key: 0,
            current_index: {},
            mode: 'page',
            show_index_detail: false,
        },
        methods: {
            get_list: function(){
                //this.page = this.pages[0];
                this.page = this.drive_pages[0];
            },
            change_page: function(sum){
                this.key_page = parseInt(this.key_page) + sum;
                if ( this.key_page < 0 ) { this.key_page = 0; }
                if ( this.key_page >= this.drive_pages.length ) { this.key_page = parseInt(this.drive_pages.length) - 1; }
                this.page = this.drive_pages[this.key_page]; 
                this.set_mode('page');
            },
            set_mode: function(value){
                this.mode = value;
            },
            set_page: function(key_page){
                this.key_page = key_page;
                //this.page = this.pages[this.key_page];
                this.page = this.drive_pages[this.key_page];
                console.log(this.page.src);
                this.set_mode('page');
            },
            set_index: function(index_key){
                this.index_key = 0;
                this.current_index = this.book_index[index_key];
                console.log(this.current_index.num_page);
                this.set_page(this.current_index.num_page);
            },
            toggle_index_detail: function(){
                this.show_index_detail = !this.show_index_detail;  
            }
        }
    });
</script>