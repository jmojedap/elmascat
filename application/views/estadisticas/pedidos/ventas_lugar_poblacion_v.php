<?php
    $score_1_summary = $this->pml->field_summary($lugares, 'score_1');
    $poblacion_summary = $this->pml->field_summary($lugares, 'poblacion');
?>

<div id="ventas_lugar_poblacion_app">
    <h4 class="text-center">En los últimos 365 días</h4>
    <table class="table bg-white">
        <thead>
            <th width="10px"></th>
            <th>Departamento ({{ lugares.length }})</th>
            <th width="30%"></th>
            <th class="text-center">Ventas / 100 mil hab.</th>
            <th>Pedidos</th>
            <th class="text-center">Población</th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in lugares">
                <td>{{ key + 1 }}</td>
                <td>{{ element.nombre_lugar }}</td>
                <td>
                    <div class="progress mb-1">
                        <div class="progress-bar" role="progressbar" v-bind:style="`width: `+ score_1_percent(element.score_1) +`%;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                            v-bind:class="class_score_1(element.score_1, element.poblacion)"
                        >
                            {{ score_1_percent(element.score_1) }} %
                        </div>
                    </div>
                </td>
                <td class="text-center">$ {{ element.score_1 | currency }}</td>
                <td class="text-right">{{ element.score_2 }}</td>
                <td class="text-center">{{ element.poblacion | currency }}</td>
            </tr>
        </tbody>
    </table>    
</div>

<script>
// Filtros
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return ''
    value = new Intl.NumberFormat().format(value)
    return value
});

// VueApp
//-----------------------------------------------------------------------------
var ventas_lugar_poblacion_app = new Vue({
    el: '#ventas_lugar_poblacion_app',
    created: function(){
        //this.get_list()
    },
    data: {
        lugares: <?= json_encode($lugares->result()) ?>,
        score_1_summary: <?= json_encode($score_1_summary) ?>,
        poblacion_summary: <?= json_encode($poblacion_summary) ?>,
        loading: false,
    },
    methods: {
        score_1_percent: function(score_1){
            return parseInt(100 * score_1/this.score_1_summary.max)
        },
        poblacion_percent: function(poblacion){
            return parseInt(100 * poblacion/this.poblacion_summary.max)
        },
        class_score_1: function(score_1, poblacion){
            var score_1_percent = this.score_1_percent(score_1)
            var poblacion_percent = this.poblacion_percent(poblacion)
            var class_score_1 = 'bg-success';
            if ( poblacion_percent > score_1_percent ) class_score_1 = 'bg-danger'
            return class_score_1
        },

    }
})
</script>