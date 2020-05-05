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

    function read($book_code, $meta_id = 0, $slug = '', $format = 'mix')
    {
        $data = $this->Book_model->book_info($book_code);

        $this->load->helper('file');

        $folder = PATH_CONTENT . "books/{$book_code}/read/";
        $pages = get_filenames($folder);
        sort($pages);

        $data['pages'] = $pages;
        $data['url_folder'] = URL_CONTENT . "books/{$book_code}/";
        $data['format'] = $format;

        //Guardar evento de apertura
        $this->Post_model->save_open_event($data['book_id']);

        //Índice
        $str_index = file_get_contents(PATH_CONTENT . "books/{$book_code}/index_01.json");
        $data['book_index'] = $str_index;

        //Páginas Drive
        $str_pages = file_get_contents(PATH_CONTENT . "books/{$book_code}/pages_{$format}.json");
        $data['drive_pages'] = $str_pages;

        //$view = 'templates/reader/demo_v';
        if ( $this->Book_model->readable($meta_id) ) {
            $view = 'templates/reader/reader_v'; 
            $this->load->view($view, $data);
        } else {
            redirect('app/no_permitido');
        }
    }

    /**
     * Demo de un libro en línea
     * 2020-04-25
     */
    function demo()
    {
        $book_code = 919580345880;
        $data = $this->Book_model->book_info($book_code);

        $this->load->helper('file');

        $folder = PATH_CONTENT . "books/{$book_code}/read/";
        $pages = get_filenames($folder);
        sort($pages);

        $data['pages'] = $pages;
        $data['url_folder'] = URL_CONTENT . "books/{$book_code}/";

        //Guardar evento de apertura
        $this->Post_model->save_open_event($data['book_id']);

        //Índice
        $str_index = file_get_contents(PATH_CONTENT . "books/{$book_code}/index_01.json");
        $data['book_index'] = $str_index;

        $this->load->view('templates/reader/reader_v', $data);
    }

    function rename_pages($book_code = '919580345880')
    {
        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);
        $i = 0;
        $this->load->helper('string');

        foreach ( $pages as $file)
        {
            $new_name = str_replace('Mayo','',$file);
            $new_name = str_replace('.jpg','',$new_name);
            $new_name = substr('00' . $new_name,-3) . '_' . random_string('numeric', 6) . '.jpg';
            echo $file . ' > ' . $new_name;
            echo '<br>';

            rename(PATH_CONTENT . "books/{$book_code}/org/" . $file, PATH_CONTENT . "books/{$book_code}/org/" . $new_name);
        }
    }

    function create_pages($book_code = '431854596068')
    {
        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);

        $this->load->library('image_lib');
        foreach ( $pages as $file)
        {

            //Config
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['source_image'] = PATH_CONTENT . "books/{$book_code}/org/" . $file;
                $config['new_image'] = PATH_CONTENT . "books/{$book_code}/mobile/" . $file;
                
                //$config['source_image'] = PATH_CONTENT . "books/{$book_code}/zoom/" . $file;
                //$config['new_image'] = PATH_CONTENT . "books/{$book_code}/read/" . $file;
                
                //$config['new_image'] = PATH_CONTENT . "books/{$book_code}/mini/" . $file;
                $config['width'] = 375;
                $config['quality'] = 95;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            
                echo $file;
                echo '<br>';
        }
    }
}