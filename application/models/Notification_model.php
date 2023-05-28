<?php
class Notification_model extends CI_Model{

    /**
     * Array con estilos CSS para mensajes de correo electrónico
     * 2021-07-26
     */
    function email_styles()
    {
        $email_styles = file_get_contents(URL_RESOURCES . 'css/email.json');
        $styles = json_decode($email_styles, true);

        return $styles;
    }

    function select($format = 'general')
    {
        $arr_select['general'] = 'id, title, content, status, created_at, related_3 AS alert_type, element_id, related_1, related_2';

        return $arr_select[$format];
    }

    function row($event_id)
    {
        $row = NULL;

        $this->db->select($this->select());
        $this->db->where('id', $event_id);
        $notifications = $this->db->get('events');

        if ( $notifications->num_rows() ) $row = $notifications->row();

        return $row;
    }

// Email de activación o recuperación de cuentas
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail de activación o restauración de cuenta
     * 2023-01-16
     */
    function email_activation($user_id, $activation_type = 'activation')
    {
        $user = $this->Db_model->row_id('usuario', $user_id);
            
        //Asunto de mensaje
            $subject = APP_NAME . ': Activa tu cuenta';
            if ( $activation_type == 'recovery' ) {
                $subject = APP_NAME . ' Recupera tu cuenta';
            }
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('accounts@' . APP_DOMAIN, APP_NAME);
            $this->email->to($user->email);
            //$this->email->bcc('jmojedap@gmail.com');
            $this->email->message($this->activation_message($user, $activation_type));
            $this->email->subject($subject);
            
            $this->email->send();   //Enviar
    }

    /**
     * Devuelve texto de la vista que se envía por email a un usuario para 
     * activación o restauración de su cuenta
     */
    function activation_message($user, $activation_type = 'activation')
    {
        $data['user'] = $user ;
        $data['activation_type'] = $activation_type;
        $data['view_a'] = 'admin/notifications/activation_message_v';

        $data['styles'] = $this->email_styles();
        
        $message = $this->load->view('templates/email/main', $data, TRUE);
        
        return $message;
    }

// Notificaciones de pedidos (orders)
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail de estado del pedido al cliente Tras edición de los datos
     * logísticos de un pedido
     * 2023-01-12
     */
    function orders_updated_email($order_id)
    {
        $order = $this->Db_model->row_id('pedido', $order_id);
            
        //Asunto de mensaje
            $subject = "Compra {$order->cod_pedido}: " . $this->Item_model->nombre(7, $order->estado_pedido);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'DistriCatolicas.com');
            $this->email->to($order->email);
            $this->email->bcc($this->App_model->valor_opcion(25));
            $this->email->subject($subject);
            $this->email->message($this->orders_updated_message($order));
            
            $this->email->send();   //Enviar
    }

    /**
     * HTML del mensaje que se envía tras la actualización del estado de un pedido
     * 2023-01-10
     */
    function orders_updated_message($order)
    {
        //Pedido relacionado
        $data['order'] = $order;

        $data['styles'] = $this->email_styles();
        $data['view_a'] = 'admin/notifications/orders_updated_message_v';
        
        $message = $this->load->view('templates/email/main', $data, TRUE);
        
        return $message;
    }

    /**
     * Envía el mensaje de resultado de pago al cliente
     * 2023-01-13
     */
    function orders_payment_updated_buyer_email($order_id)
    {
        $order = $this->Db_model->row_id('pedido', $order_id);
            
        //Asunto de mensaje
            $subject = "Compra {$order->cod_pedido}: " . $this->Item_model->nombre(7, $order->estado_pedido);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'DistriCatolicas.com');
            $this->email->to($order->email);
            $this->email->bcc($this->App_model->valor_opcion(25));
            $this->email->subject($subject);
            $this->email->message($this->orders_payment_updated_buyer_message($order));
            
            $this->email->send();   //Enviar       
    }

    /**
     * Mensaje en el email para el comprador después de actualizar el estado 
     * de pago de un pedido
     * 2023-01-13
     */
    function orders_payment_updated_buyer_message($order)
    {
        //Pedido relacionado
        $data['order'] = $order;
        $this->load->model('Pedido_model');
        $data['detalle'] = $this->Pedido_model->detalle($order->id);

        $data['store_address'] = $this->App_model->valor_opcion(27);

        $data['styles'] = $this->email_styles();
        $data['view_a'] = 'admin/notifications/orders_payment_updated_buyer_message_v';

        $message = $this->load->view('templates/email/main', $data, TRUE);

        return $message;
    }


// Account activation
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail notificando a un usuario activó su cuenta
     * 2021-11-18
     */
    function email_password_updated($user_id)
    {
        if ( ENV == 'production' )
        {
            
        //Variables
            $user = $this->Db_model->row_id('users', $user_id);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->subject('Your password was successfully updated');
            $this->email->from('info@' . APP_DOMAIN, APP_NAME);
            $this->email->to($user->email);
            $this->email->message($this->password_updated_message($user_id));
            
            $this->email->send();   //Enviar
        }
    }

    /**
     * Devuelve la vista con el mensaje de email para notificar que usuario
     * activó la cuenta
     * 2021-11-17
     */
    function password_updated_message($user_id)
    {
        $user = $this->Db_model->row_id('users', $user_id);

        //Usuarios relacionados
        $data['user'] = $user;

        $data['styles'] = $this->Notification_model->email_styles();
        $data['view_a'] = 'admin/notifications/email_password_updated_v';
        
        $message = $this->load->view('templates/email/main', $data, TRUE);
        
        return $message;
    }
}