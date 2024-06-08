<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoControllers;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductosControllers;
use App\Http\Controllers\PublicApiController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TallaController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API aroutes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);

Route::post('login', [AuthController::class,'login'])->name('login');
Route::post('register', [AuthController::class,'register']);

Route::get('/productos/{id}', [ProductosControllers::class, 'mostrar']);
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/tallas/producto/{productoId}', [TallaController::class, 'getTallasPorProducto']);
Route::get('/productos/{id}/categoria', [CategoriaController::class, 'getCategoriaByProducto']);
Route::get('/productos/categoria/{categoriaId}', [CategoriaController::class, 'productosPorCategoria']);
Route::get('/stock/{productoId}/{tallaId}', [StockController::class, 'getStockPorProductoTalla']);
Route::get('/stock', [StockController::class, 'getTodoStock']);
Route::get('/stocks/{productoId}', [StockController::class, 'getProductoStock']);

Route::get('/ofertas', [OfertaController::class, 'listarOfertas']);
Route::get('/productos', [ProductosControllers::class, 'index']);

Route::get('/productos/recomendados/{id}', [ProductosControllers::class, 'productosRecomendados']);

Route::get('/categorias/{nombre}/{genero}/productos', [CategoriaController::class, 'productosPorNombreYGenero']);

Route::get('/categorias/{nombre}/productos/{estacion}', [CategoriaController::class, 'productosPorNombreYEstacion']);

Route::group(['middleware'=>['auth:api']],function(){
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);  
    Route::post('me', [AuthController::class,'me']);

    Route::get('/facturas/todas', [FacturaController::class, 'todasFacturas']);
    Route::put('/facturas/{id}/estado-envio', [FacturaController::class, 'actualizarEstadoEnvio']);
    Route::get('/facturas/cliente', [FacturaController::class, 'obtenerFacturasConNombreCliente']);
    Route::get('/facturas/topClientes', [FacturaController::class, 'topClientes']);

    Route::get('/admin/users/{id}', [AdminController::class, 'editar']);
    Route::put('/admin/users/{id}', [AdminController::class, 'actualizar']);

    Route::post('/asignar-oferta-categoria', [OfertaController::class, 'asignarOfertaCat']);
    Route::post('/quitar-oferta-categoria', [OfertaController::class, 'quitarOfertaCat']);

    Route::get('/contar', [ProductosControllers::class, 'countActiveProducts']);

    Route::get('facturas/total', [FacturaController::class, 'totalFacturas']); 
    Route::get('facturas/hoy', [FacturaController::class, 'facturasHoy']);
    Route::get('facturas/genero', [FacturaController::class, 'todasFacturasGenero']);
    Route::get('facturas/edades', [FacturaController::class, 'todasFacturasEdades']);

    Route::get('users/count', [AuthController::class, 'userCount']);

    Route::patch('productos/{id}/desactivar', [ProductosControllers::class, 'desactivar']);
    Route::patch('productos/{id}/activar', [ProductosControllers::class, 'activar']);

    Route::post('/ofertas/crear', [OfertaController::class, 'crearOferta']);
    Route::post('/asignar-oferta', [OfertaController::class, 'asignarOferta']);
    Route::put('/ofertas/{id}', [OfertaController::class, 'actualizarOferta']);
    Route::get('/ofertas/{id}', [OfertaController::class, 'obtenerOferta']);

    Route::get('/carrito/{userId}', [CarritoControllers::class, 'mostrar']);
    Route::delete('/carrito/{userId}/eliminar/{productoId}', [CarritoControllers::class, 'eliminarProducto']);

    Route::post('/carrito/agregar', [CarritoControllers::class, 'agregarProducto']);
    Route::post('/carrito/{userId}/incrementar/{productoId}', [CarritoControllers::class, 'incrementarCantidad']);
    Route::post('/carrito/{userId}/disminuir/{productoId}', [CarritoControllers::class, 'disminuirCantidad']);

    Route::post('/wishlist/agregar', [WishListController::class, 'agregar']);
    Route::delete('/wishlist/{userId}/eliminar/{productoId}', [WishListController::class, 'eliminar']);
    Route::get('/wishlist/{userId}', [WishListController::class, 'mostrar']);

    Route::put('/carrito/{userId}/cambiar-talla', [CarritoControllers::class, 'cambiarTalla']);

    Route::post('/productos/crear', [ProductosControllers::class, 'añadir']);
    Route::delete('/productos/{id}', [ProductosControllers::class, 'eliminar']);
    Route::post('/productos/actualizar/{id}', [ProductosControllers::class, 'actualizar']);

    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::post('/admin/users/anadir', [AdminController::class, 'añadir']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'eliminar']);

    Route::get('/users/{id}', [UserController::class, 'mostrar']);
    Route::put('/user/actualizar', [UserController::class, 'actualizar']);

    Route::get('/tareas', [TareaController::class, 'index']);
    Route::get('/tareas/{tarea}', [TareaController::class, 'mostrar']);
    Route::post('/tareas', [TareaController::class, 'añadir']);
    Route::put('/tareas/{tarea}', [TareaController::class, 'actualizar']);
    Route::delete('/tareas/{tarea}', [TareaController::class, 'eliminar']);

    Route::get('/tareasAdmin', [TareaController::class, 'admin']);

    Route::post('/facturas', [FacturaController::class, 'añadir']);
    Route::get('/facturas/{userId}', [FacturaController::class, 'obtenerFacturas']);

    Route::post('/stock/disminuir', [StockController::class, 'disminuirStock']);
    Route::post('/stock/incrementar/{stockId}', [StockController::class, 'incrementarStock']);
    Route::post('/stock/disminuir/{stockId}', [StockController::class, 'decrementarStock']);


    Route::post('/paypal/create-payment', [PaymentController::class, 'createPayment']);
    Route::get('/paypal/execute-payment', [PaymentController::class, 'executePayment']);
});

Route::prefix('publico')->group(function () {
    Route::get('/productos', [PublicApiController::class, 'getAllProductos']);
    Route::get('/productos/{id}', [PublicApiController::class, 'getProducto']);
    Route::get('/categorias/{categoriaId}/productos', [PublicApiController::class, 'getProductosByCategoria']);
    Route::get('/tallas', [PublicApiController::class, 'getProductosByTalla']);
    Route::get('/categorias', [PublicApiController::class, 'getAllCategorias']);
    Route::get('/ofertas', [PublicApiController::class, 'getAllOfertas']);
    Route::get('/ofertas/{id}', [PublicApiController::class, 'getOferta']);
    Route::get('/tallas/nombre/{nombre}', [PublicApiController::class, 'getTallaByNombre']);
    Route::get('/tallas/{tallaId}', [PublicApiController::class, 'getTallaById']);    
    Route::get('/productos/nombre/{nombre}', [PublicApiController::class, 'getProductosByNombre']);
    Route::get('/productos/precio/{precio}', [PublicApiController::class, 'getProductosByPrecio']);
});
