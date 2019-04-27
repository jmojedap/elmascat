<?php
class Estadistica_Model extends CI_Model{
    
//PEDIDOS
//---------------------------------------------------------------------------------------------------
    
    function pedidos_resumen_mes($cod_rta_pol = NULL)
    {
        $this->db->select('LEFT(creado, (7)) AS periodo, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($cod_rta_pol) ) { $this->db->where('codigo_respuesta_pol', $cod_rta_pol); }
        $this->db->group_by('LEFT(creado, (7))');
        $this->db->order_by('LEFT(creado, (7)) DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }
    
    function ventas_ciudad($cod_rta_pol = NULL)
    {
        $this->db->select('ciudad_id, COUNT(pedido.id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($cod_rta_pol) ) { $this->db->where('codigo_respuesta_pol', $cod_rta_pol); }
        $this->db->group_by('ciudad_id');
        $this->db->order_by('SUM(valor_total) DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }
    
    function ventas_departamento($cod_rta_pol = NULL)
    {
        $this->db->select('lugar.region, COUNT(pedido.id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($cod_rta_pol) ) { $this->db->where('codigo_respuesta_pol', $cod_rta_pol); }
        $this->db->group_by('lugar.region');
        $this->db->join('lugar', 'lugar.id = pedido.ciudad_id', 'LEFT');
        $this->db->order_by('SUM(valor_total) DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }
    
    function productos_top()
    {
        $this->db->select('producto_id, nombre_producto, COUNT(pedido_detalle.id) cant_vendidos, SUM(pedido_detalle.precio * cantidad) AS sum_valor');
        $this->db->join('producto', 'producto.id = pedido_detalle.producto_id');
        $this->db->group_by('producto_id, nombre_producto');
        $this->db->order_by('COUNT(pedido_detalle.id)', 'DESC');
        $this->db->order_by('SUM(pedido_detalle.precio * cantidad)', 'DESC');
        $this->db->limit(50);
        $this->db->where('pedido_detalle.pedido_id IN (SELECT id FROM pedido WHERE estado_pedido > 3)');
        $query = $this->db->get('pedido_detalle');

        return $query;
    }
    
    function pedidos_resumen_anio($cod_rta_pol = NULL)
    {
        $this->db->select('LEFT(creado, (4)) AS periodo, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($cod_rta_pol) ) { $this->db->where('codigo_respuesta_pol', $cod_rta_pol); }
        $this->db->group_by('LEFT(creado, (4))');
        $this->db->order_by('creado', 'DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }

    /**
     * Query con el valor total de ventas por categoría de producto
     */
    function ventas_categoria($cod_rta_pol = NULL)
    {
        $this->db->select('producto.categoria_id, SUM(pedido_detalle.precio * cantidad) AS valor, item.item AS nombre_categoria');
        $this->db->join('producto', 'producto.id = pedido_detalle.producto_id');
        $this->db->join('item', 'item.id_interno = producto.categoria_id AND item.categoria_id = 25');
        $this->db->join('pedido', 'pedido.id = pedido_detalle.pedido_id');
        if ( ! is_null($cod_rta_pol) ) { $this->db->where('codigo_respuesta_pol', $cod_rta_pol); }
        $this->db->group_by('producto.categoria_id');
        $this->db->order_by('SUM(pedido_detalle.precio * cantidad)', 'DESC');
        $query = $this->db->get('pedido_detalle');

        return $query;
    }

    /**
     * Valor del inventario actual por cada categoría de producto
     */
    function inventario_categoria()
    {
        $this->db->select('producto.categoria_id, SUM(producto.precio * cant_disponibles) AS valor, item.item AS nombre_categoria');
        $this->db->join('item', 'item.id_interno = producto.categoria_id AND item.categoria_id = 25');
        $this->db->group_by('producto.categoria_id');
        $this->db->order_by('SUM(producto.precio * cant_disponibles)', 'DESC');
        $query = $this->db->get('producto');

        return $query;
    }
    
}