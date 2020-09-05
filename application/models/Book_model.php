<?php

class Book_model extends CI_Model{
    
    /**
     * Información básica de los libros virtuales
     * 2020-03-28
     */
    function book_info($book_code)
    {
        /*$row = $this->Db_model->row('post', "code = '{$book_code}'");
        $book['head_title'] = $row->nombre_post;
        $book['book_id'] = $row->id;
        $book['book_code'] = $row->code;*/
        $book['book_code'] = $book_code;
        $book['head_title'] = 'Revista Minutos de Amor - Mayo de 2020';
        $book['book_id'] = 311;

        if ( $book_code == '069243559697' ) {
            $book['head_title'] = 'Revista Minutos de Amor - Junio de 2020';
            $book['book_id'] = 312;
        }
        if ( $book_code == '075431960245' ) {
            $book['head_title'] = 'Revista Minutos de Amor - Julio de 2020';
            $book['book_id'] = 313;
        }
        if ( $book_code == '086239668181' ) {
            $book['head_title'] = 'Revista Minutos de Amor - Agosto de 2020';
            $book['book_id'] = 314;
        }
        if ( $book_code == '095107951418' ) {
            $book['head_title'] = 'Revista Minutos de Amor - Septiembre de 2020';
            $book['book_id'] = 315;
        }

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