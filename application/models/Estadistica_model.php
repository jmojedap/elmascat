<?php
class Estadistica_Model extends CI_Model{
    
//PEDIDOS
//---------------------------------------------------------------------------------------------------
    
    function pedidos_resumen_mes($payed = NULL)
    {
        $this->db->select('LEFT(creado, (7)) AS periodo, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($payed) ) { $this->db->where('payed', $payed); }
        $this->db->where('creado >=', '2017-01-01 00:00:00');
        $this->db->group_by('LEFT(creado, (7))');
        $this->db->order_by('LEFT(creado, (7)) ASC');
        $query = $this->db->get('pedido');
        
        return $query;
    }

    function ventas_mes($year)
    {
        $this->db->select('LEFT(creado, (7)) AS periodo, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        $this->db->where('payed', 1);    //Pago confirmado
        $this->db->where('creado >=', "{$year}-01-01 00:00:00");
        $this->db->where('creado <=', "{$year}-12-31 11:59:59");
        $this->db->group_by('LEFT(creado, (7))');
        $this->db->order_by('LEFT(creado, (7)) ASC');
        $query = $this->db->get('pedido');
        
        return $query;
    }

    function ventas_dia($qty_days)
    {
        $date = date('Y-m-d');
        $qty_days_mod = $qty_days - 1;
        $start = date('Y-m-d', strtotime($date . " - {$qty_days_mod} days"));

        $this->db->select('LEFT(creado, (10)) AS dia, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        $this->db->where('payed', 1);    //Compra pagada
        $this->db->where('creado >=', "{$start} 00:00:00");
        $this->db->group_by('LEFT(creado, (10))');
        $this->db->order_by('LEFT(creado, (10)) ASC');
        $query = $this->db->get('pedido');
        
        return $query;
    }
    
    function ventas_ciudad($payed = NULL)
    {
        $this->db->select('ciudad_id, COUNT(pedido.id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($payed) ) { $this->db->where('payed', $payed); }
        $this->db->group_by('ciudad_id');
        $this->db->order_by('SUM(valor_total) DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }
    
    function ventas_departamento($payed = NULL)
    {
        $this->db->select('lugar.region, COUNT(pedido.id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($payed) ) { $this->db->where('payed', $payed); }
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
    
    function pedidos_resumen_anio($payed = NULL)
    {
        $this->db->select('LEFT(creado, (4)) AS periodo, COUNT(id) AS cant_pedidos, SUM(total_productos) AS sum_total_productos, SUM(total_extras) AS sum_total_extras, SUM(peso_total) as sum_peso_total, SUM(valor_total) AS sum_valor_total');
        if ( ! is_null($payed) ) { $this->db->where('payed', $payed); }
        $this->db->group_by('LEFT(creado, (4))');
        $this->db->order_by('creado', 'DESC');
        $query = $this->db->get('pedido');
        
        return $query;
    }

    /**
     * Query con el valor total de ventas por categoría de producto
     */
    function ventas_categoria($payed = NULL)
    {
        $this->db->select('producto.categoria_id, SUM(pedido_detalle.precio * cantidad) AS valor, item.item AS nombre_categoria');
        $this->db->join('producto', 'producto.id = pedido_detalle.producto_id');
        $this->db->join('item', 'item.id_interno = producto.categoria_id AND item.categoria_id = 25');
        $this->db->join('pedido', 'pedido.id = pedido_detalle.pedido_id');
        if ( ! is_null($payed) ) { $this->db->where('payed', $payed); }
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

    function ventas_lugar_poblacion($tipo_id = 3)
    {
        
        $this->db->where('score_1 > 0');
        $this->db->where('tipo_id', $tipo_id);
        $this->db->order_by('score_1', 'desc');
        $lugares = $this->db->get('lugar');

        return $lugares;
    }

// Procesos masivos de actualizacion
//-----------------------------------------------------------------------------

    function update_ciudad_score_1()
    {
        $ago = new DateTime('now');
        $ago->sub(new DateInterval('P1Y'));

        $this->db->select('SUM(valor_total) AS sum_valor_total, COUNT(id) AS qty_orders, ciudad_id');
        $this->db->where('ciudad_id > 0');
        $this->db->where('payed', 1);
        $this->db->where('creado >=', $ago->format('Y-m-d H:i:s'));
        $this->db->group_by('ciudad_id');

        $ventas = $this->db->get('pedido');
        $qty_updated = 0;

        foreach ($ventas->result() as $row_venta)
        {
            $sql = "UPDATE lugar SET ";
            $sql .= " score_1 = (100000 * {$row_venta->sum_valor_total}) / poblacion";
            //$sql .= " score_1 = $row_venta->sum_valor_total";
            $sql .= ", score_2 = $row_venta->qty_orders";
            //$sql .= ", score_2 = (100000 * {$row_venta->qty_orders}) / poblacion";
            $sql .= " WHERE id = {$row_venta->ciudad_id}";
            $this->db->query($sql);
            $qty_updated += $this->db->affected_rows();
        }

        $data['ventas'] = $ventas->result();
        $data['qty_updated'] = $qty_updated;
    
        return $data;
    }

    function update_region_score_1()
    {
        $ago = new DateTime('now');
        $ago->sub(new DateInterval('P1Y'));

        $this->db->select('SUM(valor_total) AS sum_valor_total, COUNT(id) AS qty_orders, region_id');
        $this->db->where('region_id > 0');
        $this->db->where('payed', 1);
        $this->db->where('creado >=', $ago->format('Y-m-d H:i:s'));
        $this->db->group_by('region_id');

        $ventas = $this->db->get('pedido');
        $qty_updated = 0;

        foreach ($ventas->result() as $row_venta)
        {
            $sql = "UPDATE lugar SET ";
            $sql .= " score_1 = (100000 * {$row_venta->sum_valor_total}) / poblacion";
            //$sql .= " score_1 = $row_venta->sum_valor_total";
            $sql .= ", score_2 = $row_venta->qty_orders";
            //$sql .= ", score_2 = (100000 * {$row_venta->qty_orders}) / poblacion";
            $sql .= " WHERE id = {$row_venta->region_id}";
            $this->db->query($sql);
            $qty_updated += $this->db->affected_rows();
        }

        $data['ventas'] = $ventas->result();
        $data['qty_updated'] = $qty_updated;
    
        return $data;
    }
    
}