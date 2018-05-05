<?php

namespace App\Http\Controllers;

use App\History;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use Illuminate\Http\Request;

class HistoryController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $history = History::paginate(16);
        if (count($history) < 1) {
            return $this->sendMessage('Found 0 history');
        }
        return $this->sendData($history);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $history = History::find($id);
        if (is_null($history)) {
            return $this->sendErrorNotFound('History not found !');
        }
        $history->delete();

        return $this->sendMessage('Deleted '.$id.' history successfully !');
    }
}
