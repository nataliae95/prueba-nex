<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\View\View;

class ClientController extends Controller
{

    /**
     * Muestra el listado general de clientes.
     *
     * @return View
     */
    public function index()
    {
        return view('clients.index');
    }

    /**
     * Muestra el detalle de un cliente específico.
     *
     * @param Client $client cliente.
     * @return View
     * * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(Client $client)
    {
        return view('clients.show', ['id' => $client->id]);
    }
}
