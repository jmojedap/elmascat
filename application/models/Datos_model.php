<?php

class Datos_model extends CI_Model{
    
    /* Esp hace referencia a Especial,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación del sitio en casos especiales
     * 
     * Enlace.net.co V3
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//---------------------------------------------------------------------------------------------------
    
    function z_crud_sis_option()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('sis_option');
        $crud->set_subject('opción');
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        $crud->order_by('id', 'ASC');

        //Títulos de los campos
            $crud->display_as('nombre_opcion', 'Nombre opción');

        //Reglas de validación
            $crud->required_fields('id', 'nombre_opcion', 'valor');
        
        $output = $crud->render();
        
        return $output;
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
    
    function crud_extras()
    {
        //Grocery crud
        $categoria_id = 6;
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('extra pedido');
        $crud->where('categoria_id', $categoria_id);   //Ver categoria.id
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('id_interno', 'ASC');
        $crud->columns('id', 'id_interno', 'item', 'item_corto', 'slug');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Elemento extra');
            $crud->display_as('item_corto', 'Nombre corto');
            $crud->display_as('id_interno', 'COD');

        //Formulario Edit
            $crud->edit_fields(
                    'id_interno',
                    'item',
                    'item_corto',
                    'slug',
                    'categoria_id'
                );

        //Formulario Add
            $crud->add_fields(
                    'id_interno',
                    'item',
                    'item_corto',
                    'slug',
                    'categoria_id'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_estado_pedido()
    {
        //Grocery crud
        $categoria_id = 7;
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('estado pedido');
        $crud->where('categoria_id', $categoria_id);   //Ver categoria.id
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('id_interno', 'ASC');
        $crud->columns('id', 'id_interno', 'item', 'item_corto');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Estado pedido');
            $crud->display_as('item_corto', 'Clase Bootstrap');
            $crud->display_as('id_interno', 'COD');

        //Formulario Edit
            $crud->edit_fields(
                    'id_interno',
                    'item',
                    'item_corto',
                    'categoria_id'
                );

        //Formulario Add
            $crud->add_fields(
                    'id_interno',
                    'item',
                    'item_corto',
                    'categoria_id'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_fabricantes()
    {
        //Grocery crud
        $categoria_id = 5;  //Fabricantes
        $this->load->library('grocery_CRUD');
        
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('fabricante');
        $crud->where('categoria_id', $categoria_id);   //Ver categoria.id
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('item', 'ASC');
        $crud->columns('id', 'item', 'entero_1');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('item', 'Nombre fabricante');
            $crud->display_as('entero_1', '% Descuento');

        //Formulario Edit
            $crud->edit_fields(
                    'item',
                    'categoria_id',
                    'entero_1'
                );

        //Formulario Add
            $crud->add_fields(
                    'item',
                    'categoria_id',
                    'entero_1'
                );
        
        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        //Reglas de validación
            $crud->required_fields('item', 'entero_1');
        
        $output = $crud->render();
        
        return $output;
    }
    
//ETIQUETAS, METADATO 21
//---------------------------------------------------------------------------------------------------
    
    function select_tags()
    {
        $select_tag = 'id, item AS nombre_tag, descripcion, filtro, padre_id, slug, ascendencia, orden AS nivel';
        return $select_tag;
    }
    
    function tags()
    {
        $this->db->select($this->select_tags());
        $this->db->order_by('ascendencia', 'ASC');
        $this->db->order_by('item', 'ASC');
        $this->db->where('categoria_id', 21);   //ver categoria.id
        $tags = $this->db->get('item');
        
        return $tags;
    }
    
    function save_tag($tag_id = 0)
    {        
        //Construir registro
            $registro['item'] = $this->input->post('item', TRUE); 
            $registro['descripcion'] = $this->input->post('descripcion', TRUE); 
            $registro['slug'] = $this->input->post('slug', TRUE); 
            $registro['padre_id'] = $this->Pcrn->si_strlen($this->input->post('padre_id', TRUE),NULL); 
            $registro['categoria_id'] = 21;
            $registro['slug'] = $this->Pcrn->slug($this->input->post('item'));
            
        //Guardar
            $condicion = "id = {$tag_id}";
            $item_id = $this->Pcrn->guardar('item', $condicion, $registro);
            
        //Establecer campo ascendencia
            $this->set_ascendencia($item_id);
            $this->act_descendencia($item_id);
            
        return $item_id;
    }

    /**
     * Eliminar etiqueta (tabla item) y datos relacionados
     */
    function delete_tag($item_id)
    {
        //Eliminar registro
            $this->db->where('id', $item_id);
            $this->db->delete('item');

            $quan_deleted = $this->db->affected_rows();       
            
        //Eliminar descendencia
            $this->db->like("CONCAT('-', (ascendencia), '-')", "-$item_id-");
            $this->db->where('categoria_id', 21);
            $this->db->delete('item');

        //Eliminar asignaciones a productos en tabla meta
            $this->db->where('dato_id', 21);
            $this->db->where('relacionado_id', $item_id);
            $this->db->delete('meta');

        return $quan_deleted;
    }

// OTROS PARA ITEM
//-----------------------------------------------------------------------------
    
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
        
        if ( $row_item->padre_id > 0 )
        {
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
        
        foreach ( $items->result() as $row_item )
        {
            $this->set_ascendencia($row_item->id);
        }
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