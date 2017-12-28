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
                ['parent_id' => intval($parent_id), 'comment' => $comment]
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
    }

    private function getTreeArray($comments, $parent_id) {
        $array = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {

                $comment['childs'] = $this->getTreeArray($comments, $comment['id']);
                $array[] = $comment;
            }
        }
        return $array ? $array : '';
    }

}
