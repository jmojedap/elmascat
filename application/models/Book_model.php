<?php

class Book_model extends CI_Model{
    
    /**
     * Información básica de los libros virtuales
     * 2020-03-28
     */
    function book_info($book_code)
    {
        $row = $this->Db_model->row('post', "code = '{$book_code}'");
        $book['head_title'] = $row->nombre_post;
        $book['book_id'] = $row->id;

        return $book;
    }

    /**
     * Determina si el usuario en sesión tiene o no permiso para leer un book
     * dependiendo del rol y de las asignaciones.
     */
    function readable($meta_id)
    {
        $readable = false;

        $row_meta = $this->Db_model->row_id('meta', $meta_id);

        //Si lo tiene asignado, permitido
        if ( ! is_null($row_meta))
        {
            if ( $row_meta->elemento_id == $this->session->userdata('user_id') )
            {
                $readable = true;
            }
        }

        //Si es usuario interno, permitido
        if ( $this->session->userdata('logged') == true && $this->session->userdata('role') < 10 ) {
            $readable = true;
        }

        return $readable;
    }
}