<?php

class Flete_model extends CI_Model{

    function __construct(){
        parent::__construct();
        
    }

    function basico($flete_id)
    {
        $this->db->where('id', $flete_id);
        $query = $this->db->get('flete');
        
        $row = $query->row();
        $row_destino = $this->Pcrn->registro_id('lugar', $row->destino_id);
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['titulo_pagina'] = 'Flete a: ' . $row_destino->nombre_lugar;
        $basico['vista_a'] = 'comercial/fletes/flete_v';
        $basico['row_destino'] = $this->Pcrn->registro_id('lugar', $row->destino_id);
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        //Construir búsqueda
            $this->db->select('*, flete.id AS flete_id');
        
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $concat_campos = $this->Busqueda_model->concat_campos(array('nombre_lugar', 'region', 'costo_fijo', 'rangos'));
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->order_by('nombre_lugar', 'ASC');
            
        //Otros filtros
            if ( $busqueda['cat'] != '' ) { $this->db->where('origen_id', $busqueda['cat']); }    //Lugar de origen flete, cat
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }    //Condición especial
            
        //Tabla relacionada
            $this->db->join('lugar', 'lugar.id = flete.destino_id');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('flete'); //Resultados totales
        } else {
            $query = $this->db->get('flete', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /** 
     * Devuelve render de formulario grocery crud para la edición
     * de datos de usuario
     */
    function crud_basico($origen_id)
    {
        //Grocery crud//Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('flete');
        $crud->set_subject('flete');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('origen_id', 'Desde');
            $crud->display_as('destino_id', 'Ciudad destino');
            $crud->display_as('costo_fijo', 'Costo 1er kg');
            
        //Relaciones
            $crud->set_relation('destino_id', 'lugar', '{nombre_lugar} - {region}', 'tipo_id = 4');
            
        //Campos
            $crud->edit_fields(
                    'destino_id',
                    'costo_fijo',
                    'rangos'
                );
            
            $crud->add_fields(
                    'origen_id',
                    'destino_id',
                    'costo_fijo',
                    'rangos'
                );
            
        //Reglas
            $crud->required_fields('origen_id', 'destino_id', 'costo_fijo', 'rangos');
            
        //Valores por defecto}
            $crud->field_type('origen_id', 'hidden', $origen_id);
        
        $output = $crud->render();
        
        return $output;
    }
    
//FLETES
//---------------------------------------------------------------------------------------------------
    
    /**
     * Calcula el costo del flete según el peso
     * 2023-05-28
     * 
     * @param int $origen_id ID ciudad de origen del envío
     * @param int $destino_id ID didad de destino del envío
     * @param int $peso peso del envío en kilogramos
     * @return float precio del envío en pesos colombianos COP
     */
    function flete($origen_id, $destino_id, $peso)
    {
        $costo_fijo = $this->costo_fijo($origen_id, $destino_id);
        $costo_variable = $this->costo_variable($origen_id, $destino_id, $peso);
        
        $flete = 0;
        if ( $peso > 0 ) 
        {
            $flete = $costo_fijo + $costo_variable * ($peso - 1);   //Se quita el valor del kilo inicial (-1)
        }
        
        return $flete;
    }
    
    /**
     * Costo fijo de un envío para el primer kilogramo
     * 
     * @param type $origen_id
     * @param type $destino_id
     * @return type
     */
    function costo_fijo($origen_id, $destino_id)
    {
        $costo_fijo = $this->Db_model->field_id('sis_option', 20, 'option_value');
        
        $condicion = "origen_id = {$origen_id} AND  destino_id = {$destino_id}";
        $row_flete = $this->Pcrn->registro('flete', $condicion);
        
        if ( ! is_null($row_flete) ) {
            $costo_fijo = $row_flete->costo_fijo;
        }
        
        return $costo_fijo;
        
    }
    
    /**
     * Calcula el valor del costo variable por Kg, para de un envío
     * @param type $origen_id
     * @param type $destino_id
     * @param type $peso
     * @return type
     */
    function costo_variable($origen_id, $destino_id, $peso)
    {
        //Valor por defecto
            $costo_variable = $this->Db_model->field_id('sis_option', 21, 'option_value');
        
        //Buscando valor
            $array_rangos = $this->array_rangos($origen_id, $destino_id);
            if ( count($array_rangos) > 0 ) 
            {
                $costo_variable = $this->Pcrn->valor_rango($array_rangos, $peso);
            }
        
        return $costo_variable;
    }
    
    function array_rangos($origen_id, $destino_id)
    {
        $array_rangos = array();
        
        $condicion = "origen_id = {$origen_id} AND  destino_id = {$destino_id}";
        $row_flete = $this->Pcrn->registro('flete', $condicion);
        
        if ( ! is_null($row_flete) ) 
        {
            $array_rangos = (array) json_decode($row_flete->rangos);    //(array para devolver array, no un Object)
        }
        
        return $array_rangos;
    }
    
    function origenes()
    {
        $arr_origenes = array(909);
        
        return $arr_origenes;
    }
    
    function origen_id_default()
    {
        $origen_id = 909;   //Bogotá > ver campo lugar.id
        return $origen_id;
    }
    
}