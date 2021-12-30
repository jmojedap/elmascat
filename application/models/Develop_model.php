<?php

class Develop_model extends CI_Model{
    
    /* App, hace referencia a Application,
     * Colección de funciones creadas para utilizarse específicamente
     * con CodeIgniter en la apliación del sitio 
     * 
     * Districatólicas
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
    function basico()
    {
        $data[] = 1;
        return $data;
    }
    
    function crud_sis_option()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        
        $crud = new grocery_CRUD();
        $crud->set_table('sis_option');
        $crud->set_subject('opción');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        
        
        
        //Títulos de los campos
            $crud->display_as('nombre_opcion', 'Nombre opción');
            
        //Otros
            $crud->order_by('id', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_tabla($nombre_tabla)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table($nombre_tabla);
        $crud->set_subject($nombre_tabla);
        $crud->unset_delete();
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
        $crud->set_table('sis_acl_recurso');
        $crud->set_subject('recurso');
        $crud->columns('id', 'titulo_recurso', 'controlador', 'funcion', 'recurso', 'tipo_id', 'descripcion', 'roles', 'link_ayuda', 'clase_menu', 'clase_submenu');
        $crud->unset_print();
        $crud->unset_read();
        $crud->order_by('controlador', 'ASC');
        
        //Filtro
            if ( ! is_null($controlador) ) { 
                $crud->where('controlador', $controlador);
            }

        //Form edit
            $crud->edit_fields(
                'controlador',
                'funcion',
                'tipo_id',
                'roles',
                'descripcion',
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
                'titulo_recurso',
                'recurso'
            );
        
        //Reglas
            $crud->required_fields('controlador', 'recurso');
        
        //Títulos
            $crud->display_as('roles', 'Roles bloqueados');
            $crud->display_as('tipo_id', 'Tipo recurso');
            $crud->display_as('funcion', 'Función');
            $crud->display_as('descripcion', 'Descripción');
            
        //Formato
            $crud->field_type('tipo_id', 'dropdown', array(1 => 'Controlador', 2 => 'Función'));
        

        return $crud->render();
    }
    
//PROCESOS MASIVOS DE DATOS
//---------------------------------------------------------------------------------------------------------

    
    /**
     * Elimina los registros con valores de claves que no tienen registro correspondiente en la tabla relacionada
     * 
     * Eliminación en cascada
     */
    function eliminar_cascada(){
        
        //Grupos sin institución
            $consultas_sql[] = "DELETE FROM grupo WHERE institucion_id NOT IN (SELECT id FROM institucion)";
            
        //Asignaciones de grupos sin grupo
            $consultas_sql[] = "DELETE FROM usuario_grupo WHERE grupo_id NOT IN (SELECT id FROM grupo)";
        
        //Estudiantes sin institucion
            $consultas_sql[] = "DELETE FROM usuario WHERE rol_id = 6 AND institucion_id NOT IN (SELECT id FROM institucion)";

        //Estudiantes sin grupo
            $consultas_sql[] = "DELETE FROM usuario WHERE rol_id = 6 AND grupo_id NOT IN (SELECT id FROM grupo)";
        
        //Asignaciones en estudiantes inexistentes
            $consultas_sql[] = "DELETE FROM usuario_cuestionario WHERE usuario_id NOT IN (SELECT id FROM usuario)";
            $consultas_sql[] = "DELETE FROM usuario_pregunta WHERE usuario_id NOT IN (SELECT id FROM usuario)";
            $consultas_sql[] = "DELETE FROM usuario_grupo WHERE usuario_id NOT IN (SELECT id FROM usuario)";
            $consultas_sql[] = "DELETE FROM usuario_flipbook WHERE usuario_id NOT IN (SELECT id FROM usuario)";
            $consultas_sql[] = "DELETE FROM mensaje WHERE usuario_id NOT IN (SELECT id FROM usuario)";
            $consultas_sql[] = "DELETE FROM mensaje_usuario WHERE usuario_id NOT IN (SELECT id FROM usuario)";
        
        //Grupos
            $consultas_sql[] = "DELETE FROM usuario_grupo WHERE grupo_id NOT IN (SELECT id FROM grupo)";
            $consultas_sql[] = "DELETE FROM grupo_profesor WHERE grupo_id NOT IN (SELECT id FROM grupo)";
            //$consultas_sql[] = "DELETE FROM usuario_cuestionario WHERE grupo_usuario_id NOT IN (SELECT id FROM grupo)";
            
        //Quices
            $consultas_sql[] = "DELETE FROM recurso WHERE referente_id NOT IN (SELECT id FROM quiz) AND tipo_recurso_id = 3";
            $consultas_sql[] = "DELETE FROM quiz_elemento WHERE quiz_id NOT IN (SELECT id FROM quiz)";
        
        //Asignaciones en Flipbooks inexistentes
            $consultas_sql[] = "DELETE FROM usuario_flipbook WHERE flipbook_id NOT IN (SELECT id FROM flipbook)";
            $consultas_sql[] = "DELETE FROM flipbook_contenido WHERE flipbook_id NOT IN (SELECT id FROM flipbook)";
            
            $consultas_sql[] = "DELETE FROM usuario_cuestionario WHERE grupo_id NOT IN (SELECT id FROM grupo)";
        
        //Detalles de páginas, de páginas inexistentes
            $consultas_sql[] = "DELETE FROM pagina_flipbook_detalle WHERE pagina_id NOT IN (SELECT id FROM pagina_flipbook)";
            
        //Asignaciones de recursos inexistentes
            $consultas_sql[] = "DELETE FROM pagina_flipbook_detalle WHERE recurso_id NOT IN (SELECT id FROM recurso)";
            
        //Cuestionarios con preguntas inexistentes
            $consultas_sql[] = "DELETE FROM cuestionario_pregunta WHERE pregunta_id NOT IN (SELECT id FROM pregunta)";
            
        //Mensajes
            $consultas_sql[] = "DELETE FROM conversacion WHERE asunto IS NULL";
            $consultas_sql[] = "DELETE FROM usuario_asignacion WHERE tipo_asignacion_id = 5 AND referente_id NOT IN (SELECT id FROM conversacion)";
            
        foreach ($consultas_sql as $sql) {
            $this->db->query($sql);    
        }
    }
    
//SINCRONIZACIÓN DE TABLAS - controllers/sincro
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve un array con los campos y con los datos de un query en un formato resumido ()
     * Se utiliza para convertir en json. Utilizado para sincronización de base de datos local
     * Ver controllers/sincro
     * 
     * @param type $query
     * @return type
     */
    function query_liviano($query)
    {
        $campos = $query->list_fields();
        $query_liviano = array();
        
        foreach( $query->result() as $row )
        {    
            foreach ( $campos as $key => $campo ) 
            {
                $registro[$key] = $row->$campo;
            }
            
            $query_liviano[] = $registro;
        }
        
        return $query_liviano;
        
    }
    
