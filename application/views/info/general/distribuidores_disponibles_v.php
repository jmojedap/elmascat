<style>
    .table thead{
        background-color: #41ade2;
        color: white;
    }

    .portada {
        border: 1px solid #CCC;
        border-radius: 3px;
        -webkit-box-shadow: 10px 10px 5px 0px rgba(219,219,219,1);
        -moz-box-shadow: 10px 10px 5px 0px rgba(219,219,219,1);
        box-shadow: 10px 10px 5px 0px rgba(219,219,219,1);
    }

    .portada:hover {
        border: 1px solid #AAA;
    }
</style>

<?php
    $cl_col['sm'] = 'hidden-md hidden-lg hidden-xl';
    $cl_col['lg'] = 'hidden-xs hidden-sm';
    $cl_col['all'] = '';
?>

<div id="dd_app" class="mb-2">
    <div class="page-title mb-2">
        <h2 class="title">Revista Minutos de Amor &middot; Abril 2020</h2>
    </div>
    <div class="row">
        <div class="col-md-3 <?= $cl_col['lg'] ?>">
            <a href="http://www.districatolicas.com/tienda/productos/detalle/17149">
                <img class="portada mb-2" src="http://www.districatolicas.com/tienda/uploads/2020/03/500px_401467203e907e8c5a5d416dbddadbfe.jpg" alt="Portada Revista Minutos de Amor Abril 2020" style="width: 100%">  
            </a>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        Debido a la emergencia de salud pública provocada por la pandemia del 
                        <b class="resaltar">COVID-19</b> y las medidas de aislamiento requeridas para su prevención se ha dificultado para nuestros
                        lectores la adquisición de la edición de <b class="resaltar">Abril de 2020</b> del Manual de Oración 
                        <b class="resaltar">Minutos de Amor</b>.
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        Por tanto puede realizar la compra de la revista en esta página web, haciendo click
                        <a href="http://www.districatolicas.com/tienda/productos/detalle/17149" class="resaltar">
                            >AQUÍ<
                        </a>. Se entregará a domicilio sujeto al tiempo de las empresas transportadoras.
                    </p>
                    <p>
                        Además, en algunas ciudades del país, puede solicitarse la revista a domicilio
                        con los <b class="resaltar">distribuidores</b> que se muestran a continuación.
                    </p>
                </div>
            </div>
            <table class="table table-hover table-responsive">
                <thead>
                    <th class="<?= $cl_col['all'] ?>">Ciudad</th>
                    <th class="<?= $cl_col['sm'] ?>">Información</th>
                    <th class="<?= $cl_col['lg'] ?>">Sector</th>
                    <th class="<?= $cl_col['lg'] ?>">Contacto</th>
                    <th class="<?= $cl_col['lg'] ?>">Teléfono</th>
                </thead>
                <tbody>
                    <tr v-for="(distribuidor, d_key) in list">
                        <td class="<?= $cl_col['all'] ?>">{{ distribuidor.city }}</td>
                        <td class="<?= $cl_col['sm'] ?>">
                            <b>{{ distribuidor.contact_name }}</b>
                            <br>
                            <span class="text-muted">Tel.</span>
                            <b>{{ distribuidor.phone_number }}</b>
                            <br>
                            {{ distribuidor.zone }}
                        </td>
                        <td class="<?= $cl_col['lg'] ?>">{{ distribuidor.zone }}</td>
                        <td class="<?= $cl_col['lg'] ?>">
                            {{ distribuidor.contact_name }}
                        </td>
                        <td class="<?= $cl_col['lg'] ?>">
                            {{ distribuidor.phone_number }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#dd_app',
        created: function(){
            //this.get_list();
        },
        data: {
            list: <?= $list ?>
        },
        methods: {
            
        }
    });
</script>

