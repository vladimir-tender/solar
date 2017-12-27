<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use DB;

class CommentController extends Controller
{
    public function index()
    {
        return view('main');
    }

    public function commentsTreeAjax()
    {
        $comments = Comment::all();
        $tree = $this->getTreeArray($comments, 0);

        return response()->json($tree);
    }

    public function getComment($id)
    {
        $comment = Comment::find($id);

        return response()->json($comment);
    }

    public function addComment(Request $request)
    {
        $parent_id = $request->get('parent_id');
        $comment = $request->get('comment');
        $comment = filter_var($comment, FILTER_SANITIZE_STRING);

        try {
            DB::table('comments')->insert(
                ['parent_id' => $parent_id, 'comment' => $comment]
            );
            return response()->json('true');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function editComment(Request $request)
    {
        $id = $request->get('id');
        $comment = $request->get('comment');
        $comment = filter_var($comment, FILTER_SANITIZE_STRING);

        try {
            DB::table('comments')
                ->where('id', intval($id))
                ->update(['comment' => $comment]);

            return response()->json('true');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function removeComment($id)
    {
        // TODO: remove childrens
        try {
            DB::table('comments')->where('id', '=', intval($id))->delete();
            return response()->json('true');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
        //return response()->json($comment);
    }

    public function getTreeArray($comments, $parent_id) {
        $array = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {

                $comment['childs'] = $this->getTreeArray($comments, $comment['id']);
                $array[] = $comment;
            }
        }
        return $array ? $array : '';
    }

    /*public function create()
    {
        return view("comment.create");
    }


    public function arraySet()
    {
        $this->comments = [
            ['id' => '1', 'parent_id' => null, 'comment' => 'Comment 1'],
            ['id' => '2', 'parent_id' => '1', 'comment' => 'Comment 2'],
            ['id' => '3', 'parent_id' => null, 'comment' => 'Comment 3'],
            ['id' => '4', 'parent_id' => '1', 'comment' => 'Comment 4'],
            ['id' => '5', 'parent_id' => '2', 'comment' => 'Comment 5'],
            ['id' => '6', 'parent_id' => '2', 'comment' => 'Comment 6'],
            ['id' => '7', 'parent_id' => '1', 'comment' => 'Comment 7']
        ];
    }*/


}
