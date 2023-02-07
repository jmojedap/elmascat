<?php

class Item_model extends CI_Model{

/**
 * Versión 2022-08-17
 */
    
    function __construct(){
        parent::__construct();
        
    }

// EXPLORE FUNCTIONS - items/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'items';                      //Nombre del controlador
            $data['cf'] = 'items/explore/';                     //Nombre del controlador
            $data['views_folder'] = 'admin/items/explore/';           //Carpeta donde están las vistas de exploración
            $data['num_page'] = $num_page;                      //Número de la página
            
        //Vistas
            $data['head_title'] = 'Items';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con listado de items, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-01
     */
    function get($filters, $num_page, $per_page)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $this->search($filters, $per_page, $offset)->result();    //Resultados para página
            $data['str_filters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de items
     * 2021-08-14
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'items.*';
        $arr_select['export'] = 'items.*';

        return $arr_select[$format];
    }
    
    /**
     * Query de items, filtrados según búsqueda, limitados por página
     * 2020-08-01
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select($this->select());
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('id_interno', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('item', $per_page, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar items
     * 2022-08-05
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $q_search_fields = ['item', 'descripcion',];
        $words_condition = $this->Search_model->words_condition($filters['q'], $q_search_fields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['cat_1'] != '' ) { $condition .= "categoria_id = {$filters['cat_1']} AND "; }
        if ( $filters['fe1'] != '' ) { $condition .= "item_group = {$filters['fe1']} AND "; }
        if ( $filters['fe2'] != '' ) { $condition .= "filters LIKE '%-{$filters['fe2']}-%' AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-06-19
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('item'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL, para filtrar listado de usuarios según el rol del usuario en sesión
     * 2020-08-01
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'items.item_group >= 10';  //Valor por defecto, categorías públicas
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los ITEMS
            $condition = 'items.id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Item',
            'id_interno' => 'Código',
            'item' => 'Nombre',
        );
        
        return $order_options;
    }

    /**
     * Query para exportar
     * 2021-09-27
     */
    function query_export($filters)
    {
        //Select
        $select = $this->select('export');
        if ( $filters['sf'] != '' ) { $select = $this->select($filters['sf']); }
        $this->db->select($select);

        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $this->db->order_by('categoria_id', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
// CRUD ITEM
//---------------------------------------------------------------------------------------------------------
    
    function next_cod($categoria_id)
    {
        $cod = 1;
        
        $this->db->select('MAX(cod) AS max_cod');
        $this->db->where('categoria_id', $categoria_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $cod = $query->row()->max_cod + 1;
        }
        
        return $cod;
    }
    
    /**
     * Elimina un registro de la tabla item, requiere ID item, e ID category, para
     * asegurar y confirmar registro correcto.
     * 2020-04-03
     */
    function delete($item_id, $categoria_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);   //Resultado inicial

        $this->db->where('id', $item_id);
        $this->db->where('categoria_id', $categoria_id);
        $this->db->delete('item');

        $data['qty_deleted'] = $this->db->affected_rows();

        if ( $data['qty_deleted'] > 0) { $data['status'] = 1; } //Verificar resultado

        return $data;
    }
    
    /**
     * Guardar un registro en la tabla items. Insertar o Editar.
     */
    function save($arr_row, $item_id)
    {
        //Set condition
            $condition = "id = {$item_id}";
            if ( $item_id == 0 ) { $condition = "categoria_id = {$arr_row['categoria_id']} AND cod = {$arr_row['id_interno']}"; }
        
        //Insert or Update
            $data['saved_id'] = $this->Db_model->save('item', $condition, $arr_row);
            
        //Result
            $data['status'] = 0;
            if ( $data['saved_id'] > 0 )
            {
                $data['status'] = 1;
                //Modificar campos dependientes
                $row_item = $this->Db_model->row_id('item', $data['saved_id']);
                $this->update_ancestry($row_item);
                $this->update_offspring($row_item);
            }
        
        return $data;
    }
    
    /**
     * Devuelve el value del field items.cod para una categoría
     * dado un value de un field
     */
    function cod($categoria_id, $value, $field = 'abreviatura')
    {   
        $condition = "categoria_id = {$categoria_id} AND {$field} = '{$value}'";
        $cod = $this->Pcrn->field('item', $condition, 'id_interno');
        
        return $cod;
    }
    
// DATOS
//-----------------------------------------------------------------------------
    
    /**
     * Listado de items con una condición específica
     * 2022-02-18
     */
    function get_items($condition)
    {
        $this->db->order_by('id_interno', 'ASC');
        $items = $this->db->get_where('item', $condition);
        
        return $items;
    }

    function items($categoria_id)
    {
        //$this->db->order_by('ancestry', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $items = $this->db->get_where('item', "categoria_id = {$categoria_id}");
        
        return $items;
    }
    
    /**
     * Devuelve el name de un item con el formato correspondiente.
     * 
     */
    function name($categoria_id, $cod, $field = 'item')
    {
        $name = 'ND';
        
        $this->db->select("{$field} as field");
        $this->db->where('id_interno', $cod);
        $this->db->where('categoria_id', $categoria_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $name = $query->row()->field;
        }
        
        return $name;
    }
    
// Arrays
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con índice y value para una categoría específica de items
     * Dadas unas características definidas en el array $config
     * 
     * @param type $condition
     * @return type
     */
    function arr_cod($condition)
    {   
        $this->db->select('cod, item');
        $this->db->where($condition);
        $this->db->order_by('position', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item');
        
        $arr_item = $this->pml->query_to_array($query, 'item', 'id_interno');
        
        return $arr_item;
    }

    /**
     * Array con items, especificando código y nombre. Filtrados por condición
     * 2022-09-11
     * 
     * @param string $condition
     * @return array $options
     */
    function arr_options($condition)
    {
        $select = 'CONCAT("0", (cod)) AS str_cod, cod, item AS name, nombre_corto,
            abreviatura, slug, padre_id';

        $query = $this->db->select($select)
            ->where($condition)
            ->order_by('id_interno', 'ASC')
            ->get('item');
        
        $options = $query->result_array();
        
        return $options;
    }
    
    /**
     * Array con options de item, para elementos select de formularios.
     * La variable $condition es una condición WHERE de SQL para filtrar los items.
     * En el array el índice corresponde al cod y el value del array al
     * field items. La variable $empty_value se pone al principio del array
     * cuando el field select está vacío, sin ninguna opción seleccionada.
     * 
     * @param string $condition
     * @param string $empty_value
     * @return array $options
     */
    function options($condition, $empty_value = NULL)
    {
        
        $select = 'CONCAT("0", (cod)) AS str_cod, item AS field_value';
        
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->order_by('id_interno', 'ASC');
        $this->db->order_by('position', 'ASC');
        $query = $this->db->get('item');
        
        $options_pre = $this->pml->query_to_array($query, 'field_value', 'str_cod');
        
        if ( ! is_null($empty_value) ) 
        {
            $options = array_merge(array('' => '[ ' . $empty_value . ' ]'), $options_pre);
        } else {
            $options = $options_pre;
        }
        
        return $options;
    }

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación 
     * 2020-04-01
     */
    function import_config()
    {
        $data['help_note'] = 'Se importarán items a la BD';
        $data['help_tips'] = array();
        $data['template_file_name'] = 'f60_items.xlsx';
        $data['sheet_name'] = 'items';
        $data['head_subtitle'] = 'Importar items';
        $data['destination_form'] = "admin/items/import_e/";

        return $data;
    }

    /**
     * Importa items a la base de datos
     * 2020-04-01
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_row($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla item
     * 2020-04-01
     */
    function import_row($row_data)
    {
        //Validar
            $error_text = '';
            $row_category = $this->Db_model->row('item', "categoria_id = 0 AND cod = '$row_data[0]'");
                            
            if ( strlen($row_data[1]) == 0 ) { $error_text = 'La casilla `cod` está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text = 'La casilla `item name` está vacía. '; }
            if ( is_null($row_category) ) { $error_text = "El ID de category '{$row_data[0]}' no existe. "; }

        //Si no hay error
            if ( $error_text == '' )
            {                
                $arr_row['categoria_id'] = $row_data[0];
                $arr_row['id_interno'] = $row_data[1];
                $arr_row['item'] = $row_data[2];
                $arr_row['abreviatura'] = ( is_null($row_data[3]) ) ? strtolower(substr($row_data[2],0,4)) : $row_data[3];
                $arr_row['padre_id'] = ( is_null($row_data[6]) ) ? 0 : $row_data[6];
                $arr_row['descripcion'] = ( is_null($row_data[8]) ) ? $row_category->item . ' - ' . $row_data[2] : $row_data[8];
                $arr_row['nombre_largo'] = ( is_null($row_data[10]) ) ? $row_data[2] : $row_data[10];
                $arr_row['nombre_corto'] = ( is_null($row_data[11]) ) ? $row_data[2] : $row_data[11];
                $arr_row['slug'] = $row_category->slug . '-' . $this->Db_model->unique_slug($row_data[2], 'item');

                //Guardar en tabla item
                $data_insert = $this->save($arr_row, 0);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// GESTIÓN DE JERARQUÍA DE ÍTEMS
//-----------------------------------------------------------------------------

    /**
     * Actualiza el campo items.ancestry para un item específico
     * 2020-05-05
     */
    function update_ancestry($row)
    {
        //Valores por iniciales defecto
        $prefix = '-';
        $level = 0;
        
        //Si tiene padre, cambiar valores
        if ( $row->padre_id > 0 )
        {
            $row_parent = $this->Db_model->row('item', "categoria_id = {$row->categoria_id} AND cod = {$row->padre_id}");
            $prefix = $row_parent->ancestry;
            $level = $row_parent->level + 1;
        }
        
        //Construir registro
            $arr_row['ancestry'] = $prefix . $row->cod . '-';
            $arr_row['level'] = $level;
        
        //Actualizar
            $this->db->where('id', $row->id);
            $this->db->update('item', $arr_row);   
    }
    
    /**
     * Actualiza el campo items.ancestry para todos los items correspondientes a la descendencia
     * de un item ($row), necesaria cuando un item cambia de padre inmediado en la jerarqía
     * 2020-05-05
     */
    function update_offspring($row)
    {
        $items = $this->offspring($row->id);

        foreach ( $items->result() as $row_child )
        {
            $this->update_ancestry($row_child);
        }
    }
    
    /**
     * Descendencia de un ítem, en un formato específico
     * 2020-04-02
     */
    function offspring($item_id, $format = 'query')
    {
        $offspring = NULL;
        $row = $this->Db_model->row_id('item', $item_id);
        
        $this->db->like("CONCAT('-', (ancestry), '-')", "-{$row->cod}-");
        $this->db->where('categoria_id', $row->categoria_id);
        $this->db->order_by('ancestry', 'ASC');
        $query = $this->db->get('item');
        
        if ( $format == 'query' ) {
            $offspring = $query;
        } elseif ( $format == 'array' ) {
            $offspring = $this->pml->query_to_array($query, 'id');
        } elseif ( $format == 'string' ) {
            $offspring = '0';
            $arr_offspring = $this->pml->query_to_array($query, 'id');
            if ( $query->num_rows() > 0 ) {
                $offspring = implode(',', $arr_offspring);
            }
        }
        
        return $offspring;
    }
}