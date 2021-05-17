<?php
namespace App\AppCustom\Models;

class PedidosProductos extends ModelCustomBase
{
	
    protected $table = 'pedidos_productos';
	protected $primaryKey = 'id_pedido_producto';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
	
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
	
}