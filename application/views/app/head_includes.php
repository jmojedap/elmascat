<?php

    /**
     * Inclusión de scripts adicionales, desde el controlador, var $data['head_includes]
     * 
     * Si se requiere cargar algún código adicional en el head se cargan los segmentos de código del head 
     * de la página definidos en el array $head_includes, esta variable se define en la función del controlador
     * como $data['head_includes']
     */

      if ( isset($head_includes) ):
          foreach ($head_includes as $value):
              $this->load->view("assets/{$value}");
          endforeach;
      endif;

?>