    /**
     * Para sincronización, primero eliminar todos los datos de la tabla local
     * @param type $tabla
     */
    function limpiar_tabla($tabla)
    {
        //Debe ser desarrollador
        if ( $this->session->userdata('role') == 0 ) 
        {
            $sql = "TRUNCATE TABLE {$tabla}";
            $this->db->query($sql);
        }
    }
    
    /**
     * Llenar la tabla local con los registros descargados
     * 
     * @param type $tabla
     * @param type $descarga
     * @return type
     */
    function cargar_registros($tabla, $descarga)
    {
        $campos = $descarga->campos;
        $registros = $descarga->registros;
        $respuesta['max_id'] = 0;
        $respuesta['cant_registros'] = count($registros);
        
        foreach( $registros as $registro_liviano ) 
        {
            foreach ( $registro_liviano as $i => $valor ) 
            {
                $registro[$campos[$i]] = $valor;
            }
            
            $condicion = "id = {$registro['id']}";
            $this->Pcrn->guardar($tabla, $condicion, $registro);
            
            $respuesta['max_id'] = $registro['id'];
        }
        
        return $respuesta;
    }
    
    /**
     * Condición SQL para filtrar las tablas que se pueden sincronizar, filtro
     * aplicado en la tabla sis_tabla
     * 
     * @return string
     */
    function condicion_sincro()
    {
        $condicion = 'id NOT IN (1140, 1100, 9998)';
        return $condicion;
    }
    
    /**
     * Tablas de la base de datos
     * 
     * @param type $condicion
     * @return type
     */
    function tablas($condicion = NULL)
    {
        
        $this->db->select('sis_tabla.*, (max_ids - max_ids) AS dif_registros');
        
        if ( ! is_null($condicion) ) 
        {
            $this->db->where($condicion);
        }
        
        //Orden
        $order_by = $this->input->get('ob');
        $order_type = $this->input->get('ot');
        if ( ! is_null($order_by) ) 
        {
            $this->db->order_by($order_by, $order_type);
        } else {
            $this->db->order_by('(max_ids - max_id)', 'DESC');
        }
        
        $tablas = $this->db->get('sis_tabla');
        
        return $tablas;
    }
    
