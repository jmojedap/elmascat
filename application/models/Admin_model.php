<?php

class Admin_model extends CI_Model{
    
    /* Admin hace referencia a Administración,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación para tareas de administración
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }

// OPCIONES DE LA APLICACION 2019-06-15
//-----------------------------------------------------------------------------

    /** Guarda registro de una opción en la tabla sis_option */
    function save_option($option_id)
    {
        $arr_row = $this->input->post();
        $option_id = $this->Db_model->save('sis_option', "id = {$option_id}", $arr_row);

        return $option_id;
    }

    /**
     * Elimina opción, de la tabla posts.
     */
    function delete_option($option_id)
    {
        $data = array('status' => 0, 'message' => 'La opción no fue eliminada');

        //Tabla post
            $this->db->where('id', $option_id);
            $this->db->delete('sis_option');

        if ( $this->db->affected_rows() > 0 ) {
            $data = array('status' => 1, 'message' => 'Opción eliminada');
        }

        return $data;
    }    

// Otros CRUD
//-----------------------------------------------------------------------------
    
    function crud_tabla($nombre_tabla)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table($nombre_tabla);
        $crud->set_subject($nombre_tabla);  
        $crud->unset_print();
        
        if ( strlen($this->input->post('condicion')) > 0 ) {
            $partes = explode(',', $this->input->post('condicion'));
            $crud->where($partes[0], $partes[1]);
        }
        
