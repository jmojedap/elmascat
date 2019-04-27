<?php
class Post_Model extends CI_Model{
    
    function basico($post_id)
    {
        
        $this->db->where('id', $post_id);
        $query = $this->db->get('post');
        
        $row = $query->row();
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_post;
        $basico['vista_a'] = $this->vista_a($row);
        
        return $basico;
    }
    
    /**
     * Búsqueda de posts
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
            if ( strlen($busqueda['q']) > 2 ){
                
                $campos_posts = array('nombre_post', 'contenido', 'resumen', 'editado', 'creado');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_posts);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('post.*');
            
            $this->db->order_by('id', 'DESC');
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }    //Editado
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }    //Tipo de post
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }    //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('post');
        $crud->set_subject('post');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('nombre_post', 'Título');
            $crud->display_as('tipo_id', 'Tipo');
            
        //Campos
            $crud->add_fields(
                    'nombre_post',
                    'tipo_id',
                    'usuario_id',
                    'editor_id',
                    'creado',
                    'editado'
                );
            
        //Array opciones
            $arr_tipos = $this->Item_model->arr_item(33);
            
        //Relaciones
            //$crud->set_relation('estado_post', 'item', 'item', 'categoria_id = 7', 'id_interno ASC');
            
        //Reglas
            //$crud->required_fields('nombre', 'direccion', 'telefono', 'pais_id', 'email');
            //$crud->set_rules('grosor', 'Grosor', 'is_natural');
        
        //Tipos de campo
            $crud->field_type('tipo_id', 'dropdown', $arr_tipos);
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
                        
        //Formato
            
            $crud->unset_texteditor('notas_admin');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function vista_a($row)
    {
        $vista_a = 'posts/post_v';
        if ( $row->tipo_id == 22 ){ $vista_a = 'posts/listas/lista_v'; }
        
        return $vista_a;
    }
    
    function guardar_post($condicion, $registro)
    {
        $post_id = $this->Pcrn->existe('post', $condicion);
        
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = $this->session->userdata('usuario_id');
        
        if ( $post_id == 0 ) {
            //No existe, insertar
            
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            
            $this->db->insert('post', $registro);
            $post_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $post_id);
            $this->db->update('post', $registro);
        }
        
        return $post_id;
    }
    
    function editable($post_id)
    {
        $editable = 1;
        return $editable;
    }
    
    function metadatos($post_id, $dato_id = NULL)
    {
        $this->db->select('*');
        $this->db->where('relacionado_id', $post_id);
        $this->db->where('dato_id', $dato_id);
        $this->db->order_by('dato_id', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('meta');
        
        return $query;
    }
    
    function actualizar($post_id)
    {
        $registro = $this->input->post();
        
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $post_id);
        $this->db->update('post', $registro);
        
    }
    
    function eliminable($post_id)
    {
        $eliminable = 1;
        return $eliminable;
    }
    
    function eliminar($post_id)
    {
        if ( $this->eliminable($post_id) ) {
            //Tablas relacionadas, post
                $this->db->where('tabla_id', 2000); //Tabla post
                $this->db->where('elemento_id', $post_id);
                $this->db->delete('meta');
                
            //Tablas relacionadas, post, listas
                $this->db->where('relacionado_id', $post_id);
                $this->db->where('dato_id', 22);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $post_id);
                $this->db->delete('post');
        }
    }
    
    function reordenar_lista($post_id, $arr_elementos)
    {
        //Actualizar orden en tabla meta
            foreach ( $arr_elementos as $orden => $elemento_id)
            {
                $registro['orden'] = $orden;

                $this->db->where('relacionado_id', $post_id);
                $this->db->where('dato_id', 22);    //Elemento de lista
                $this->db->where('elemento_id', $elemento_id);
                $this->db->update('meta', $registro);
            }
        
        //Actualizar edición, tabla post
            $reg_post['editado'] = date('Y-m-d H:i:s');
            $reg_post['editor_id'] = $this->session->userdata('usuario_id');
            
            $this->db->where('id', $post_id);
            $this->db->update('post', $reg_post);
        
        
        return count($arr_elementos);
    }
    
// PROMOCIONES
//-----------------------------------------------------------------------------
    
    /**
     * Objeto GroceryCrud, renderizado
     * @return type
     */
    function crud_promociones()
    {
        //Grocery crud
        $tipo_id = 31001;  //Promociones de productos
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('post');
        $crud->set_subject('promoción');
        $crud->where('tipo_id', $tipo_id);
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('editado', 'DESC');
        $crud->columns('id', 'nombre_post', 'resumen', 'estado', 'referente_1_id');

        //Permisos de edición
            
        //Filtros

        //Títulos de los campos
            $crud->display_as('nombre_post', 'Nombre promoción');
            $crud->display_as('referente_1_id', '% Descuento');
            $crud->display_as('estado', 'Promoción activa');
        
        //Relaciones
            

        //Formulario Edit
            $crud->edit_fields(
                    'nombre_post',
                    'tipo_id',
                    'resumen',
                    'estado',
                    'referente_1_id',
                    'editor_id',
                    'editado'
                );

        //Formulario Add
            $crud->add_fields(
                    'nombre_post',
                    'tipo_id',
                    'resumen',
                    'estado',
                    'referente_1_id',
                    'editor_id',
                    'editado',
                    'usuario_id',
                    'creado'
                );
            
        //Opciones estado
            $this->load->model('Esp');
            $opciones_estado = $this->Esp->arr_si_no();
            $crud->field_type('estado', 'dropdown', $opciones_estado);
            
        
        //Valores por defecto
            $crud->field_type('tipo_id', 'hidden', $tipo_id);
            $crud->field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));

        //Reglas de validación
            $crud->required_fields('nombre_post', 'referente_1_id');
            $crud->unset_texteditor('resumen');
        
        $output = $crud->render();
        
        return $output;
    }
    
}

