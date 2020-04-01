<?php

class Book_model extends CI_Model{
    
    /**
     * Información básica de los libros virtuales
     * 2020-03-28
     */
    function book_info($book_code)
    {
        $books = array(
            'mda_202004' => array(
                'head_title' => 'Abril 2020 :: Revista Minutos de Amor '
            )
        );

        return $books[$book_code];
    }
}