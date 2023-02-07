
<script>
// Variables
//-----------------------------------------------------------------------------
let showFiltersPre = false;
let windowWidth = window.innerWidth;
let advancedFiltersStyle = 'display: none;';
if ( windowWidth > 990 ) {
    console.log(windowWidth);
    showFiltersPre = true;
    advancedFiltersStyle = ''
}

// Filters
//-----------------------------------------------------------------------------
Vue.filter('promocion_name', function (value) {
    if (!value) return ''
    value = promocion_names[value]
    return value
})

Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow()
})

Vue.filter('currency', function (value) {
    if (!value) return ''
    value = new Intl.NumberFormat().format(value)
    return value
})

// App
//-----------------------------------------------------------------------------
var app_explore = new Vue({
    el: '#appCatalogo',
    created: function(){
        this.calculateFiltered()
    },
    data: {
        url_app: url_app,
        cf: 'productos/catalogo/',
        controller: 'productos',
        search_num_rows: <?= $search_num_rows ?>,
        num_page: <?= $num_page ?>,
        max_page: <?= $max_page ?>,
        perPage: <?= $perPage ?>,
        list: <?= json_encode($list) ?>,
        element: [],
        filters: <?= json_encode($filters) ?>,
        showFilters: showFiltersPre,
        filtered: false,
        advancedFiltersStyle: advancedFiltersStyle,
        windowWidth: windowWidth,
        loading: false,
        arrCategorias: <?= json_encode($arrCategorias) ?>,
        arrTags: <?= json_encode($arrTags) ?>,
        arrFabricantes: <?= json_encode($arrFabricantes) ?>,
        arrOrdering: <?= json_encode($arrOrdering) ?>,
        arrRangoPrecio: <?= json_encode($arrRangoPrecio) ?>,
    },
    methods: {
        getList: function(e, num_page = 1){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(url_app + this.controller + '/get_catalogo/' + num_page, formValues)
            .then(response => {
                this.num_page = num_page
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters)
                this.all_selected = false
                this.selected = []
                this.loading = false

                this.calculateFiltered()
            })
            .catch(function (error) { console.log(error) })
        },
        sum_page: function(sum){
            var new_num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page)
            this.getList(null, new_num_page)
        },
        set_current: function(key){
            this.element = this.list[key]
        },
        removeFilter: function(filterName){
            this.filters[filterName] = null;
            setTimeout(() => {
                this.getList();
            }, 100);
        },
        remove_filters: function(){
            this.filters.cat = null
            this.filters.tag = null
            this.filters.fab = null
            this.filters.fe1 = null
            this.filters.fe3 = null
            this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculateFiltered: function(){
            var filtered = false
            if ( this.filters.q ) filtered = true
            if ( this.filters.cat ) filtered = true
            if ( this.filters.tag ) filtered = true
            if ( this.filters.fab ) filtered = true
            if ( this.filters.fe1 ) filtered = true
            if ( this.filters.fe3 ) filtered = true
            if ( this.filters.promo ) filtered = true
            if ( this.filters.d1 ) filtered = true

            this.filtered = filtered
        },
        toggleShowFilters: function(){
            this.showFilters = !this.showFilters
            $('#advanced_filters').toggle('fast');
        },
    }
})
</script>