<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $file = Storage::disk('weatherdata')->get('130670.csv');

        //Split the .csv by newline.
        $seperated = explode("\r\n", $file);

        //Get the first value, split by comma and create a new multidimensional array with it
        $labels = explode(',', array_shift($seperated)); // not used for now

        for($y = 0; $y < count($labels); $y++) {
            $fullData[strtolower($labels[$y])] = [];
        }
        $keys = array_keys($fullData);

        for($i = 0; $i < count($seperated); $i++) {
            if(empty(trim($seperated[$i]))) continue;

            //0 -> date, 1-> time, 2 -> temperature, 3 -> dewpoint
            $data = explode(',', $seperated[$i]);
            for($x = 0; $x < count($data); $x++) {

                $fullData[$keys[$x]][] = $data[$x];
            }

        }

        return view('home', compact('fullData', 'timeexpired'));
    }
}
