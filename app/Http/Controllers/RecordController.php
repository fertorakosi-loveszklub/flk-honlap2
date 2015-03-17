<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Record;
use App\RecordCategory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class RecordController extends BaseController
{
    /**
     * Initializes a new instance of the AlbumController class.
     */
    public function __construct() 
    {
        $this->middleware('auth', ['only' => ['getUj', 'postUj', 'getSajat',
            'getTorles', 'getLathatosag', 'getGrafikon']]);
    }

    /**
     * GET method, index route (/)
     * Displays the overview of the top records.
     * @return mixed View
     */
    public function getIndex()
    {
        // Get categories
        $categories = RecordCategory::all();

        return view('layouts.records.list', array('categories' => $categories));
    }

    /**
     * GET method, uj route(/uj
     * Displays the form to upload a new record.
     * @return mixed View
     */
    public function getUj()
    {
        // Get categories
        $categories = RecordCategory::all();

        return view('layouts.records.new', array('categories' => $categories));
    }

    /**
     * POST method, uj route (/uj)
     * Saves a new record.
     * @return mixed View
     */
    public function postUj(Request $req)
    {
        // Validate input
        $validator = Validator::make(
            $req->all(),
            array(
                'imgurl'        => 'regex:/^https?:\/\/i\.imgur\.com\/[a-zA-Z0-9]+\.jpe?g$/',
                'category'      => 'exists:record_categories,id',
                'shots'         => 'integer|between:1,30',
                'points'        => 'integer|between:1,300',
                'shot_at'       => 'date',
                'visibility'    => 'in:private,public'
            ));

        // No need to send / display validation error messages.
        // There is JS validation and as JS is required for uploading 
        // anyways, validation errors should not occur on server side
        // for a nice user.
        if ($validator->fails()) {
            return redirect('/rekordok/uj')->with('message',
                array( 'message' => 'Hibás adatok.', 'type' => 'danger'));
        }

        $is_public = $req->input('visibility') == 'public' ? true : false;

        // Save record
        $record = new Record;
        $record->user_id        = Auth::user()->id;
        $record->category_id    = $req->input('category');
        $record->shots          = $req->input('shots');
        $record->points         = $req->input('points');
        $record->shot_at        = $req->input('shot_at');
        $record->image_url      = $req->input('imgurl');
        $record->shots_average  = round($record->points / $record->shots * 10, 2);
        $record->is_public      = $is_public;
        $record->save();

        return redirect('/rekordok/sajat')->with('message',
            array( 'message' => 'Rekord feltöltve.', 'type' => 'success'));
    }

    /**
     * GET method, sajat route (/sajat)
     * Displays the list of all own records.
     * @return mixed View
     */
    public function getSajat() 
    {
        // Get categories
        $categories = RecordCategory::with(array('records' => function($query)
        {
            $query->where('user_id', '=', Auth::user()->id);

        }))->get();

        return view('layouts.records.own', array('categories' => $categories));
    }

    /**
     * GET method, rekordok route (/rekordok/$id)
     * Loads top records with given category id.
     * @param $id       Id of the category to get the records of.
     * @return mixed    JSON
     */
    public function getRekordok($id) 
    {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'data'      => null
        );

        // Check if category id is valid
        if(RecordCategory::find($id) == null) {
            $response['message'] = "Érvénytelen kategória";
            return response()->json($response);
        }

        $data = DB::table('records')
            ->join('users', 'records.user_id', '=', 'users.id')
            ->select(DB::raw('users.real_name, records.shot_at, records.shots, records.points,
                            max(records.shots_average) as record, records.category_id, records.image_url'))
            ->where('records.category_id', '=', $id)
            ->where('records.is_public', '=', true)
            ->groupBy('records.user_id')
            ->orderBy('record', 'desc')
            ->get();

        $response['success'] = true;
        $response['data'] = (array)$data;

        return response()->json($response);
    }

    /**
     * GET method, torles route (/torles/$id)
     * Deletes a record with the given id.
     * @param $id       Id of the record to delete.
     * @return mixed    View
     */
    public function getTorles($id) 
    {
        // Check if record exists and belongs to the user currently logged in
        $record = Record::find($id);

        if ($record == null || $record->user_id != Auth::user()->id) {
            return redirect('/rekordok');
        }

        // Delete record
        $record->delete();

        return redirect('/rekordok/sajat')->with('message',
            array('message' => 'Rekord törölve.', 'type' => 'success'));
    }

    /**
     * GET method, lathatosag route (/lathatosag/$id)
     * Toggles the visibility (is_public property) of a given record.
     * @param $id       Id of the record to toggle.
     * @return mixed    JSON
     */
    public function getLathatosag($id) 
    {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'isPublic'  => null
        );

        // Check if record exists and belongs to the user currently logged in
        $record = Record::find($id);

        if ($record == null || $record->user_id != Auth::user()->id) {
            $response['message'] = 'Érvénytelen ID vagy nem saját rekord.';
            return response()->json($response);
        }

        $record->is_public = !$record->is_public;
        $record->save();

        $response['success'] = true;
        $response['isPublic'] = $record->is_public;
        return response()->json($response);
    }

    /**
     * GET method, grafikon route (/grafikon/$id)
     * Returns data for displaying a line chart of the progress of the user (records with given category id)
     * @return JSON
     */
    public function getGrafikon($id) 
    {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'data'  => null
        );

        // Get entries for each category
        $entries = DB::table('records')
            ->select(DB::raw('shot_at as date, max(shots_average) as record'))
            ->where('user_id', '=', Auth::user()->id)
            ->where('category_id', '=', $id)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        if (count($entries) == 0){
            $response['message'] = 'Nincsenek eredmények.';
            return response()->json($response);
        }

        /**
         * $entries has one record per per day.
         * We have to make an array which is:
         *  -  An array of arrays, which are
         *      - Data titles
         *      - Data itself
         *
         * Example:
         * [
         *  ['Dátum',       'Eredmény'],
         *  ['2014-01-01',  '80'],
         *  ['2014-01-08',  null],
         *  ['2014-02-01',  '10']
         * ]
         */

        // Create return value
        $data = [];

        // Add first row with headers
        $data[]  = ['Dátum', 'Eredmény (10-es átlag)'];

        foreach ($entries as $entry) {
            $data[] = [$entry->date, $entry->record];
        }

        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response);
    }
}