    /**
     * Actualiza los campos sis_tabla.max_id y sis_tabla.cant_registros
     * ID máximo de la tabla en la versión local, para comparar con la versión
     * en servidor
     */
    function act_max_idl($arr_estado_tablas)
    {
        foreach ($arr_estado_tablas as $estado_tabla)
        {
            //Construir registro
                $registro['max_id'] = $estado_tabla['max_id'];
                $registro['cant_registros'] = $estado_tabla['cant_registros'];
                
            //Actualizar
                $this->db->where('id', $estado_tabla['tabla_id']);
                $this->db->update('sis_tabla', $registro);
        }
    }
    
    /**
     * Actualiza los campos de la tabla sis_tabla: max_ids y cant_registros_servidor
     * Estado actual de datos de las tablas en el servidor
     * 
     */
    function act_estado_servidor($arr_estado_tablas)
    {   
        foreach ($arr_estado_tablas as $estado_tabla)
        {
            //Construir registro
                $registro['max_ids'] = $estado_tabla['max_id'];
                $registro['cant_registros_servidor'] = $estado_tabla['cant_registros'];
                
            //Actualizar
                $this->db->where('id', $estado_tabla['tabla_id']);
                $this->db->update('sis_tabla', $registro);
        }
    }
    
    /**
     * Array con los valores de tabla de la BD, incluye el id de la tabla,
     * la cantidad de registros y el ID máximo
     * 
     * @param type $condicion
     * @return type
     */
    function arr_estado_tablas($condicion)
    {
        $arr_estado_tablas = array();
        
        //Seleccionar tablas
            if ( ! is_null($condicion) ) { $this->db->where($condicion); }
            $tablas = $this->db->get('sis_tabla');
        
        //Recorrer tablas
        foreach ( $tablas->result() as $row_tabla )
        {
            $estado_tabla = $this->estado_tabla($row_tabla->nombre_tabla);
            $arr_estado_tablas[] = $estado_tabla;
        }
        
        return $arr_estado_tablas;
    }
    
    /**
     * Array con los datos del estado de una tabla
     * 
     * @param type $nombre_tabla
     * @return type
     */
    function estado_tabla($nombre_tabla)
    {
        $row_tabla = $this->Pcrn->registro('sis_tabla', "nombre_tabla = '{$nombre_tabla}'");
        
        //Buscar máximo
            $this->db->select('MAX(id) AS max_id, COUNT(id) AS cant_registros');
            $query = $this->db->get($row_tabla->nombre_tabla);

            $estado_tabla['max_id'] = 0;
            $estado_tabla['cant_registros'] = 0;
            if ( $query->num_rows() ) 
            {
                $estado_tabla['max_id'] = $query->row()->max_id;
                $estado_tabla['cant_registros'] = $query->row()->cant_registros;   
            }

        //Elemento
            $estado_tabla['tabla_id'] = $row_tabla->id;
            
        return $estado_tabla;
        
    }
    
// CAMPOS
//-----------------------------------------------------------------------------
    
    /**
     * Inserta en la tabla item, los campos de las tablas de la base de datos
     * 
     * 
     * @return string
     */
    function act_campos_item()
    {
        $cant_encontrados = 0;
        $tablas = $this->db->get('sis_tabla');
        
        $registro['categoria_id'] = 31; //Campo de tabla
        
        foreach ($tablas->result() as $row_tabla)   //Recorrer tablas
        {
            $i = 0;
            
            $registro['item_grupo'] = $row_tabla->id;
            
            $campos = $this->db->list_fields($row_tabla->nombre_tabla);
            
            foreach ($campos as $campo)
            {
                $i++;
                $registro['item'] = $campo;
                $registro['slug'] = $row_tabla->nombre_tabla . '.' .$campo;
                $registro['id_interno'] = $row_tabla->id * 100 + $i;
                
                $condicion = "categoria_id = 31 AND item = '{$registro['item']}' AND item_grupo = {$registro['item_grupo']}";
                
                $item_id = $this->Pcrn->insertar_si('item', $condicion, $registro);
                if ( $item_id > 0 ) { $cant_encontrados++; } 
            }
            
        }
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = "Se encontraron {$cant_encontrados} campos en la base de datos";
        $resultado['clase'] = 'alert-success';
        $resultado['icono'] = 'fa-check';
        
        return $resultado;
    }
    
}