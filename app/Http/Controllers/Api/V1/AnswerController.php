<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerStoreRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Service\CreateAnswer;


class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $from = Carbon::createFromFormat('Y-m-d', $request->from ?? now()->firstOfMonth()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', $request->to ??now()->format('Y-m-d') );

      return response()->json(Answer::with(['subject','user','exam','answer_details'])
          ->whereBetween('created_at', [$from, $to])
            ->get()
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnswerStoreRequest $request)
    {
        $answer = new CreateAnswer($request->validated());
        $result = $answer->execute();
        return response()->json($result['message'], $result['success'] ? 201 : 400 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): AnswerResource
    {
        return new AnswerResource(Answer::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
