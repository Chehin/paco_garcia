<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
	
	const RESOURCE = 'permission';
	const RESOURCE_LABEL = 'Permisos';
	
	
	static $aAllPermissions = [
		'dash' => [
			'label' => 'Dash. Por Mes',
			'aPermissions' => [
				'dash.create' => '',
				'dash.update' => '',
				'dash.view' => '',
				'dash.delete' => '',
				
			]
		],
		'dash2' => [
			'label' => 'Dash. Por Producto',
			'aPermissions' => [
				'dash2.create' => '',
				'dash2.update' => '',
				'dash2.view' => '',
				'dash2.delete' => '',
				
			]
		],
		'dash3' => [
			'label' => 'Dash. Por Rubro',
			'aPermissions' => [
				'dash3.create' => '',
				'dash3.update' => '',
				'dash3.view' => '',
				'dash3.delete' => '',
				
			]
		],
		'user' => [
			'label' => 'Usuarios',
			'aPermissions' => [
				'user.create' => 'Usuarios: Crear Usuarios',
				'user.update' => 'Usuarios: Modificar datos',
				'user.view' => 'Usuarios: Ver listado de usuarios y detalle',
				'user.delete' => 'Usuarios: Borrar usuarios',
				
			]
		],
		
		'role' => [
			'label' => 'Perfiles',
			'aPermissions' => [
				'role.create' => 'Perfiles: Crear',
				'role.update' => 'Perfiles: Modificar',
				'role.view' => 'Perfiles: Ver',
				'role.delete' => 'Perfiles: Borrar',
				
			]
		],
		'empresaSis' => [
			'label' => 'La Empresa',
			'aPermissions' => [
				'empresaSis.create' => '',
				'empresaSis.update' => '',
				'empresaSis.view' => '',
				'empresaSis.delete' => '',
				
			]
		],
		
		'comprobante' => [
			'label' => 'Comprobantes',
			'aPermissions' => [
				'comprobante.create' => '',
				'comprobante.update' => '',
				'comprobante.view' => '',
				'comprobante.delete' => '',
			]
		],
		'rubros' => [
			'label' => 'Rubros',
			'aPermissions' => [
				'rubros.create' => 'Rubros: Crear',
				'rubros.update' => 'Rubros: Modificar',
				'rubros.view' => 'Rubros: Ver',
				'rubros.delete' => 'Rubros: Borrar',
				
			]
		],
		'subRubros' => [
			'label' => 'Sub Rubros',
			'aPermissions' => [
				'subRubros.create' => 'Sub Rubros: Crear',
				'subRubros.update' => 'Sub Rubros: Modificar',
				'subRubros.view' => 'Sub Rubros: Ver',
				'subRubros.delete' => 'Sub Rubros: Borrar',
				
			]
		],
		/*'subsubRubros' => [
			'label' => 'Sub Sub Rubros',
			'aPermissions' => [
				'subsubRubros.create' => 'Sub Sub Rubros: Crear',
				'subsubRubros.update' => 'Sub Sub Rubros: Modificar',
				'subsubRubros.view' => 'Sub Sub Rubros: Ver',
				'subsubRubros.delete' => 'Sub Sub Rubros: Borrar',
				
			]
		],*/
		'deportes' => [
			'label' => 'Deportes',
			'aPermissions' => [
				'deportes.create' => 'Deportes: Crear',
				'deportes.update' => 'Deportes: Modificar',
				'deportes.view' => 'Deportes: Ver',
				'deportes.delete' => 'Deportes: Borrar',
				
			]
		],
		'etiquetas' => [
			'label' => 'Etiquetas',
			'aPermissions' => [
				'etiquetas.create' => 'Etiquetas: Crear',
				'etiquetas.update' => 'Etiquetas: Modificar',
				'etiquetas.view' => 'Etiquetas: Ver',
				'etiquetas.delete' => 'Etiquetas: Borrar',
				
			]
		],
		'productos' => [
			'label' => 'Productos',
			'aPermissions' => [
				'productos.create' => 'Productos: Crear',
				'productos.update' => 'Productos: Modificar',
				'productos.view' => 'Productos: Ver',
				'productos.delete' => 'Productos: Borrar',
				
			]
		],
		'importarProductos' => [
			'label' => 'Importar Productos',
			'aPermissions' => [
				'importarProductos.create' => 'Importar Productos: Crear',
				'importarProductos.view' => 'Importar Productos: Ver listado y detalle',
				
			]
		],
		'marcas' => [
			'label' => 'Marcas',
			'aPermissions' => [
				'marcas.create' => 'Marcas: Crear',
				'marcas.update' => 'Marcas: Modificar',
				'marcas.view' => 'Marcas: Ver',
				'marcas.delete' => 'Marcas: Borrar',
				
			]
		],
		'colores' => [
			'label' => 'Colores',
			'aPermissions' => [
				'colores.create' => 'Colores: Crear',
				'colores.update' => 'Colores: Modificar',
				'colores.view' => 'Colores: Ver',
				'colores.delete' => 'Colores: Borrar',
				
			]
		],
		'talles' => [
			'label' => 'Talles',
			'aPermissions' => [
				'talles.create' => 'Talles: Crear',
				'talles.update' => 'Talles: Modificar',
				'talles.view' => 'Talles: Ver',
				'talles.delete' => 'Talles: Borrar',
				
			]
		],
		'monedas' => [
			'label' => 'Monedas',
			'aPermissions' => [
				'monedas.create' => 'Monedas: Crear',
				'monedas.update' => 'Monedas: Modificar',
				'monedas.view' => 'Monedas: Ver',
				'monedas.delete' => 'Monedas: Borrar',
				
			]
		],
		'general' => [
			'label' => 'General',
			'aPermissions' => [
				'general.update' => 'General: Modificar',
				'general.view' => 'General: Ver',
			]
		],
		'banners' => [
			'label' => 'Banners',
			'aPermissions' => [
				'banners.create' => 'Banners: Crear',
				'banners.update' => 'Banners: Modificar',
				'banners.view' => 'Banners: Ver',
				'banners.delete' => 'Banners: Borrar',
				
			]
		],
		'bannersClientes' => [
			'label' => 'Banners Clientes',
			'aPermissions' => [
				'bannersClientes.create' => 'Banners Clientes: Crear',
				'bannersClientes.update' => 'Banners Clientes: Modificar',
				'bannersClientes.view' => 'Banners Clientes: Ver',
				'bannersClientes.delete' => 'Banners Clientes: Borrar',
				
			]
		],
		'bannersPosiciones' => [
			'label' => 'Banners Posiciones',
			'aPermissions' => [
				'bannersPosiciones.create' => 'Banners Posiciones: Crear',
				'bannersPosiciones.update' => 'Banners Posiciones: Modificar',
				'bannersPosiciones.view' => 'Banners Posiciones: Ver',
				'bannersPosiciones.delete' => 'Banners Posiciones: Borrar',
				
			]
		],
		'bannersTipos' => [
			'label' => 'Tipos de Banners',
			'aPermissions' => [
				'bannersTipos.create' => 'Tipos de Banners: Crear',
				'bannersTipos.update' => 'Tipos de Banners: Modificar',
				'bannersTipos.view' => 'Tipos de Banners: Ver',
				'bannersTipos.delete' => 'Tipos de Banners: Borrar',
				
			]
		],
		'pedidos3' => [
			'label' => 'Pedidos. A Acordar',
			'aPermissions' => [
				'pedidos3.update' => 'Pedidos: Modificar',
				'pedidos3.view' => 'Pedidos: Ver',				
			]
		],
		'pedidos2' => [
			'label' => 'Pedidos. En Carrito',
			'aPermissions' => [
				'pedidos2.update' => 'Pedidos: Modificar',
				'pedidos2.view' => 'Pedidos: Ver',				
			]
		],
		'pedidos1' => [
			'label' => 'Pedidos. A Gestionar',
			'aPermissions' => [
				'pedidos1.update' => 'Pedidos: Modificar',
				'pedidos1.view' => 'Pedidos: Ver',				
			]
		],
		'pedidos' => [
			'label' => 'Pedidos. Todos',
			'aPermissions' => [
				/* 'pedidos.create' => 'Pedidos: Crear', */
				'pedidos.update' => 'Pedidos: Modificar',
				'pedidos.view' => 'Pedidos: Ver',
				'pedidos.delete' => 'Pedidos: Borrar',
				
			]
		],

		'pedidosMeli' => [
			'label' => 'Pedidos. Meli',
			'aPermissions' => [
				/* 'pedidos.create' => 'Pedidos: Crear', */
				'pedidosMeli.update' => 'Pedidos: Modificar',
				'pedidosMeli.view' => 'Pedidos: Ver',
				'pedidosMeli.delete' => 'Pedidos: Borrar',
				
			]
		],

		'pedidosClientes' => [
			'label' => 'Pedidos Clientes',
			'aPermissions' => [
				'pedidosClientes.create' => 'Pedidos Clientes: Crear',
				'pedidosClientes.update' => 'Pedidos Clientes: Modificar',
				'pedidosClientes.view' => 'Pedidos Clientes: Ver',
				'pedidosClientes.delete' => 'Pedidos Clientes: Borrar',
				
			]
		],
		'news' => [
			'label' => 'Notas',
			'aPermissions' => [
				'news.create' => 'Notas: Crear Notas',
				'news.update' => 'Notas: Modificar datos',
				'news.view' => 'Notas: Ver listado de Notas y detalle',
				'news.delete' => 'Notas: Borrar Notas',
				
			]
		],
		'slider' => [
			'label' => 'Notas Slider',
			'aPermissions' => [
				'slider.create' => 'Notas Slider: Crear Slider',
				'slider.update' => 'Notas Slider: Modificar datos',
				'slider.view' => 'Notas Slider: Ver listado de Slider y detalle',
				'slider.delete' => 'Notas Slider: Borrar Slider',
				
			]
		],
		'sucursales' => [
			'label' => 'Sucursales',
			'aPermissions' => [
				'sucursales.create' => 'Sucursalesr: Crear Sucursales',
				'sucursales.update' => 'Sucursales: Modificar datos',
				'sucursales.view' => 'Sucursales: Ver listado de Sucursales y detalle',
				'sucursales.delete' => 'Sucursales: Borrar Sucursales',
				
			]
		],/*,
		'newsletter' => [
            'label' => 'Newsletter',
            'aPermissions' => [
                'newsletter.create' => 'Newsletter: Crear Newsletter',
                'newsletter.update' => 'Newsletter: Modificar datos',
                'newsletter.view' => 'Newsletter: Ver listado de Newsletter y detalle',
                'newsletter.delete' => 'Newsletter: Borrar Newsletter',
                
            ]
		],*/
		
		'blog' => [
			'label' => 'Blog',
			'aPermissions' => [
				'blog.create' => 'Blog: Crear Notas',
				'blog.update' => 'Blog: Modificar datos',
				'blog.view' => 'Blog: Ver listado de Notas y detalle',
				'blog.delete' => 'Blog: Borrar Notas',
				
			]
		],
		'etiquetasBlog' => [
			'label' => 'Etiquetas Blog',
			'aPermissions' => [
				'etiquetasBlog.create' => 'Etiquetas: Crear',
				'etiquetasBlog.update' => 'Etiquetas: Modificar',
				'etiquetasBlog.view' => 'Etiquetas: Ver',
				'etiquetasBlog.delete' => 'Etiquetas: Borrar',
				
			]
		],
		'banners2' => [
			'label' => 'CTO',
			'aPermissions' => [
				'banners2.create' => 'Banners: Crear',
				'banners2.update' => 'Banners: Modificar',
				'banners2.view' => 'Banners: Ver',
				'banners2.delete' => 'Banners: Borrar',
				
			]
		],
		'banners2Posiciones' => [
			'label' => 'CTO Posiciones',
			'aPermissions' => [
				'banners2Posiciones.create' => 'Banners Posiciones: Crear',
				'banners2Posiciones.update' => 'Banners Posiciones: Modificar',
				'banners2Posiciones.view' => 'Banners Posiciones: Ver',
				'banners2Posiciones.delete' => 'Banners Posiciones: Borrar',
				
			]
		],
		'banners2Tipos' => [
			'label' => 'Tipos de CTO',
			'aPermissions' => [
				'banners2Tipos.create' => 'Tipos de Banners: Crear',
				'banners2Tipos.update' => 'Tipos de Banners: Modificar',
				'banners2Tipos.view' => 'Tipos de Banners: Ver',
				'banners2Tipos.delete' => 'Tipos de Banners: Borrar',
				
			]
		],
		'marketingPersonas' => [
            'label' => 'Marketing Personas',
            'aPermissions' => [
                'marketingPersonas.create' => 'Marketing Personas: Crear Marketing Personas',
                'marketingPersonas.update' => 'Marketing Personas: Modificar datos',
                'marketingPersonas.view' => 'Marketing Personas: Ver listado de Marketing Personas y detalle',
                'marketingPersonas.delete' => 'Marketing Personas: Borrar Marketing Personas',
                
            ]
        ],
        'marketingEmpresas' => [
            'label' => 'Marketing Empresas',
            'aPermissions' => [
                'marketingEmpresas.create' => 'Marketing Empresas: Crear Marketing Empresas',
                'marketingEmpresas.update' => 'Marketing Empresas: Modificar datos',
                'marketingEmpresas.view' => 'Marketing Empresas: Ver listado de Marketing Empresas y detalle',
                'marketingEmpresas.delete' => 'Marketing Empresas: Borrar Marketing Empresas',
                
            ]
        ],
		'marketingListas' => [
            'label' => 'Marketing Listas',
            'aPermissions' => [
                'marketingListas.create' => 'Marketing Listas: Crear Listas Marketing',
                'marketingListas.update' => 'Marketing Empresas: Modificar datos',
                'marketingListas.view' => 'Marketing Listas: Ver listado de Listas Marketing y detalle',
                'marketingListas.delete' => 'Marketing Listas: Borrar Listas Marketing',
                
            ]
		],

		'maillingTemplates' => [
            'label' => 'Mailling Templates',
            'aPermissions' => [
                'maillingTemplates.create' => 'Mailling Templates: Crear Templates Mailling',
                'maillingTemplates.update' => 'Mailling Templates: Modificar datos',
                'maillingTemplates.view' => 'Mailling Templates: Ver listado de Templates Mailling',
                'maillingTemplates.delete' => 'Mailling Templates: Borrar Templates Mailling',
            ]
		],
		
		'maillingDiagramador' => [
            'label' => 'Mailling Diagramador',
            'aPermissions' => [
                'maillingDiagramador.create' => 'Mailling Diagramador: Crear Mailling',
                'maillingDiagramador.update' => 'Mailling Diagramador: Modificar datos',
                'maillingDiagramador.view' => 'Mailling Diagramador: Ver listado Mailling',
                'maillingDiagramador.delete' => 'Mailling Diagramador: Borrar Mailling',
                ]
		],

		'maillingDiagramadorEdit' => [
            'label' => 'Mailling Diagramador',
            'aPermissions' => [
                'maillingDiagramadorEdit.create' => 'Mailling Diagramador: Crear Mailling',
                'maillingDiagramadorEdit.update' => 'Mailling Diagramador: Modificar datos',
                'maillingDiagramadorEdit.view' => 'Mailling Diagramador: Ver listado Mailling',
                'maillingDiagramadorEdit.delete' => 'Mailling Diagramador: Borrar Mailling',
                ]
		],
		
		'maillingTipos' => [
            'label' => 'Mailling Tipos',
            'aPermissions' => [
                'maillingTipos.create' => 'Mailling Tipos: Crear Tipos Mailling',
                'maillingTipos.update' => 'Mailling Tipos: Modificar datos',
                'maillingTipos.view' => 'Mailling Tipos: Ver listado Mailling',
                'maillingTipos.delete' => 'Mailling Tipos: Borrar Mailling',
                ]
		],
		

		'maillingCampanias' => [
            'label' => 'Mailling Campanias',
            'aPermissions' => [
                'maillingCampanias.create' => 'Mailling Campanias: Crear Campanias Mailling',
                'maillingCampanias.update' => 'Mailling Campanias: Modificar datos',
                'maillingCampanias.view' => 'Mailling Campanias: Ver listado de Campanias Mailling',
                'maillingCampanias.delete' => 'Mailling Campanias: Borrar Campanias Mailling',
            ]
		],
		

		'maillingEstadisticas' => [
            'label' => 'Mailling Estadisticas',
            'aPermissions' => [
                'maillingEstadisticas.create' => 'Mailling Estadisticas: Crear Estadisticas Mailling',
                'maillingEstadisticas.update' => 'Mailling Estadisticas: Modificar datos',
                'maillingEstadisticas.view' => 'Mailling Estadisticas: Ver listado de Estadisticas Mailling',
                'maillingEstadisticas.delete' => 'Mailling Estadisticas: Borrar Estadisticas Mailling',
            ]
		],
		
		'maillingEstadisticasSimples' => [
            'label' => 'Mailling Estadisticas Simples',
            'aPermissions' => [
                'maillingEstadisticasSimples.create' => 'Mailling Estadisticas Simples: Crear Estadisticas Mailling',
                'maillingEstadisticasSimples.update' => 'Mailling Estadisticas Simples: Modificar datos',
                'maillingEstadisticasSimples.view' => 'Mailling Estadisticas Simples: Ver listado de Estadisticas Mailling',
                'maillingEstadisticasSimples.delete' => 'Mailling Estadisticas Simples: Borrar Estadisticas Mailling',
            ]
        ],

        'maillingTemplates' => [
            'label' => 'Mailling Templates',
            'aPermissions' => [
                'maillingTemplates.create' => 'Mailling Templates: Crear Templates Mailling',
                'maillingTemplates.update' => 'Mailling Templates: Modificar datos',
                'maillingTemplates.view' => 'Mailling Templates: Ver listado de Templates Mailling',
                'maillingTemplates.delete' => 'Mailling Templates: Borrar Templates Mailling',
            ]
		],
        
        'maillingEstadisticasReport' => [
            'label' => 'Mailling Estadisticas Reportes',
            'aPermissions' => [
                'maillingEstadisticasReport.create' => 'Mailling Estadisticas Report: Crear Estadisticas Mailling',
                'maillingEstadisticasReport.update' => 'Mailling Estadisticas Report: Modificar datos',
                'maillingEstadisticasReport.view' => 'Mailling Estadisticas Report: Ver listado de Estadisticas Mailling',
                'maillingEstadisticasReport.delete' => 'Mailling Estadisticas Report: Borrar Estadisticas Mailling',
            ]
		],

		'maillingEstadisticasAbReport' => [
            'label' => 'Mailling Estadisticas AB Reportes',
            'aPermissions' => [
                'maillingEstadisticasAbReport.create' => 'Mailling Estadisticas Report: Crear Estadisticas Mailling',
                'maillingEstadisticasAbReport.update' => 'Mailling Estadisticas Report: Modificar datos',
                'maillingEstadisticasAbReport.view' => 'Mailling Estadisticas Report: Ver listado de Estadisticas Mailling',
                'maillingEstadisticasAbReport.delete' => 'Mailling Estadisticas Report: Borrar Estadisticas Mailling',
            ]
        ],
        
        'pedidosClientes' => [
			'label' => 'Pedidos Clientes',
			'aPermissions' => [
				'pedidosClientes.create' => 'Pedidos Clientes: Crear',
				'pedidosClientes.update' => 'Pedidos Clientes: Modificar',
				'pedidosClientes.view' => 'Pedidos Clientes: Ver',
				'pedidosClientes.delete' => 'Pedidos Clientes: Borrar',
				
			]
        ],
        'importarClientes' => [
			'label' => 'Importar Clientes',
			'aPermissions' => [
				'importarClientes.create' => 'Importar Clientes: Crear',
				'importarClientes.view' => 'Importar Clientes: Ver listado y detalle',
				
			]
		]
	];
	
	protected $type;



	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.panel');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	$aResult = Util::getDefaultArrayResult();
        
        $item = \Sentinel::findRoleById($id);
        
        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'item' => $item,
				'aPermissions' => static::$aAllPermissions,
				'resourceLabel' => static::RESOURCE_LABEL,
				'resource' => static::RESOURCE,
            );
			
			$viewModule = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

            $aResult['html'] = \View::make($viewModule . '.' . static::RESOURCE."Edit")
                ->with('aViewData', $aViewData)
                ->render()
            ;
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
		$aResult = Util::getDefaultArrayResult();
		
		$roleResource = \config('appCustom.roleType.panel') == $this->type ? 'role' : 'rolePc'; 
        
        if (\Sentinel::hasAccess($roleResource  . '.update')) {
        
            $item = \Sentinel::findRoleById($id);

            if ($item) {
                $aPerms = $request->input('aPerms', []);
				
		$aPermsAux = [];
		array_walk($aPerms, function($value, $key) use (&$aPermsAux){
			$aPermsAux[$value] = true;
		});

		$item->permissions = $aPermsAux;

		$item->save();

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            } 
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
		return response()->json($aResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($role = \Sentinel::findRoleById($id)) {
			
			$roleResource = \config('appCustom.roleType.panel') == $this->type ? 'role' : 'rolePc'; 
			
			if (\Sentinel::hasAccess($roleResource  . '.delete')) { 
				
				if (!($role->users()->with('roles')->get()->count() > 0)) {
					$role->delete();
				} else {
					$aResult['status'] = 1;
					$aResult['msg'] = 'El rol tiene usuarios asignados';
				}
				 
			} else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
    }
}
