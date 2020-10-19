<div id="geoplugin_app">
    {{ location }}
</div>

<script>
    new Vue({
        el: '#geoplugin_app',
        created: function(){
            this.get_location();
        },
        data: {
            ip_address: '<?= $this->input->ip_address(); ?>',
            location: {}
        },
        methods: {
            get_location: function(){
                console.log('localizando', this.ip_address);
                axios.get('http://www.geoplugin.net/json.gp')
                .then(response => {
                    this.location = response.data
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>