<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Usuario_model');
        $this->load->model('Book_model');
        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

// Lectura
//-----------------------------------------------------------------------------

    function read($book_code)
    {
        $data = $this->Book_model->book_info($book_code);

        $this->load->helper('file');

        $folder = PATH_CONTENT . "books/{$book_code}/read/";
        $pages = get_filenames($folder);
        sort($pages);

        $data['pages'] = $pages;
        $data['url_folder'] = URL_CONTENT . "books/{$book_code}/";

        //Índice
        $str_index = file_get_contents(PATH_CONTENT . "books/{$book_code}/index_01.json");
        $data['book_index'] = $str_index;

        $this->load->view('templates/reader/reader_v', $data);
    }

    function rename_pages($book_code = 'mda_202004')
    {
        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);

        foreach ( $pages as $file)
        {
            $new_name = substr($file,3);
            $new_name = substr($new_name,0,-4) + 1;
            $new_name = '00' . $new_name;
            $new_name = substr($new_name,-3) . '.jpg';
            echo $file . ' > ' . $new_name;
            echo '<br>';

            rename(PATH_CONTENT . "books/{$book_code}/org/" . $file, PATH_CONTENT . "books/{$book_code}/org/" . $new_name);
        }
    }
}