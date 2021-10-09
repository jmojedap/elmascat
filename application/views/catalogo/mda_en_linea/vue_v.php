<?php
    $arr_meta_productos = array(
        /*array('nombre_corto' => 'Junio 2020', 'qty_months' => '1', 'descripcion_corta' => 'Edición de Junio de 2020'),*/
        array('nombre_corto' => '3 meses', 'qty_months' => '3', 'descripcion_corta' => 'Abril, mayo y junio de 2021'),
        array('nombre_corto' => '6 meses', 'qty_months' => '6', 'descripcion_corta' => 'Abril a septiembre 2021'),
        array('nombre_corto' => '12 meses', 'qty_months' => '12', 'descripcion_corta' => 'Abril de 2021 a Marzo de 2022')
    );

    $arr_productos = array();

    $i = 0;

    foreach ($productos->result() as $row_producto)
    {
        $producto = $arr_meta_productos[$i];
        $att_img = $this->Archivo_model->att_img($row_producto->imagen_id, '500px_');

        $producto['id'] = $row_producto->id;
        $producto['nombre_producto'] = $row_producto->nombre_producto;
        $producto['precio'] = $this->pml->money($row_producto->precio);
        $producto['descripcion'] = $row_producto->descripcion;
        $producto['img_src'] = $att_img['src'];
        $producto['precio_und'] = $this->pml->money($row_producto->precio / $producto['qty_months']);

        $arr_productos[] = $producto;

        $i++;
    }
?>

<script>
var app_digitales = new Vue({
    el: '#app_digitales',
    created: function(){
        this.set_current(0);
    },
    data: {
        order_code: '<?= $this->session->userdata('order_code') ?>',
        producto_key: 1,
        producto_id: 0,
        producto_actual: {},
        productos: <?= json_encode($arr_productos) ?>
    },
    methods: {
        set_current: function(key){
            this.producto_key = key
            this.producto_id = this.productos[this.producto_key].id
            this.producto_actual = this.productos[this.producto_key]
        },
        unset_current: function(){
            this.producto_id = 0
            this.producto_actual = {};
        },
        agregar_producto: function(){
            var params = new FormData()
            params.append('producto_id', this.producto_id)
            params.append('cantidad', 1)
            
            axios.get(url_app + 'pedidos/add_product/' + this.producto_id + '/1/' + this.order_code)
            .then(response => {
                if ( response.data.status == 1 ) {
                    window.location = url_app + 'pedidos/carrito/';
                }
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>