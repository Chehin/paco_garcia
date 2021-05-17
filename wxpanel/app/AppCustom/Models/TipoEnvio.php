<?php

namespace App\AppCustom\Models;

class TipoEnvio extends ModelCustomBase
{
    protected $table = 'pedido_tipo_envio';
	protected $primaryKey = 'id_tipo_envio';

    protected $guarded = [];
    
    public $timestamps = false;
}
