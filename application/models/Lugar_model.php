<?php

class Lugar_model extends CI_Model{

    function __construct(){
        parent::__construct();
        
    }
    
    function basico($lugar_id)
    {
        
        $this->db->where('id', $lugar_id);
        $query = $this->db->get('lugar');
        
        $row = $query->row();
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_lugar;
        $basico['vista_a'] = 'sistema/lugares/lugar_v';
        
        return $basico;
    }
    
//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar($num_pagina)
    {
        //Data inicial, de la tabla
            $data = $this->data_tabla_explorar($num_pagina);
        
        //Elemento de exploración
            $data['controlador'] = 'lugares';                      //Nombre del controlador
            $data['carpeta_vistas'] = 'sistema/lugares/explorar/';         //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Lugares';
                
        //Otros
            $data['cant_resultados'] = $this->Lugar_model->cant_resultados($data['busqueda']);
            $data['max_pagina'] = ceil($this->Pcrn->si_cero($data['cant_resultados'],1) / $data['per_page']) - 1;   //Cantidad de páginas, menos 1 por iniciar en cero

        //Vistas
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'menu_v';
        
        return $data;
    }
    
    /**
     * Array con los datos para la tabla de la vista de exploración
     * 
     * @param type $num_pagina
     * @return string
     */
    function data_tabla_explorar($num_pagina)
    {
        //Elemento de exploración
            $data['cf'] = 'lugares/explorar/';     //CF Controlador Función
        
        //Paginación
            $data['num_pagina'] = $num_pagina;              //Número de la página de datos que se está consultado
            $data['per_page'] = 12;                          //Cantidad de registros por página
            $offset = $num_pagina * $data['per_page'];      //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->buscar($data['busqueda'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');               //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    /**
     * Devuelve query según los filtros de la $busqueda
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) 
                {
                    $this->db->like('nombre_lugar', $palabra);
                }
            }
            
        //Otros filtros
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }      //Tipo de lugar
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }   //Condición especial
            
        //Orden
            if ( strlen($busqueda['o']) > 0 ) 
            {
                $ot = $this->Pcrn->si_strlen($busqueda['ot'], 'ASC');   //Tipo de orden (Order type)
                $this->db->order_by($busqueda['o'], $ot);
            }
            $this->db->order_by('poblacion', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('lugar'); //Resultados totales
        } else {
            $query = $this->db->get('lugar', $per_page, $offset); //Resultados por página
        }
        
        return $query;   
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $busqueda
     * @return type
     */
    function cant_resultados($busqueda)
    {
        $resultados = $this->Lugar_model->buscar($busqueda); //Para calcular el total de resultados
        return $resultados->num_rows();
    }
    
