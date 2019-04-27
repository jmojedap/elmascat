<?php

class Meta_model extends CI_Model{
    
    /* Esp hace referencia a Especial,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación del sitio en casos especiales
     * 
     * Enlace.net.co V3
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
// CRUD META
//---------------------------------------------------------------------------------------------------------
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $this->load->model('Busqueda_model');
        
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like('CONCAT(valor, editado)', $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->order_by('editado', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('meta'); //Resultados totales
        } else {
            $query = $this->db->get('meta', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /*function sql_busqueda($cod_tabla, $palabras, $condicion_add)
    {
        $sql = 'SELECT elemento_id ';
        $sql .= 'FROM meta ';
        $sql .= "WHERE tabla_id = {$cod_tabla}";
        $sql .= "";
        $sql .= "";
        $sql .= "";
        $sql .= "";
        $sql .= "";
        
        return $sql;
    }*/
    
    function eliminable($meta_id)
    {
        $eliminable = FALSE;
        $cant_condiciones = 0;
        $row = $this->Pcrn->registro_id('meta', $meta_id);
        
        //Verificar condiciones para eliminar
            //Ha iniciado sesión
            if ( $this->session->userdata('logged') ) { $cant_condiciones++; }

            //Rol de usuario con capacidad
            if ( in_array($this->session->userdata('rol_id'), array(0,1,2)) ) { $cant_condiciones++; }

            //Fue el creador del meta dato
            if ( $row->usuario_id == $this->session->userdata('usuario_id') ) { $cant_condiciones++; }
        
        //Al menos se cumple dos condiciones
            if ( $cant_condiciones >= 2 ) { $eliminable = TRUE; }
        
        return $eliminable;
        
    }
    
    function eliminar($arr_where)
    {
        
        $this->db->where($arr_where);
        $query = $this->db->get('meta');
        
        foreach ( $query->result() as $row_meta ) {
            if ( $this->eliminable($row_meta->id) ) {
                $this->db->where('id', $row_meta->id);
                $this->db->delete('meta');
            }
        }
        
        return $query->num_rows();
    }
    
    /**
     * Guarda un registro en la tabla meta
     * Inserta o edita, según la condición
     * 
     * @param type $registro
     * @param type $tipo_clave
     * @return type
     */
    function guardar($registro, $tipo_clave = 'relacionado_id')
    {
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        $condicion = $this->condicion($registro, $tipo_clave);    
        $meta_id = $this->Pcrn->guardar('meta', $condicion, $registro);
        
        return $meta_id;
    }
    
    /**
     * Devuelve condicion WHERE sql para verificar antes de guardar un
     * registro en la tabla meta
     * 
     * @param type $registro
     * @param type $tipo_clave
     * @return type
     */
    function condicion($registro, $tipo_clave)
    {   
        $campos_clave = array(
            'tabla_id',
            'elemento_id'
        );
        
        if ( $tipo_clave == 'relacionado_id' ) { 
            $campos_clave = array(
                'tabla_id',
                'elemento_id',
                'dato_id',
                'relacionado_id'
            );  
        }
        
        $condicion_and = '';
            foreach( $campos_clave AS $nombre_campo ) {
                $condicion_and .= "{$nombre_campo} = {$registro[$nombre_campo]} AND ";
            }

            $condicion = substr($condicion_and, 0, -5); //Quitar cadena final ' AND '  
        
        return $condicion;
    }
    
    function registro_get()
    {
        $campos = array(
            'tabla_id',
            'elemento_id',
            'relacionado_id',
            'dato_id',
            'valor',
            'orden'
        );
        
        foreach( $campos as $campo ) {
            $registro[$campo] = $this->input->get($campo);
        }
        
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        return $registro;
        
    }
    
    /**
     * Devuelve el valor del campo meta.valor, dados tres parámetros clave
     * 
     * @param type $cod_tabla
     * @param type $dato_id
     * @param type $elemento_id
     * @return type
     */
    function valor($cod_tabla, $dato_id, $elemento_id)
    {
        $condicion = $condicion = "tabla_id = {$cod_tabla} AND dato_id = {$dato_id} AND elemento_id = {$elemento_id}";
        $valor = $this->Pcrn->campo('meta', $condicion, 'valor');
        
        return $valor;
    }
    
    /**
     * Query con los metacampos de una tabla, y filtrados según el criterio 
     * $filtro
     * 
     * @param type $tabla_id
     * @param type $filtro
     * @return type
     */
    function metacampos($tabla_id, $filtro = NULL)
    {
        $this->db->select('id, item AS nombre_meta, id_interno AS meta_id, slug, descripcion');
        $this->db->where('categoria_id', 2);
        $this->db->like('filtro', "-{$tabla_id}-");
        $this->db->order_by('orden', 'ASC');
        
        if ( ! is_null($filtro) )
        { $this->db->like('filtro', "-{$filtro}-"); }
        
        $metadatos = $this->db->get('item');
        
        return $metadatos;
    }
    
//---------------------------------------------------------------------------------------------------
    
    function agregar_comentario($tabla_id)
    {   
        $row_usuario = $this->session->userdata('row');
        
        $registro['tipo_id'] = 23;   //Comentario
        $registro['contenido'] = $this->input->post('contenido');
        $registro['texto_1'] = $this->session->userdata('nombre_completo');
        $registro['texto_2'] = $row_usuario->email;
        $registro['referente_1_id'] = $tabla_id;
        $registro['referente_2_id'] = $this->input->post('producto_id');
        $registro['estado'] = 1;    //Sin aprobar
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['creado'] = date('Y-m-d H:i:s');
        
        $condicion = "contenido = '{$registro['contenido']}' AND usuario_id = {$registro['usuario_id']}";
        
        $post_id = $this->Pcrn->guardar('post', $condicion, $registro);
        
        return $post_id;
    }
    
    
}