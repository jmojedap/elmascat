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
        //$this->output->enable_profiler(TRUE);
        $data = $this->Book_model->book_info($book_code);

        $this->load->helper('file');

        $folder = PATH_CONTENT . "books/{$book_code}/read/";
        $pages = get_filenames($folder);
        sort($pages);

        $data['pages'] = $pages;
        $data['url_folder'] = URL_CONTENT . "books/{$book_code}/";
        $data['format'] = $format;
        $data['book_code'] = $book_code;
        $data['meta_id'] = $meta_id;
        $data['slug'] = $slug;

        //Guardar evento de apertura
        //$this->Post_model->save_open_event($data['book_id']);

        //Índice
        $str_index = file_get_contents(PATH_CONTENT . "books/{$book_code}/index_01.json");
        $data['book_index'] = $str_index;

        //Páginas Drive
        $str_pages = file_get_contents(PATH_CONTENT . "books/{$book_code}/pages_{$format}.json");
        $data['drive_pages'] = $str_pages;

        $view = 'templates/reader/reader_v'; 
        $this->load->view($view, $data);
    }

    /**
     * Demo de un libro en línea
     * 2020-04-25
     */
    function demo()
    {
        $book_code = '069243559697';
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

// Generación de archivos
//-----------------------------------------------------------------------------

    function files_generator($book_code = '')
    {
        $data['book_code'] = $book_code;
        $data['head_title'] = 'Generador de archivos';
        $data['view_a'] = 'books/files_generator_v';

        $data['books'] = $this->db->select('id, code, nombre_post')
                        ->where('tipo_id = 8 AND estado = 2')
                        ->order_by('code', 'ASC')
                        ->get('post');

        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * 1) Cambiar nombres de archivos originales
     * 2021-10-20
     */
    function rename_pages()
    {
        $book_code = $this->input->post('book_code');
        $prefix = $this->input->post('prefix');

        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);
        $i = 0;
        $this->load->helper('string');

        $renamed_pages = array();
        foreach ( $pages as $file)
        {
            $new_name = str_replace($prefix,'',$file);
            $new_name = str_replace('.jpg','',$new_name);
            $new_name = substr('00' . $new_name,-3) . '_' . random_string('numeric', 6) . '.jpg';
            
            rename(PATH_CONTENT . "books/{$book_code}/org/" . $file, PATH_CONTENT . "books/{$book_code}/org/" . $new_name);

            $renamed_pages[] = $file . ' > ' . $new_name;
        }

        $data['status'] = (count($renamed_pages)) ? 1 : 0;
        $data['renamed_pages'] = $renamed_pages;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * 2) Crear imagenes para lectura
     */
    function create_read()
    {
        $book_code = $this->input->post('book_code');

        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);

        $this->load->library('image_lib');
        $created = array();
        foreach ( $pages as $file)
        {

            //Config
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['source_image'] = PATH_CONTENT . "books/{$book_code}/org/" . $file;
                $config['new_image'] = PATH_CONTENT . "books/{$book_code}/read/" . $file;
                $config['width'] = 650;
                $config['quality'] = 95;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            
                $created[] = $file;
        }

        $data['status'] = (count($created)) ? 1 : 0;
        $data['created'] = $created;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function create_drive()
    {
        $book_code = $this->input->post('book_code');
        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/org/";
        $pages = get_filenames($folder);

        $this->load->library('image_lib');
        $created = array();
        foreach ( $pages as $file)
        {

            //Config
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['source_image'] = PATH_CONTENT . "books/{$book_code}/org/" . $file;
                $config['new_image'] = PATH_CONTENT . "books/{$book_code}/drive/" . $file;
                $config['width'] = 650;
                $config['quality'] = 100;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            
                $created[] = $file;
        }

        $data['status'] = (count($created)) ? 1 : 0;
        $data['created'] = $created;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }

    function create_mini()
    {
        $book_code = $this->input->post('book_code');
        $this->load->helper('file');
        $folder = PATH_CONTENT . "books/{$book_code}/read/";
        $pages = get_filenames($folder);

        $this->load->library('image_lib');
        foreach ( $pages as $file)
        {

            //Config
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['source_image'] = PATH_CONTENT . "books/{$book_code}/read/" . $file;
                $config['new_image'] = PATH_CONTENT . "books/{$book_code}/mini/" . $file;
                $config['width'] = 72;
                $config['quality'] = 95;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            
                $created[] = $file;
        }

        $data['status'] = (count($created)) ? 1 : 0;
        $data['created'] = $created;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}