        //Otros
            $crud->order_by('id', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_acl($controlador)
    {
        
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('sis_acl');
        $crud->set_subject('recurso');
        $crud->columns('id', 'titulo_recurso', 'recurso', 'tipo_id', 'descripcion', 'roles');
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('controlador', 'ASC');
        
        //Filtro
            if ( ! is_null($controlador) ) { $crud->where('controlador', $controlador); }

        //Form edit
            $crud->edit_fields(
                'controlador',
                'funcion',
                'tipo_id',
                'roles',
                'descripcion',
                'ajuste_pendiente',
                'titulo_recurso',
                'recurso'
            );
            
        //Form edit
            $crud->add_fields(
                'controlador',
                'funcion',
                'tipo_id',
                'roles',
                'descripcion',
                'ajuste_pendiente',
                'titulo_recurso',
                'recurso'
            );
            
        //Tipo
            $opciones_tipo = $this->Item_model->opciones('categoria_id = 72');
            $crud->field_type('tipo_id', 'dropdown', $opciones_tipo);
        
        //Reglas
            $crud->required_fields('controlador', 'recurso');
        
        //Títulos
            $crud->display_as('roles', 'Roles permitidos');
            $crud->display_as('tipo_id', 'Tipo recurso');
            $crud->display_as('funcion', 'Función');
            $crud->display_as('descripcion', 'Descripción');

        return $crud->render();
    }
    
    function crud_item_meta()
    {
        $categoria_id = 2;
        
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('metadato');
        $crud->where('categoria_id', $categoria_id);
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('id', 'ASC');
        $crud->columns('id', 'id_interno', 'item', 'filtro', 'descripcion', 'orden');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Nombre dato');
            $crud->display_as('filtro', 'Filtros');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('id_interno', 'ID Interno');
            $crud->display_as('orden', 'Tipo');
        
        //Relaciones
            

        //Formulario Edit
            $crud->edit_fields(
                    'item',
                    'id_interno',
                    'categoria_id',
                    'filtro',
                    'orden',
                    'descripcion'
                );

        //Formulario Add
            $crud->add_fields(
                    'item',
                    'id_interno',
                    'categoria_id',
                    'filtro',
                    'orden',
                    'descripcion'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item', 'descripcion');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_palabras_clave()
    {
        //Grocery crud
        $categoria_id = 20;
        $this->load->library('grocery_CRUD');
        
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('palabra');
        $crud->where('categoria_id', $categoria_id);   //Ver categoria.id
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('item', 'ASC');
        $crud->columns('id', 'item', 'slug');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Palabra');

        //Formulario Edit
            $crud->edit_fields(
                    'item',
                    'categoria_id'
                );

        //Formulario Add
            $crud->add_fields(
                    'item',
                    'categoria_id'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_listas()
    {
        //Grocery crud
        $categoria_id = 22;
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('lista');
        $crud->where('categoria_id', $categoria_id);   //Ver categoria.id
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('item', 'ASC');
        $crud->columns('id', 'item', 'slug');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Nombre lista');
        
        

        //Formulario Edit
            $crud->edit_fields(
                    'item',
                    'categoria_id',
                    'slug'
                );

        //Formulario Add
            $crud->add_fields(
                    'item',
                    'categoria_id',
                    'slug'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item');
        
        $output = $crud->render();
        
        return $output;
    }
    

    
//CATEGORIAS 21
//---------------------------------------------------------------------------------------------------
    
    function select_categoria()
    {
        $select_categoria = 'id, item AS nombre_categoria, descripcion, filtro, padre_id, slug, ascendencia, orden AS nivel';
        return $select_categoria;
    }
    
    function categorias()
    {
        $this->db->select('id, item AS nombre_categoria, descripcion, filtro, padre_id, slug, ascendencia, orden AS nivel');
        $this->db->order_by('ascendencia', 'ASC');
        $this->db->order_by('item', 'ASC');
        $this->db->where('categoria_id', 21);   //ver categoria.id
        $categorias = $this->db->get('item');
        
        return $categorias;
    }
    
    function guardar_categoria($categoria_id = 0)
    {        
        //Construir registro
            $registro['item'] = $this->input->post('item', TRUE); 
            $registro['descripcion'] = $this->input->post('descripcion', TRUE); 
            $registro['slug'] = $this->input->post('slug', TRUE); 
            $registro['padre_id'] = $this->Pcrn->si_strlen($this->input->post('padre_id', TRUE),NULL); 
            $registro['categoria_id'] = 21;
            $registro['slug'] = $this->Pcrn->slug($this->input->post('item'));
            
        //Guardar
            $condicion = "id = {$categoria_id}";
            $item_id = $this->Pcrn->guardar('item', $condicion, $registro);
            
        //Establecer campo ascendencia
            $this->set_ascendencia($item_id);
            $this->act_descendencia($item_id);
            
        return $item_id;
    }
    
    /**
     * Actualiza el campo item.ascendencia para un item específico
     * 
     * @param type $item_id
     */
    function set_ascendencia($item_id)
    {
        $prefijo = '-';
        $nivel = 1;
        $row_item = $this->Pcrn->registro_id('item', $item_id);
        
        if ( ! is_null($row_item->padre_id) ) {
            $row_padre = $this->Pcrn->registro_id('item', $row_item->padre_id);
            $prefijo = $row_padre->ascendencia;
            $nivel = $row_padre->orden + 1;
        }
        
        $registro['ascendencia'] = $prefijo . $item_id . '-';
        $registro['orden'] = $nivel;
        
        $this->db->where('id', $item_id);
        $this->db->update('item', $registro);
        
    }
    
    /**
     * Actualiza el campo item.ascendencia para toda la descendencia
     * de un item específico, necesaria cuando un item cambia de padre inmediado en la jerarqía
     * 
     * @param type $item_id
     */
    function act_descendencia($item_id)
    {
        $this->db->like("CONCAT('-', (ascendencia), '-')", "-$item_id-");
        $this->db->where('categoria_id', 21);
        $this->db->order_by('ascendencia', 'ASC');
        $items = $this->db->get('item');
        
        foreach ( $items->result() as $row_item ){
            $this->set_ascendencia($row_item->id);
        }
    }
    
    function eliminar_categoria($item_id)
    {
        //Eliminar registro
            $this->db->where('id', $item_id);
            $this->db->delete('item');
            
        //Eliminar descendencia
            $this->db->like("CONCAT('-', (ascendencia), '-')", "-$item_id-");
            $this->db->where('categoria_id', 21);
            $this->db->delete('item');
            
    }
    
    function descendencia($item_id, $formato = 'query')
    {
        $descendencia = NULL;
        $row = $this->Pcrn->registro_id('item', $item_id);
        
        //$this->db->like('ascendencia', "-{$item_id}-");
        $this->db->where("CONCAT('-', ascendencia, '-') LIKE '%-{$item_id}-%'");
        $this->db->where('categoria_id', $row->categoria_id);
        $query = $this->db->get('item');
        
        
        
        if ( $formato == 'query' ) {
            $descendencia = $query;
        } elseif ( $formato == 'array' ) {
            $descendencia = $this->Pcrn->query_to_array($query, 'id');
        } elseif ( $formato == 'string' ) {
            $descendencia = '0';
            $arr_descendencia = $this->Pcrn->query_to_array($query, 'id');
            if ( $query->num_rows() > 0 ) {
                $descendencia = implode(',', $arr_descendencia);
            }
            
        }
        
        return $descendencia;
    }
}