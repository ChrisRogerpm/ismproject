<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "pedido";
    protected $primaryKey = "idPedido";
    protected $fillable = [
        'idCeo',
        'idCliente',
        'nroPedido',
        'visita',
        'latitud',
        'longitud',
        'giro',
        'email',
        'canal',
        'lprecio',
        'idGestor',
    ];
    public $timestamps = false;
    public static function PedidoListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            p.idPedido,
            p.nroPedido,
            c.codigoCliente,
            c.nombreRazonSocial,
            c.direccion,
            c.referencia,
            IF(IF(CHAR_LENGTH(c.nroDocumento) <= 8,0,1) = 1,'RUC','DNI') AS tipoDocumento,
            c.nroDocumento,
            c.ruta,
            c.modulo,
            p.visita,
            p.latitud,
            p.longitud,
            p.giro,
            p.email,
            c.telefono,
            co.lugar,
            co.codigoCeo,
            p.canal,
            p.lprecio,
            CONCAT(g.codigoGestor,' - ',g.nombre) AS nombreGestor
        FROM pedido AS p
        INNER JOIN cliente AS c ON c.idCliente = p.idCliente
        INNER JOIN centrooperativo AS co ON co.idCeo = p.idCeo
        INNER JOIN gestor as g ON g.idGestor = p.idGestor
        WHERE p.idCeo = $idCeo"));
    }
    public static function PedidoRegistrar(Request $request)
    {
        $data = new Pedido();
        $data->idCeo = $request->input('idCeo');
        $data->idCliente = $request->input('idCliente');
        $data->nroPedido = $request->input('nroPedido');
        $data->visita = $request->input('visita');
        $data->latitud = $request->input('latitud');
        $data->longitud = $request->input('longitud');
        $data->giro = $request->input('giro');
        $data->email = $request->input('email');
        $data->canal = $request->input('canal');
        $data->lprecio = $request->input('lprecio');
        $data->idGestor = $request->input('idGestor');
        $data->save();
        return $data;
    }
    public static function PedidoImportarData(Request $request)
    {

        // $DataTB_UNI = Producto::ProductoListarActivos($request);
        // $DataGGVVRUTA = Excel::ImportarDataGGVVRUTA($request);
        // $DataCLIENTEPEDIDO = Excel::ImportarDataCLIENTEPEDIDO($request);
        // $DataBONIFICACIONES = Excel::ImportarDataBONIFICACIONES($request);
        // $DataPEDIDO = Excel::ImportarDataPEDIDO($request);
        // $DataDATA_CLI = Excel::ImportarDataDATA_CLI($request);
    }
}
