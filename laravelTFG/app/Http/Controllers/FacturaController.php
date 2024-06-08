<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Factura;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacturaController extends Controller
{

    public function añadir(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'total' => 'required|numeric',
            'detalle' => 'required|array',
        ]);

        $factura = new Factura();
        $factura->user_id = $request->userId;
        $factura->total = $request->total;
        $factura->detalle = json_encode($request->detalle);
        $factura->save();

        return response()->json(['message' => 'Factura creada con éxito', 'factura' => $factura], 201);
    }

    public function obtenerFacturas($userId)
    {
        $facturas = Factura::where('user_id', $userId)->get();
        return response()->json($facturas);
    }

    public function totalFacturas()
        {
            $total = Factura::sum('total');
            return response()->json(['total' => $total]);
        }

        public function facturasHoy()
    {
        $facturasHoy = Factura::whereDate('created_at', today())->count();
        return response()->json(['facturas_hoy' => $facturasHoy]);
    }

    public function todasFacturas()
        {
            $facturas = Factura::all();
            return response()->json($facturas);
        }

    public function actualizarEstadoEnvio(Request $request, $id)
    {
        $request->validate([
            'status_envio' => 'required|in:enviado,procesando,recibido',
        ]);
    
        $factura = Factura::findOrFail($id);
        $factura->status_envio = $request->status_envio;
        $factura->save();
    
        return response()->json(['message' => 'Estado de envío actualizado con éxito', 'factura' => $factura]);
    }

    public function obtenerFacturasConNombreCliente()
    {
        $facturas = Factura::with('user')->get();
        return response()->json($facturas);
    }

    public function todasFacturasGenero()
    {
        try {
            $facturas = Factura::with('user')->get();
            
            $generoCounts = [
                'hombre' => 0,
                'mujer' => 0,
            ];
            
            foreach ($facturas as $factura) {
                if ($factura->user) {
                    $genero = $factura->user->genero;
                    if (isset($generoCounts[$genero])) {
                        $generoCounts[$genero]++;
                    }
                }
            }

            $data = [];
            foreach ($generoCounts as $genero => $count) {
                $data[] = [
                    'name' => ucfirst($genero),
                    'value' => $count,
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching invoices', 'message' => $e->getMessage()], 500);
        }
    }   

    public function todasFacturasEdades()
    {
        try {
            $facturas = Factura::with('user')->get();
            $fechaActual = Carbon::now();
            $edadesCounts = [
                '+18' => 0,
                '+30' => 0,
                '+40' => 0,
                '+50' => 0,
                '+60' => 0,
            ];
            
            foreach ($facturas as $factura) {
                if ($factura->user && $factura->user->fecha_nacimiento) {
                    $fechaNacimiento = Carbon::parse($factura->user->fecha_nacimiento);
                    $edad = $fechaActual->diffInYears($fechaNacimiento);

                    if ($edad >= 18 && $edad < 30) {
                        $edadesCounts['+18']++;
                    } elseif ($edad >= 30 && $edad < 40) {
                        $edadesCounts['+30']++;
                    } elseif ($edad >= 40 && $edad < 50) {
                        $edadesCounts['+40']++;
                    } elseif ($edad >= 50 && $edad < 60) {
                        $edadesCounts['+50']++;
                    } elseif ($edad >= 60) {
                        $edadesCounts['+60']++;
                    }
                }
            }

            $data = [];
            foreach ($edadesCounts as $rangoEdad => $count) {
                $data[] = [
                    'name' => $rangoEdad,
                    'value' => $count,
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching invoices', 'message' => $e->getMessage()], 500);
        }
    }

    public function topClientes()
    {
        try {
            $topCustomers = Factura::selectRaw('user_id, SUM(total) as total_spent')
                ->groupBy('user_id')
                ->orderByDesc('total_spent')
                ->limit(10)
                ->get();

            $detailedCustomers = [];
            foreach ($topCustomers as $customer) {
                $user = User::find($customer->user_id);
                if ($user) {
                    $detailedCustomers[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'totalSpent' => $customer->total_spent,
                    ];
                }
            }

            return response()->json($detailedCustomers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching top customers', 'message' => $e->getMessage()], 500);
        }
    }
}
