<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\AlbumComment;
use App\Models\TrackComment;

class CommentController extends Controller
{
    public function albumComment(Request $request,$group,$albumID,$commentIndex)
    {
        $request->validate([
            'comment' => 'required|filled'
        ]);

        $existingComment = AlbumComment::where('comment', $request->input('comment'))->first();

        if ($existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])
                ->with('commentError', 'Same comment')
                ->with('comment_id', $existingComment->id);
        }

        DB::table('album_comments')->insert([
            'user_id'=> Auth::id(),
            'username'=> Auth::user()->name,
            'album_id'=> $albumID,
            'comment' => nl2br($request->input('comment')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentSuccess', 'Successfully comment');
    }
    public function trackComment(Request $request,$group,$albumID,$commentIndex,$track_id)
    {
        $request->validate([
            'comment' => 'required|filled'
        ]);

        $existingComment = TrackComment::where('comment', $request->input('comment'))->first();

        if ($existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])
                ->with('commentError', 'Same comment')
                ->with('comment_id', $existingComment->id);
        }

        DB::table('track_comments')->insert([
            'user_id'=> Auth::id(),
            'username'=> Auth::user()->name,
            'track_id'=> $track_id,
            'comment' => nl2br($request->input('comment')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentSuccess', 'Successfully comment');
    }
    public function editAlbumComment(Request $request,$group,$albumID,$commentIndex,$comment_id)
    {
        $request->validate([
            'newComment' => 'required|filled'
        ]);

        $existingComment = AlbumComment::where('comment', $request->input('newComment'))->first();

        if ($existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])
                ->with('commentError', 'Same comment')
                ->with('comment_id', $comment_id);
        }

        //check if exists
        $existingComment = DB::table('album_comments')
        ->where('id', $comment_id)
        ->first();

        if (!$existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', 'Comment does not exist')->with('comment_id', $comment_id);
        }


        //check if it's from the logged in user
        if (Auth::id()!=$existingComment->user_id){
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', "You can't edit comment from another user")->with('comment_id', $comment_id);
        }
    
        DB::table('album_comments')->where('id', $comment_id)->update(['comment' => nl2br($request->input('newComment')), 'updated_at' => now()]);
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('singleCommentSuccess', 'Successfully edit comment')->with('comment_id', $comment_id);
    }
    public function editTrackComment(Request $request,$group,$albumID,$commentIndex,$comment_id)
    {
        $request->validate([
            'newComment' => 'required|filled'
        ]);

        $existingComment = TrackComment::where('comment', $request->input('newComment'))->first();

        if ($existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])
                ->with('commentError', 'Same comment')
                ->with('comment_id', $comment_id);
        }
        
        //check if exists
        $existingComment = DB::table('track_comments')
        ->where('id', $comment_id)
        ->first();

        if (!$existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', 'Comment does not exist')->with('comment_id', $comment_id);
        }


        //check if it's from the logged in user
        if (Auth::id()!=$existingComment->user_id){
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', "You can't edit comment from another user")->with('comment_id', $comment_id);
        }
    
        DB::table('track_comments')->where('id', $comment_id)->update(['comment' => nl2br($request->input('newComment')), 'updated_at' => now()]);
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('singleCommentSuccess', 'Successfully edit comment')->with('comment_id', $comment_id);
    }
    public function removeAlbumComment(Request $request,$group,$albumID,$commentIndex,$comment_id)
    {
        //check if exists
        $existingComment = DB::table('album_comments')
        ->where('id', $comment_id)
        ->first();

        if (!$existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', 'Comment does not exist')->with('comment_id', $comment_id);
        }

        //check if it's from the logged in user
        if (Auth::id()!=$existingComment->user_id){
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', "You can't delete comment from another user")->with('comment_id', $comment_id);
        }
    
        DB::table('album_comments')->where('id', $comment_id)->delete();
        
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentSuccess', 'Successfully delete comment');
    }
    public function removeTrackComment(Request $request,$group,$albumID,$commentIndex,$comment_id)
    {
        //check if exists
        $existingComment = DB::table('track_comments')
        ->where('id', $comment_id)
        ->first();

        if (!$existingComment) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', 'Comment does not exist')->with('comment_id', $comment_id);
        }

        //check if it's from the logged in user
        if (Auth::id()!=$existingComment->user_id){
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentError', "You can't delete comment from another user")->with('comment_id', $comment_id);
        }
    
        DB::table('track_comments')->where('id', $comment_id)->delete();
        
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $albumID, 'commentIndex' => $commentIndex])->with('commentSuccess', 'Successfully delete comment');
    }
}
