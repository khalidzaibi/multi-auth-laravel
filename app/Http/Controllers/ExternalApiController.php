<?php

namespace App\Http\Controllers;

use App\Models\ExternalApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Cache;
use Exception;
use Carbon\Carbon;
use Dotenv\Parser\Value;

class ExternalApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExternalApi  $externalApi
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try{
            DB::beginTransaction();
            $response = Http::get('https://api.publicapis.org/entries');
            $jsonData = $response->json();
            
            if(sizeof($jsonData['entries']) && $response->ok()){
               
                // $expiresAt = Carbon::now()->addMinute();
                $expiresAt = Carbon::now()->addDays(365);
               Cache::put('newFetchedData', $jsonData['entries'], $expiresAt);

                // $testArray = [
                //    [ "API" => "AdoptAPet",
                //     "Description" => "Resource to help get pets adopted",
                //     "Auth" => "apiKeyK",
                //     "HTTPS" => true,
                //     "Cors" => "yesS",
                //     "Link" => "https://www.adoptapet.com/public/apis/pet_list.html",
                //     "Category" => "Animals"],
                //     [ "API" => "Axolotl",
                //     "Description" => "Collection of axolotl pictures and facts",
                //     "Auth" => "KKKK",
                //     "HTTPS" => true,
                //     "Cors" => "noN",
                //     "Link" => "https://theaxolotlapi.netlify.app/",
                //     "Category" => "Animals"],
                // ];
                // Cache::put('oldFetchedData',$testArray, $expiresAt);
                
                if(Cache::has('newFetchedData')){
                    $oldData = Cache::has('oldFetchedData') ? Cache::get('oldFetchedData') : array();
                    // dd(Cache::get('oldFetchedData'));
                    
                    $newFetchedData = Cache::get('newFetchedData');
                    $newDifferData = array_udiff($newFetchedData, $oldData, fn($a, $b) => $a <=> $b);
                   
                    //MERGE differ array to previous/OldFetchedData
                    $mergeOldAndDiffData = array_merge($newDifferData,$oldData);
                    
                    //PREVIOUS Fetched Data// save in cache
                    Cache::put('oldFetchedData',$mergeOldAndDiffData, $expiresAt);

                    //saveData into Database
                    $saveAbleData = array();
                    foreach($newDifferData as $key=>$value){
                        $saveAbleData = array(
                            'api'         => $value['API'],
                            'description' => $value['Description'],
                            'auth'        => $value['Auth'],
                            'https'       => $value['HTTPS'],
                            'cors'        => $value['Cors'],
                            'link'        => $value['Link'],
                            'category'    => $value['Category']
                        );
                        
                        //SAVE DATA //or INGNORE if already in database
                        if(sizeof($saveAbleData)){
                            $isExist = ExternalApi::where($saveAbleData)->count();
                            if(!$isExist){
                                ExternalApi::create($saveAbleData);
                            }
                        }
                    }
                    // dd($saveAbleData);
                    // ExternalApi::insertOrIgnore($saveAbleData);
                    
                }
            }
            DB::commit();
            return back()->withStatus('New data saved successfully!');
        }catch(Exception $err){
            DB::rollback();
            abort(500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExternalApi  $externalApi
     * @return \Illuminate\Http\Response
     */
    public function edit(ExternalApi $externalApi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *  
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExternalApi  $externalApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExternalApi $externalApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExternalApi  $externalApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExternalApi $externalApi)
    {
        //
    }
}