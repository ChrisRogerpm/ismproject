<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Gestor extends Model
{
    protected $table = "gestor";
    protected $primaryKey = "idGestor";
    protected $fillable = [
        'idCeo',
        'idMesa',
        'codigoGestor',
        'nombre',
        'telefono',
        'nroDocumento',
        'estado',
    ];
    public $timestamps = false;
    public function Mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa');
    }
    public static function GestorListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            g.idGestor,
            g.idCeo,
            ct.nombreCeo,
            g.codigoGestor,
            g.nombre,
            g.telefono,
            g.nroDocumento,
            g.estado
            FROM gestor AS g
        INNER JOIN centrooperativo AS ct ON ct.idCeo = g.idCeo
        WHERE ct.idCeo = $idCeo"));
    }
    public static function GestorRegistrar(Request $request)
    {
        $data = new Gestor();
        $data->idCeo = $request->input('idCeo');
        $data->codigoGestor = $request->input('codigoGestor');
        $data->idMesa = $request->input('idMesa');
        $data->nombre = $request->input('nombre');
        $data->telefono = $request->input('telefono');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function GestorEditar(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->codigoGestor = $request->input('codigoGestor');
        $data->nombre = $request->input('nombre');
        $data->telefono = $request->input('telefono');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->save();
        return $data;
    }
    public static function GestorBloquear(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function GestorRestablecer(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function GestorImportarData(Request $request)
    {
        $archivoPlantilla = $request->file('gestorExcel');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($archivoPlantilla);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "ruta",
            "B" => "linea",
            "C" => "mesa",
            "D" => "gestor",
            "E" => "supervisor",
            "F" => "telefono",
            "G" => "nroDocumento",
            "H" => "codigoGestor",
            "I" => "sku",
            "J" => "marca",
        ];
        $dataImportada = [];
        for ($i = $startRow; $i <= $max; $i++) {
            $data_row = [];
            foreach ($columns as $col => $field) {
                $val = $workSheet->getCell("$col$i")->getValue();
                $data_row[$field] = $val;
                $data_row['idCeo'] = $request->input('idCeo');
            }
            $dataImportada[] = $data_row;
        }
        $dataImportada = collect($dataImportada);

        $dataGrupoGestor = $dataImportada->groupBy('codigoGestor');
        foreach ($dataGrupoGestor as $key => $dg) {
            $codigoGestor = trim($key);
            $objEntranteGestor = $dg->where('codigoGestor', $key)->first();
            $listaMesa = $dg->groupBy('mesa')->keys()->toArray();
            $nombreMesa = count($listaMesa) > 0 ? $listaMesa[0] : null;
            $objGestor = Gestor::where('codigoGestor', $codigoGestor)->where('idCeo', $request->input('idCeo'))->first();
            $objMesa = Mesa::where('nombre', $nombreMesa)->where('idCeo', $request->input('idCeo'))->first();
            if ($objMesa == null) {
                $data = new Mesa();
                $data->idCeo = $request->input('idCeo');
                $data->nombre = $nombreMesa;
                $data->estado = 1;
                $data->save();
                $objMesa = $data;
            }
            if ($objGestor != null) {
                $objGestor->idMesa = $objMesa->idMesa;
                $objGestor->nombre = $objEntranteGestor['gestor'];
                $objGestor->telefono = $objEntranteGestor['telefono'];
                $objGestor->nroDocumento = $objEntranteGestor['nroDocumento'];
                $objGestor->estado = 1;
                $objGestor->save();
            } else {
                $req = new Request([
                    'idCeo' => $request->input('idCeo'),
                    'codigoGestor' => $objEntranteGestor['codigoGestor'],
                    'idMesa' => $objMesa->idMesa,
                    'nombre' => $objEntranteGestor['gestor'],
                    'telefono' => $objEntranteGestor['telefono'],
                    'nroDocumento' => $objEntranteGestor['nroDocumento'],
                ]);
                $objGestor = Gestor::GestorRegistrar($req);
            }
            $listaSku = $dg->groupBy('sku')->keys()->toArray();
            foreach ($listaSku as $sku) {
                $objProducto = Producto::where('sku', $sku)->where('idCeo', $request->input('idCeo'))->first();
                if ($objProducto != null) {
                    $objSku = GestorProducto::where('idProducto', $objProducto->idProducto)->where('idGestor', $objGestor->idGestor)->first();
                    if ($objSku == null) {
                        $data = new GestorProducto();
                        $data->idGestor = $objGestor->idGestor;
                        $data->idProducto = $objProducto->idProducto;
                        $data->save();
                    }
                }
            }
            $listaRutas = $dg->groupBy('ruta')->keys()->toArray();
            foreach ($listaRutas as $ruta) {
                $objRuta = Ruta::where('descripcion', $ruta)->where('idCeo', $request->input('idCeo'))->first();
                if ($objRuta != null) {
                    $objGestoRuta = GestorRuta::where('idRuta', $objRuta->idRuta)->where('idGestor', $objGestor->idGestor)->first();
                    if ($objGestoRuta == null) {
                        $data = new GestorRuta();
                        $data->idGestor = $objGestor->idGestor;
                        $data->idRuta = $objRuta->idRuta;
                        $data->save();

                        $objMesaRuta = MesaRuta::where('idRuta', $objRuta->idRuta)->where('idMesa', $objMesa->idMesa)->first();
                        if ($objMesaRuta == null) {
                            $data = new MesaRuta();
                            $data->idMesa = $objMesa->idMesa;
                            $data->idRuta = $objRuta->idRuta;
                            $data->save();
                        }
                    }
                }
            }
            $listaSupervisor = $dg->groupBy('supervisor')->keys()->toArray();
            foreach ($listaSupervisor as $supervisor) {
                $objSupervisor = Supervisor::where('nombre', $supervisor)->where('idCeo', $request->input('idCeo'))->first();
                if ($objSupervisor == null) {
                    $req = new Request([
                        'idCeo' => $request->input('idCeo'),
                        'nombre' => $supervisor,
                    ]);
                    $data = Supervisor::SupervisorRegistrar($req);
                    $objSupervisor = $data;
                }
                $objGestorSupervisor = GestorSupervisor::where('idSupervisor', $objSupervisor->idSupervisor)->where('idGestor', $objGestor->idGestor)->first();
                if ($objGestorSupervisor == null) {
                    $data = new GestorSupervisor();
                    $data->idGestor =  $objGestor->idGestor;
                    $data->idSupervisor = $objSupervisor->idSupervisor;
                    $data->save();
                    $objMesaRuta = MesaSupervisor::where('idSupervisor', $objSupervisor->idSupervisor)->where('idMesa', $objMesa->idMesa)->first();
                    if ($objMesaRuta == null) {
                        $data = new MesaSupervisor();
                        $data->idMesa = $objMesa->idMesa;
                        $data->idSupervisor = $objSupervisor->idSupervisor;
                        $data->save();
                    }
                }
            }
        }
    }
    public static function GestorListarProcesado(Request $request)
    {
        $idCeo = $request->input('idCeo');
        $Lista = [];
        $ListaGestores = Gestor::with(['Mesa'])->where('idCeo', $idCeo)->where('estado', 1)->get();
        foreach ($ListaGestores as $gestor) {
            $Marcas = GestorProducto::GestorProductoMarcasConcatenadas($gestor->idGestor);
            $Lineas = GestorProducto::GestorProductoLineasConcatenadas($gestor->idGestor);
            $Rutas = GestorRuta::GestorRutaRutasConcatenadas($gestor->idGestor);
            $Supervisores = GestorSupervisor::GestorSupervisorSupervisorConcatenado($gestor->idGestor);
            $Lista[] = [
                'ruta' => $Rutas,
                'linea' => $Lineas,
                'mesa' => $gestor->mesa->nombre,
                'gestor' => $gestor->nombre,
                'telefono' => $gestor->telefono,
                'dni' => $gestor->nroDocumento,
                'codigo' => trim($gestor->codigoGestor),
                'marcas' => $Marcas,
                'supervisor' => $Supervisores
            ];
        }
        return $Lista;
    }
    public static function GestorListarData(Request $request)
    {
        $idCeo = $request->input('idCeo');
        $Lista = [];
        $ListaGestores = Gestor::with(['Mesa'])->where('idCeo', $idCeo)->where('estado', 1)->get();
        foreach ($ListaGestores as $gestor) {
            $gestorRutas = GestorRuta::with(['Ruta'])->where('idGestor', $gestor->idGestor)->get();
            $Mesa = $gestor->mesa->nombre;
            $Productos = GestorProducto::GestorProductoListar(new Request(['idGestor' => $gestor->idGestor])); //with(['Producto'])->where('idGestor', $gestor->idGestor)->get();
            $Supervisores = GestorSupervisor::with(['Supervisor'])->where('idGestor', $gestor->idGestor)->get();
            foreach ($gestorRutas as $objGestorRuta) {
                foreach ($Supervisores as $objSupervisor) {
                    foreach ($Productos as $objProducto) {
                        $Lista[] = [
                            'ruta' => $objGestorRuta->ruta->descripcion,
                            'linea' => $objProducto->nombreLinea,
                            'mesa' => $Mesa,
                            'gestor' => trim($gestor->nombre),
                            'telefono' => $gestor->telefono,
                            'dni' => $gestor->nroDocumento,
                            'codigo' => $gestor->codigoGestor,
                            'marcas' => $objProducto->marca,
                            'supervisor' => $objSupervisor->supervisor->nombre,
                        ];
                    }
                }
            }
        }
        return $Lista;
    }
}
