<?php

namespace App\Classes;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{

    public static function getComments()
    {
        return Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->whereNull('children_id')
            ->get();
    }

    public static function getCommentByCourse($id)
    {
        return Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            // add whereHas Course
            ->whereHas('course', function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->whereNull('children_id')
            ->get();
    }
    public static function getCommentByAssignmentUser($id, $user_id)
    {
        $comments =  Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            // add whereHas Assignments
            ->whereHas('assignment', function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->whereHas('comment', function ($query) use ($user_id) {
                return $query->where('user_id', '=', $user_id);
            })
            // add whereHas Users
            // ->whereHas('commentChild.user', function ($query) use ($user_id) {
            //     return $query->where('id', '=', $user_id);
            // })
            ->whereNull('children_id')
            ->get();
        $commentChild = [];
        foreach ($comments as $comment) {
            $commentChild = [
                'comments' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'username' => $comment->user->name,
                    'image' => $comment->user->image,
                    'child' => $comment->commentChild
                ]
            ];
        }
        return $commentChild;
    }
    public static function getCommentByAssignment($id, $user_id)
    {
        $comments =  Comment::with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            // add whereHas Assignments
            ->whereHas('assignment', function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->whereHas('comment', function ($query) {
                return $query->where('user_id', '=', Auth::user()->id);
            })
            //add whereHas Users
            ->whereHas('commentChild.user', function ($query) use ($user_id) {
                return $query->where('id', '=', $user_id);
            })

            ->whereNull('children_id')
            ->get();
        $commentChild = [];
        foreach ($comments as $comment) {
            $commentChild = [
                'comments' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'username' => $comment->user->name,
                    'image' => $comment->user->image,
                    'child' => $comment->commentChild
                ]
            ];
        }
        return $commentChild;
    }

    public static function getComment($id)
    {
        return Comment::where('id', $id)->with(['user', 'course.subject', 'assignment.assignmenttype', 'commentChild.user'])
            ->first();
    }

    public static function createComment($data)
    {
        $newComment = new Comment();
        $newComment->user_id    = Auth::user()->id;
        $newComment->comment    = $data['comment'];
        $newComment->model_id   = $data['model_id'];
        $newComment->children_id   = $data['children_id'] ?? NULL;
        $newComment->model_name = $data['model_name'];
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
