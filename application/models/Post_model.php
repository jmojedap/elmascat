<?php
class Post_model extends CI_Model{

    function basic($post_id)
    {
        $row = $this->Db_model->row_id('post', $post_id);

        $data['post_id'] = $post_id;
        $data['row'] = $row;
        $data['att_img'] = $this->att_img($row);
        $data['head_title'] = $data['row']->nombre_post;
        $data['view_a'] = 'posts/post_v';
        $data['nav_2'] = 'posts/menu_v';

        //Listas
        if ( $data['row']->tipo_id == 22  )
        {
            $data['nav_2'] = 'posts/types/list/menu_v';
        }

        return $data;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla post.
     * 2020-02-22
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }

        $data = array('status' => 0);
        
        //Insert in table
            $this->db->insert('post', $arr_row);
            $data['saved_id'] = $this->db->insert_id();

        if ( $data['saved_id'] > 0 ) { $data['status'] = 1; }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla post
     * 2020-02-22
     */
    function update($post_id)
    {
        $data = array('status' => 0);

        //Guardar
            $arr_row = $this->Db_model->arr_row($post_id);
            $saved_id = $this->Db_model->save('post', "id = {$post_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){ $data = array('status' => 1); }
        
        return $data;
    }
    
    function deletable()
    {
        $deletable = 0;
        if ( $this->session->userdata('role') <= 1 ) { $deletable = 1; }

        return $deletable;
    }

    /**
     * Eliminar un usuario de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($post_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($post_id) ) 
        {
            //Tablas relacionadas
            
            //Tabla principal
                $this->db->where('id', $post_id);
                $this->db->delete('post');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }
    
// EXPLORE FUNCTIONS - posts/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'posts';                      //Nombre del controlador
            $data['cf'] = 'posts/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'posts/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Posts';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 10;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $elements = $this->search($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Tipo de post
        if ( $filters['type'] != '' ) { $condition .= "tipo_id = {$filters['type']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('post_id'));

        //Construir consulta
            $this->db->select('id, nombre_post AS post_name, resumen AS except, tipo_id AS type_id');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('nombre_post', 'contenido', 'resumen'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('editado', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de post en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $filters
     * @return type
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     * 
     * @param type $post_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero post.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Post',
            'nombre_post' => 'Nombre'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }

// VALIDATION
//-----------------------------------------------------------------------------

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['nombre_post'], 'post');
            $arr_row['usuario_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// GESTIÓN DE IMAGEN
//-----------------------------------------------------------------------------

    function att_img($row)
    {
        $att_img = array(
            'src' => URL_IMG . 'app/nd.png',
            'alt' => 'Imagen del Post ' . $row->id,
            'onerror' => "this.src='" . URL_IMG . "app/nd.png'"
        );

        $row_file = $this->Db_model->row_id('archivo', $row->imagen_id);
        if ( ! is_null($row_file) )
        {
            $att_img['src'] = URL_UPLOADS . $row_file->carpeta . $row_file->nombre_archivo;
            $att_img['alt'] = $row_file->titulo_archivo;
        }

        return $att_img;
    }
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen del post
     */
    function set_image($post_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial
        $row_file = $this->Db_model->row_id('file', $file_id);
        
        $arr_row['image_id'] = $row_file->id;
        
        $this->db->where('id', $post_id);
        $this->db->update('post', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen del post fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }

        return $data;
    }

    /**
     * Le quita la imagen asignada a un post, eliminado el archivo
     * correspondiente
     * 
     * @param type $post_id
     * @return int
     */
    function remove_image($post_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('post', $post_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;

            //Modificar Row en tabla Post
            $arr_row['image_id'] = 0;
            $this->db->where('image_id', $row->image_id);
            $this->db->update('post', $arr_row);
        }
        
        return $data;
    }

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación según el tipo de usuario
     * que se va a importar.
     * 2019-11-20
     */
    function import_config($type)
    {
        $data = array();

        if ( $type == 'general' )
        {
            $data['help_note'] = 'Se importarán posts a la base de datos.';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f50_posts.xlsx';
            $data['sheet_name'] = 'posts';
            $data['head_subtitle'] = 'Importar';
            $data['destination_form'] = "posts/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa posts a la base de datos
     * 2020-02-22
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_post($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla post, y agrega al grupo asignado.
     * 2020-02-22
     */
    function import_post($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla Nombre está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla Cod Tipo está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla Resumen está vacía. '; }
            if ( strlen($row_data[14]) == 0 ) { $error_text .= 'La casilla Fecha Publicación está vacía. '; }

        //Si no hay error
            $this->load->helper('string');
            if ( $error_text == '' )
            {
                $arr_row['nombre_post'] = $row_data[0];
                $arr_row['tipo_id'] = $row_data[1];
                $arr_row['resumen'] = $row_data[2];
                $arr_row['contenido'] = $row_data[3];
                $arr_row['content_json'] = $row_data[4];
                $arr_row['keywords'] = $row_data[5];
                $arr_row['code'] = $this->pml->if_strlen($row_data[6], strtolower(random_string('alpha')));
                $arr_row['place_id'] = $this->pml->if_strlen($row_data[7], 0);
                $arr_row['referente_1_id'] = $this->pml->if_strlen($row_data[8], 0);
                $arr_row['referente_2_id'] = $this->pml->if_strlen($row_data[9], 0);
                $arr_row['imagen_id'] = $this->pml->if_strlen($row_data[10], 0);
                $arr_row['texto_1'] = $this->pml->if_strlen($row_data[11], '');
                $arr_row['texto_2'] = $this->pml->if_strlen($row_data[12], '');
                $arr_row['estado'] = $this->pml->if_strlen($row_data[13], 2);
                $arr_row['publicado'] = $this->pml->dexcel_dmysql($row_data[14]);
                $arr_row['slug'] = $this->Db_model->unique_slug($row_data[0], 'post');
                
                $arr_row['usuario_id'] = $this->session->userdata('user_id');
                $arr_row['editor_id'] = $this->session->userdata('user_id');

                //Guardar en tabla user
                $data_insert = $this->insert($arr_row);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Asignar un contenido de la tabla post a un usuario, lo agrega como metadato
     * en la tabla meta, con el tipo 100012
     * 2020-04-15
     */
    function add_to_user($post_id, $user_id)
    {
        //Construir registro
        $arr_row['tabla_id'] = 1000;    //usuario
        $arr_row['dato_id'] = 100012;   //Asignación de post
        $arr_row['elemento_id'] = $user_id; //Usuario ID, al que se asigna
        $arr_row['relacionado_id'] = $post_id;  //ID contenido
        $arr_row['usuario_id'] = 0;  //Usuario que asigna

        //Establecer usuario que ejecuta
        if ( $this->session->userdata('logged') ) {
            $arr_row['usuario_id'] = $this->session->userdata('user_id');
        }

        $condition = "dato_id = {$arr_row['dato_id']} AND elemento_id = {$arr_row['elemento_id']} AND relacionado_id = {$arr_row['relacionado_id']}";
        $meta_id = $this->Db_model->save('meta', $condition, $arr_row);

        //Establecer resultado
        $data = array('status' => 0, 'saved_id' => '0');
        if ( $meta_id > 0) { $data = array('status' => 1, 'saved_id' => $meta_id); }

        return $data;
    }

    /**
     * Quita la asignación de un post a un usuario
     * 2020-04-30
     */
    function remove_to_user($post_id, $meta_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);

        $this->db->where('id', $meta_id);
        $this->db->where('relacionado_id', $post_id);
        $this->db->delete('meta');

        $data['qty_deleted'] = $this->db->affected_rows();

        if ( $data['qty_deleted'] > 0) { $data['status'] = 1; }

        return $data;
    }

// Seguimiento
//-----------------------------------------------------------------------------
    /**
     * Guardar evento de apertura de post
     * 2020-04-26
     */
    function save_open_event($post_id)
    {
        $arr_row['tipo_id'] = 51;   //Apertura de post
        $arr_row['inicio'] = date('Y-m-d H:i:s');
        $arr_row['editado'] = date('Y-m-d H:i:s');
        $arr_row['creado'] = date('Y-m-d H:i:s');
        $arr_row['direccion_ip'] = $this->input->ip_address();
        $arr_row['elemento_id'] = $post_id;

        if( ! is_null($this->session->userdata('usuario_id')) )
        {
            $arr_row['usuario_id'] = $this->session->userdata('usuario_id');
            $arr_row['editor_id'] = $this->session->userdata('usuario_id');
            $arr_row['creador_id'] = $this->session->userdata('usuario_id');
        }

        $evento_id = $this->Db_model->save('evento', 'id = 0', $arr_row);

        return $evento_id;
    }

// Listas 22
//-----------------------------------------------------------------------------

    function metadata($post_id, $dato_id = NULL)
    {
        $this->db->select('*');
        $this->db->where('relacionado_id', $post_id);
        $this->db->where('dato_id', $dato_id);
        $this->db->order_by('dato_id', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('meta');
        
        return $query;
    }
}