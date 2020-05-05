<?php
class Busqueda_model extends CI_Model{
    
    function palabras($texto_busqueda)
    {
        
        $palabras = array();
        
        if ( strlen($texto_busqueda) > 2 ){
            
            $no_buscar = array(
                'la',
                'el',
                'los',
                'las',
                'del',
                'de',
                'y',
            );

            $palabras = explode(' ', $texto_busqueda);

            foreach ($palabras as $key => $palabra){
                if ( in_array($palabra, $no_buscar ) ){
                    unset($palabras[$key]);
                }
            }
        }
        
        return $palabras;
    }
    
    function arr_indices()
    {
        $arr_indices = array(
            'q',        //Texto búsqueda
            'cat',      //Categoría
            'tag',      //Etiqueta
            'etd',      //Estado
            'tp',       //Tipo
            'o',        //Order by
            'ot',       //Order by tipo
            'rol',      //Rol de usuario
            'sexo',     //Sexo
            'e',        //Editado, fehca de edición
            'fab',      //Fabricante
            'est',      //Estado
            'fi',       //Fecha inicial
            'ff',       //Fecha final
            'ofrt',     //Producto en oferta
            'dcto',     //Descuento aplicado a un producto
            'prc_min',  //Precio mínimo
            'prc_max',  //Precio máximo
            'condicion',//Condición SQL Where adicional
            'fe1',      //Filtro especial 1
        );
        
        return $arr_indices;
    }
    
    /**
     * Array de búsqueda con valor NULL para todos los índices
     * Valor inicial antes de evaluar contenido de POST y GET
     * @return null
     */
    function busqueda_array_inicial()
    {
        $arr_indices = $this->arr_indices();
        
        foreach ($arr_indices as $indice) {
            $busqueda[$indice] = NULL;
        }
        
        return $busqueda;
    }
    
    /**
     * Array con los parámetros de una búsqueda, respuesta para los dos métodos
     * de solicitud POST y GET.
     * 
     * @return type
     */
    function busqueda_array()
    {
        $busqueda = $this->busqueda_array_inicial();
        $arr_indices = $this->arr_indices();
        
        if ( $this->input->post() )
        {
            //Búsqueda por formulario
            foreach ($arr_indices as $indice) {
                $busqueda[$indice] = $this->input->post($indice);
            }

            $busqueda['q_uri'] = $this->Pcrn->texto_uri($busqueda['q'], TRUE);
        } elseif ( $this->input->get() ){
            //Se ha hecho una consulta, por get

            //Búsqueda por formulario
            foreach ($arr_indices as $indice) {
                $busqueda[$indice] = $this->input->get($indice);
            }

            $busqueda['q_uri'] = $this->input->get('q');
        }
            
        return $busqueda;
    }
    
    /**
     * String con la cadena para URL tipo GET, con los valores de la búsqueda
     * @return type
     */
    function busqueda_str()
    {
        $busqueda = $this->busqueda_array();
        $arr_indices = $this->arr_indices();
        $busqueda_str = '';
        
        foreach ( $arr_indices as $indice ) 
        {
            if ( $busqueda[$indice] != '' ) { $busqueda_str .= "{$indice}={$busqueda[$indice]}&"; }
        }
        
        return $busqueda_str;
    } 
    
    
    /**
     * Devuelve string con segmento sql de campos con el condicional para concatenar
     * 
     * @param type $campos
     * @return type
     */
    function concat_campos($campos)
    {
        $concat_campos = '';
        
        foreach ( $campos as $campo ) {
            $concat_campos .= "IFNULL({$campo}, ''), ";
        }
        
        return substr($concat_campos, 0, -2);
    }
}