<?php

namespace App\Classes;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{

    public static function getComments()
    {
        $commentParse = [];
        $comments = Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->whereHas('user', function ($query) {
                return $query->where('institution_id', '=', Auth::user()->institution_id);
            })
            // ->whereHas('course.subject', function ($query) {
            //     return $query->where('id', '=', Auth::user()->institution_id);
            // })
            ->whereNull('children_id')
            ->get();

        foreach ($comments as $comment) {
            if ($comment->model_name === 'assignments') {
                $commentParse['assignments'][] = [
                    'id' => $comment->id,
                    'children_id' => $comment->children_id,
                    'comment' => $comment->comment,
                    'username' => $comment->user->name,
                    'image' => $comment->user->image,
                    'assignment' => $comment->assignment,
                    'child' => $comment->commentChild
                ];
            } else {
                $commentParse['courses'][] = [
                    'id' => $comment->id,
                    'children_id' => $comment->children_id,
                    'comment' => $comment->comment,
                    'username' => $comment->user->name,
                    'image' => $comment->user->image,
                    'child' => $comment->commentChild
                ];
            }
        }
        return $commentParse;
    }

    public static function getCommentByCourse($id)
    {
        $commentParse = [];
        $comments = Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->whereHas('user', function ($query) {
                return $query->where('institution_id', '=', Auth::user()->institution_id);
            })
            ->whereHas('course', function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('created_at', 'DESC')
            ->whereNull('children_id')
            ->get();

        foreach ($comments as $comment) {
            $commentParse[] = [
                'id' => $comment->id,
                'children_id' => $comment->children_id,
                'model_id' => $comment->children_id,
                'model_name' => $comment->children_id,
                'created_at' => $comment->created_at,
                'comment' => $comment->comment,
                'assignment' => $comment->assignment,
                'user' => $comment->user,
                'child' => $comment->commentChild
            ];
        }
        return $commentParse;
    }
    public static function getCommentByAssignmentUser($id, $user_id)
    {
        $commentParse = [];
        $comments = Comment::where('to_user_id', $user_id)->with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->whereHas('user', function ($query) {
                return $query->where('institution_id', '=', Auth::user()->institution_id);
            })
            ->whereHas('assignment', function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->whereNull('children_id')
            ->get();

        foreach ($comments as $comment) {
            $commentParse[] = [
                'id' => $comment->id,
                'children_id' => $comment->children_id,
                'model_id' => $comment->children_id,
                'model_name' => $comment->children_id,
                'created_at' => $comment->created_at,
                'comment' => $comment->comment,
                'assignment' => $comment->assignment,
                'user' => $comment->user,
                'child' => $comment->commentChild
            ];
        }
        return $commentParse;
    }
    public static function getCommentByAssignment($id, $user_id)
    {
        $comments =  Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            // add WhereHas Institution user
            ->whereHas('user', function ($query) {
                $query->where('institution_id', '=', Auth::user()->institution_id);
            })
            // add whereHas Assignments
            ->whereHas('assignment', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })
            ->whereHas('comment', function ($query) {
                $query->where('user_id', '=', Auth::user()->id);
            })
            // //add whereHas To Users
            ->whereHas('comment', function ($query) use ($user_id) {
                $query->where('to_user_id', '=', $user_id);
                $query->where('model_name', '=', 'assignments');
            })

            ->whereNull('children_id')
            ->get();
        $commentParse = [];
        foreach ($comments as $comment) {
            $commentParse[] = [
                'id' => $comment->id,
                'children_id' => $comment->children_id,
                'model_id' => $comment->children_id,
                'model_name' => $comment->children_id,
                'created_at' => $comment->created_at,
                'comment' => $comment->comment,
                'assignment' => $comment->assignment,
                'user' => $comment->user,
                'child' => $comment->commentChild
            ];
        }
        return $commentParse;
    }

    public static function getComment($id)
    {
        $commentParse = [];
        $comments = Comment::where('id', $id)->with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->whereHas('user', function ($query) {
                return $query->where('institution_id', '=', Auth::user()->institution_id);
            })

            //->whereNull('children_id')
            ->get();

        foreach ($comments as $comment) {

            $commentParse = [
                'id' => $comment->id,
                'children_id' => $comment->children_id,
                'model_id' => $comment->children_id,
                'model_name' => $comment->children_id,
                'created_at' => $comment->created_at,
                'comment' => $comment->comment,
                'assignment' => $comment->assignment,
                'user' => $comment->user,
                'child' => $comment->commentChild
            ];
        }
        return $commentParse;
    }

    public static function createComment($data)
    {
        $newComment = new Comment();
        $newComment->user_id    = Auth::user()->id;
        $newComment->comment    = $data['comment'];
        $newComment->model_id   = $data['model_id'];
        $newComment->children_id   = $data['children_id'] ?? NULL;
        $newComment->model_name = $data['model_name'];
        $newComment->to_user_id = $data['to_user_id'] ?? Auth::user()->id;
        $newComment->save();
        return self::getComment($newComment->id);
    }

    public static function updateComment($id, $data)
    {
        // Comment::where('id', $id)->update($data);
        // return Comment::where('id', $id)->with(['province.country'])->first();
        // $province = Comment::where('id', $id)->with(['province.country'])->first();
        // $province->name        = $data['name'];
        // $province->zip_code    = $data['zip_code'];
        // $province->province_id = $data['province_id'];
        // $province->save();
        // return $province;
    }

    public static function deleteComment($id)
    {
        return Comment::where('id', $id)->delete();
    }
}
