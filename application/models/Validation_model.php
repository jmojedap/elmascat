<?php
class Validation_model extends CI_Model{


// Usuarios
//-----------------------------------------------------------------------------

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function username($user_id = null)
    {
        $validation['username_unique'] = $this->Db_model->is_unique('usuario', 'username', $this->input->post('username'), $user_id);
        return $validation;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2019-10-29
     */
    function email($user_id = null)
    {
        //Valores por defecto
        $validation['email_unique'] = TRUE;

        //Si tiene algún valor escrito
        if ( strlen($this->input->post('email')) > 0 )
        {
            $validation['email_unique'] = $this->Db_model->is_unique('usuario', 'email', $this->input->post('email'), $user_id);
        }

        return $validation;
    }

    /**
     * Valida que número de identificacion (id_number) sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function id_number($user_id = null)
    {
        $validation['id_number_unique'] = $this->Db_model->is_unique('usuario', 'no_documento', $this->input->post('no_documento'), $user_id);
        return $validation;
    }
}