<?php
$this->load->view('assets/grocery_crud');

if ( ! IS_NULL($vista_menu) ){ $this->load->view($vista_menu);}

echo $output;