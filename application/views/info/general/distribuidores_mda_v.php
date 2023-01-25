<style>
.table thead {
    background-color: #41ade2;
    color: white;
}
</style>

<?php
    $cl_col['sm'] = 'hidden-md hidden-lg hidden-xl';
    $cl_col['lg'] = 'hidden-xs hidden-sm';
    $cl_col['all'] = '';
?>

<div id="distribuidoresApp" class="mb-2">
    <div class="page-title mb-2">
        <h2 class="title text-center">Distribuidores Revista Minutos de Amor</h2>
        <p class="text-center">Encuentra a tu distribuidor más cercano ubicándolo por departamento. Gracias por ser parte de esta obra de Evangelización.</p>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group <?= $cl_col['lg'] ?>">
                <a class="list-group-item pointer" v-for="departamento in departamentos" v-bind:class="{'active': departamento == currDepartamento }"
                    v-on:click="setCurrDepartamento(departamento)"
                >
                    {{ departamento }}
                </a>
            </div>
            <select v-model="currDepartamento" class="form-control input-lg mb-2 <?= $cl_col['sm'] ?>">
                <option v-bind:value="departamento" v-for="departamento in departamentos">{{ departamento }}</option>
            </select>
        </div>
        <div class="col-md-9">

            <table class="table table-hover table-responsive">
                <thead>
                    <th class="<?= $cl_col['all'] ?>">Ciudad/Municipio</th>
                    <th class="<?= $cl_col['sm'] ?>">Información</th>
                    <th class="<?= $cl_col['lg'] ?>">Nombre</th>
                    <th class="<?= $cl_col['lg'] ?>">Teléfono</th>
                </thead>
                <tbody>
                    <tr v-for="(distribuidor, d_key) in list" v-show="distribuidor.departamento == currDepartamento">
                        <td class="<?= $cl_col['all'] ?>">{{ distribuidor.ciudad }}</td>
                        <td class="<?= $cl_col['sm'] ?>">
                            <b>{{ distribuidor.nombre }}</b>
                            <br>
                            <span class="text-muted">Tel.</span>
                            <b>{{ distribuidor.telefono }}</b>
                        </td>
                        <td class="<?= $cl_col['lg'] ?>">
                            {{ distribuidor.nombre }}
                        </td>
                        <td class="<?= $cl_col['lg'] ?>">
                            {{ distribuidor.telefono }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var distribuidoresApp = new Vue({
    el: '#distribuidoresApp',
    created: function() {
        this.setDepartamentos()
    },
    data: {
        list: <?= $list ?>,
        currDepartamento: 'Bogotá D.C.',
        departamentos: [],
    },
    methods: {
        setDepartamentos: function() {
            this.list.forEach(distribuidor => {
                if (!this.departamentos.includes(distribuidor.departamento)) {
                    this.departamentos.push(distribuidor.departamento)
                }
            });
        },
        setCurrDepartamento: function(value){
            this.currDepartamento = value
        },
    }
});
</script>