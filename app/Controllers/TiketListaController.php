<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketsModel;
class TiketListaController extends BaseController
{
    public function index()
    {
        $modelo = new TicketsModel();
        $data['tickets'] = $modelo->obtenerTickets();

        return view('ticketslista/lista', $data); 
    }

    public function verTicket($id)
{
    $modelo = new TicketsModel();
    $ticket = $modelo->obtenerTicket($id);

    if (!$ticket) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Ticket no encontrado.");
    }

    return view('ticketslista/detalle', ['ticket' => $ticket]);
}

}
