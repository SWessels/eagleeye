<?php

namespace App\Http\Controllers;

use App\OrderNotes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

class OrderNotesController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        // check if user has employees capability
        if(User::userCan('orders') === false)
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function deleteOrderNote(Request $request)
    {
        //
        $input = $request->input();
          if(OrderNotes::where('id', '=', $input['note_id'])->delete())
          {
              echo json_encode(array('action' => true, 'id' => $input['note_id']));
              exit;
          }
  
          echo json_encode(array('action' => false, 'msg' => 'Error deleting note!'));
          exit;
    }
}
