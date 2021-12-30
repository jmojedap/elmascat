<?php

class Sincro_model extends CI_Model{
    
    /* Admin hace referencia a Administración,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación para tareas de administración
     * 
     */
    
    function __construct(){
        parent::__construct();
        
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
}