<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsJqueryController extends Controller
{
    /**
     * Studi Kasus 2: CRUD dengan table HTML biasa
     * Tanpa database, pure JavaScript DOM manipulation
     */
    public function crudTable()
    {
        return view('js-jquery.crud-table');
    }
    
    /**
     * Studi Kasus 2 + 3: CRUD dengan DataTables + Modal Edit/Delete
     * Tanpa database, menggunakan jQuery DataTables
     */
    public function crudDatatables()
    {
        return view('js-jquery.crud-datatables');
    }
    
    /**
     * Studi Kasus 4: Select & Select2
     * Menampilkan 2 card dengan select biasa dan select2
     */
    public function select()
    {
        return view('js-jquery.select');
    }
}