//FUNCIONES CRUD
//---------------------------------------------------------------------------------------------------
    
    function eliminar($lugar_id)
    {
        //Tabla principal
            $this->db->where('id', $lugar_id);
            $this->db->delete('lugar');
    }
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('lugar');
        $crud->set_subject('lugar');
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        $crud->unset_back_to_list();

        //Títulos de los campos
            $crud->display_as('nombre_lugar', 'Nombre');
            $crud->display_as('palabras_clave', 'Nombres similares');
            
            
        //Campos
            $crud->add_fields(
                'nombre_lugar',
                'palabras_clave',
                'activo'
            );
            
            $crud->edit_fields(
                'nombre_lugar',
                'palabras_clave',
                'cod_oficial',
                'poblacion',
                'activo'
            );

        //Reglas de validación
            $crud->required_fields('nombre_lugar');
            
        //Formato
            $crud->field_type('activo', 'dropdown', array(0 => 'No', 1 => 'Sí'));
            
        //Procesos
            $crud->callback_after_insert(array($this, 'after_save_lugar'));
            $crud->callback_after_update(array($this, 'after_save_lugar'));
            
        
        $output = $crud->render();
        
        return $output;
    }
    
    function after_save_lugar($post_array,$primary_key)
    {
        $this->act_campos_calculados($primary_key);
        $this->act_nombres_dependientes($primary_key);
    }
    
    function arr_tipo_lugar()
    {
        $arr_tipo_lugar = array(
            1 => 'Continente',
            2 => 'País',
            3 => 'Departamento/Estado',
            4 => 'Ciudad'
        );
        
        return $arr_tipo_lugar;
    }
    
    
    
    /**
     * Sub-lugares que contiene un lugar
     * @param type $lugar_id
     * @return type
     */
    function condicion_sublugares($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $campos_ref = array(
            1 => 'continente_id',
            2 => 'pais_id',
            3 => 'region_id',
            4 => 'region_id'    //Experimental
        );
        
        $tipo_id = $row_lugar->tipo_id + 1;   //Aumentar nivel para filtrar sublugares
        
        $condicion = "{$campos_ref[$row_lugar->tipo_id]} = {$lugar_id} AND tipo_id = {$tipo_id}";
        
        return $condicion;
    }
    
    function guardar($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        //Construir registro
            $registro['nombre_lugar'] = $this->input->post('nombre_lugar');
            $registro['palabras_clave'] = $this->input->post('palabras_clave');
            $registro['slug'] = $this->Pcrn->slug_unico($this->input->post('nombre_lugar'), 'lugar');
            $registro['tipo_id'] = $row_lugar->tipo_id + 1;
            $registro['continente_id'] = $row_lugar->continente_id;
            $registro['pais_id'] = $row_lugar->pais_id;
            $registro['region_id'] = $row_lugar->region_id;
            $registro['ciudad_id'] = $row_lugar->ciudad_id;
            $registro['pais'] = $row_lugar->pais;
            $registro['region'] = $row_lugar->region;
            
        //Condición
            $condicion = "nombre_lugar = '{$registro['nombre_lugar']}' AND tipo_id = {$registro['tipo_id']}";
            
        //Guardar
            $nuevo_lugar_id = $this->Pcrn->insertar_si('lugar', $condicion, $registro);
            
        //Actualizar campos adicionales
            $this->act_campo_id($nuevo_lugar_id);
            $this->act_campos_calculados($nuevo_lugar_id);
            
        return $nuevo_lugar_id;
        
    }
    
    /**
     * Actualiza un campo de la tabla, según el tipo de lugar
     * 
     * Si es ciudad actualiza cuidad_id = id
     * Si es pais pais_id = id
     * Si es region: region_id = id
     * 
     * @param type $lugar_id
     */
    function act_campo_id($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        $nombre_campo = $this->campo_id($row_lugar->tipo_id);
        
        $registro[$nombre_campo] = $row_lugar->id;
        
        $this->db->where('id', $row_lugar->id);
        $this->db->update('lugar', $registro);
    }
    
    /**
     * Actualiza los campos dependientes
     * pais y region, a partir de pais_id y region_id
     * 
     * @param type $lugar_id
     */
    function act_campos_calculados($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $registro['slug'] = $this->Pcrn->slug_unico($row_lugar->nombre_lugar, 'lugar');
        $registro['pais'] = $this->Pcrn->campo_id('lugar', $row_lugar->pais_id, 'nombre_lugar');
        $registro['region'] = $this->Pcrn->campo_id('lugar', $row_lugar->region_id, 'nombre_lugar');
        
        $this->db->where('id', $lugar_id);
        $this->db->update('lugar', $registro);
    }
    
    function act_nombres_dependientes($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $tipos_dependietnes = array(2,3);
        
        if ( in_array($row_lugar->tipo_id, $tipos_dependietnes) ) {
            $nombre_campo = $this->campo_nombre_dependiente($row_lugar->tipo_id);
            $campo_id = $this->campo_id($row_lugar->tipo_id);
            
            $registro[$nombre_campo] = $this->Pcrn->campo_id('lugar', $lugar_id, 'nombre_lugar');
            
            
            $this->db->where($campo_id, $lugar_id);
            $this->db->update('lugar', $registro);
        }
    }
    
    function campo_id($tipo_id)
    {
        $campos_ref = array(
            1 => 'continente_id',
            2 => 'pais_id',
            3 => 'region_id',
            4 => 'ciudad_id'    //Experimental
        );
        
        return $campos_ref[$tipo_id];
    }
    
    function campo_nombre_dependiente($tipo_id)
    {
        $campos_ref = array(
            2 => 'pais',
            3 => 'region',
        );
        
        return $campos_ref[$tipo_id];
    }
    
    function sublugares($lugar_id)
    {
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['condicion'] = $this->condicion_sublugares($lugar_id);
            
        $resultados = $this->Lugar_model->buscar($busqueda);
        
        return $resultados;
    }
    
    function fletes($lugar_id, $campo = 'destino_id')
    {
        $this->db->select('*');
        $this->db->where($campo, $lugar_id);
        $this->db->order_by('', 'ASC');
        $query = $this->db->get('flete');
        
        return $query;
    }
    
    function titulo_sublugares($tipo_id)
    {
        $titulos = array(
            1 => 'Países',
            2 => 'Estados',
            3 => 'Ciudades',
            4 => 'Sectores'
        );
        
        return $titulos[$tipo_id];
    }
}