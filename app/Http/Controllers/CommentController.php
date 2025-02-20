<?php

namespace App\Http\Controllers;

use App\Classes\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = CommentService::getComments();
        return response()->json(['data' => $comments]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $comments = CommentService::createComment($request->all());
            Log::debug(__METHOD__ . ' - NEW Comment CREATED ' . json_encode($comments));
            return response()->json(['data' => $comments]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Comment"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

    /**
     * Display Comments reference in  course by ID
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function commentByCourse($id)
    {
        $comments = CommentService::getCommentByCourse($id);
        return response()->json(['data' => $comments]);
    }
    /**
     * Display Comments reference in Assignment by ID
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function commentByAssignment($id, $user_id = NULL)
    {
        $user = Auth::user();
        // $comments = [];
        if ($user->hasRole('teacher')) {
            $comments = CommentService::getCommentByAssignment($id, $user_id);
        } else if ($user->hasRole('student')) {
            $comments = CommentService::getCommentByAssignmentUser($id, $user->id);
        } else {
            $comments = [];
        }

        return response()->json(['data' => $comments]);
    }
}
