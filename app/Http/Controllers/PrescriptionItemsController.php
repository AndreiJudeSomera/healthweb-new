<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionItem;

class PrescriptionItemsController extends Controller {
  public function index() {
    $medicines = PrescriptionItem::all();

    return response()->json($medicines);
  }
}
