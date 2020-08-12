<?php
    $arr_meta_productos = array(
        /*array('nombre_corto' => 'Junio 2020', 'qty_months' => '1', 'descripcion_corta' => 'Edición de Junio de 2020'),*/
        array('nombre_corto' => '3 meses', 'qty_months' => '3', 'descripcion_corta' => 'Junio, Julio y Agosto de 2020'),
        array('nombre_corto' => '6 meses', 'qty_months' => '6', 'descripcion_corta' => 'Junio a Noviembre de 2020'),
        array('nombre_corto' => '12 meses', 'qty_months' => '12', 'descripcion_corta' => 'Junio de 2020 a Mayo de 2021')
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
    new Vue({
        el: '#app_digitales',
        created: function(){
            this.set_current(0);
        },
        data: {
            producto_key: 1,
            producto_id: 0,
            producto_actual: {},
            productos: <?php echo json_encode($arr_productos) ?>
        },
        methods: {
            set_current: function(key){
                this.producto_key = key;
                this.producto_id = this.productos[this.producto_key].id;
                this.producto_actual = this.productos[this.producto_key];
            },
            unset_current: function(){
                this.producto_id = 0;
                this.producto_actual = {}; 
            },
            agregar_producto: function(){
                var params = new FormData();
                params.append('producto_id', this.producto_id);
                params.append('cantidad', 1);
                
                axios.post(app_url + 'pedidos/guardar_detalle/', params)
                .then(response => {
                    if ( response.data > 0 ) {
                        window.location = app_url + 'pedidos/usuario/';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>