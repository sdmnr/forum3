<?php
namespace App\Http\Controllers;

use App\Like;
use App\Rlike;
use App\Post;
use App\User;
use App\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// use Illuminate\Pagination\LengthAwarePaginator;


class PostController extends Controller
{
    public function getDashboard()//show dashboard view
    { 
        $user = Auth::user();
        $userid=Auth::user()->id;//get user id of logged in user
        $responses = Response::where('user_id', $userid)->pluck('rscore')->all();
         $Uscore = 0;
       for($i=0;$i<count($responses);$i++)//give score to user based on score of his responses
            {
                $Uscore = $responses[$i] + $Uscore;
            }
        //dd($Uscore);
        // if($Uscore > 0)
        // {
          $user->uscore = $Uscore;
          $user->update();  
        //}       
        
        $posts = Post::where('user_id',$userid)->orderBy('created_at', 'desc')->/*get()*/paginate(3);//get qts of this user
        return view('dashboard', ['posts' => $posts]);
    }

    public function getallQuestions(Request $input)//show all qts view
     {   
       $keyword = $input->get("searchBar");//search key word
       $filter = $input->get("topAllQuestions");//filter for top rated or all qts
       $posts = Post::all();
       $num_posts = count($posts);//count of all qts
       $searched_posts = 0;
       $posts = Post::where('visible',true)->orderBy('created_at', 'desc')/*->get()*/->paginate(5);//get all posts
        if(!empty($keyword))
        {
            $posts = Post::where('visible',true)->where('title','like',"%".$keyword."%")->orWhere('tags', 'like', "%".$keyword."%")->paginate(5);//get search results
            $searched_posts = count($posts); 
        }
        elseif ($filter=="top") {
            $posts = Post::orderBy('score', 'desc')/*
                ->get()*/->paginate(5);//get top rated qts
        }
        elseif ($filter=="all") {
            $posts = Post::where('visible',true)->orderBy('created_at', 'desc')/*->get()*/->paginate(5);//get all qts
        }
        return view('allQuestions', ['posts' => $posts])->with(['num_posts' => $num_posts,'searched_posts' => $searched_posts]);
    }

    public function getmyQuestions(Request $input)//show my qts view
    { 
        $userid=Auth::user()->id;//get user id of logged in user
        $keyword = $input->get("searchBar");//search key word
       $filter = $input->get("topMyQuestions");//filter for top rated or all qts
        $posts = Post::where('user_id',$userid)->get();//get this users qts
        $num_posts = count($posts);//count of this users qts
       $searched_posts = 0;
        $posts = Post::where('user_id',$userid)->orderBy('created_at', 'desc')->paginate(5);
        if(!empty($keyword))
        {
            $posts = Post::where('user_id',$userid)->where('title','like',"%".$keyword."%")->orWhere('tags', 'like', "%".$keyword."%")->paginate(5);//get search results 
            $searched_posts = count($posts); 
        }
        elseif ($filter=="top") {
            $posts = Post::where('user_id',$userid)->orderBy('score', 'desc')
                ->paginate(5);//get top rated qts
        }
        elseif ($filter=="all") {
            $posts = Post::where('user_id',$userid)->orderBy('created_at', 'desc')/*->get()*/->paginate(5);//get all qts
        }
        return view('myQuestions', ['posts' => $posts])->with(['num_posts' => $num_posts,'searched_posts' => $searched_posts]);
    }

    public function getmyResponses(Request $input)//show my responses view
    { //this page is under development
        //dd('under development');
        $userid=Auth::user()->id;
       //  $keyword = $input->get("searchBar");
       $filter = $input->get("topMyResponses");
       $responses = Response::where('user_id', $userid)->orderBy('created_at', 'desc')->pluck('post_id')->all();
       $responses = array_unique($responses);
       $responses = array_values($responses);
       $topresponses = Response::where('user_id', $userid)->orderBy('rscore', 'desc')
                    ->pluck('post_id')->all();
        $topresponses = array_unique($topresponses);
        $topresponses = array_values($topresponses);
        $posts = array( );
        for($i=0;$i<count($responses);$i++)
            {
                $posts[] = Post::where('id', $responses[$i])->get()->all()/*paginate(5)*/;
            }

        if ($filter=="top") {

            unset($posts); 
            $posts = array( );   
            for($i=0;$i<count($topresponses);$i++)
            {
                $posts[] = Post::where('id', $topresponses[$i])->get()->all()/*paginate(5)*/;
            }

        }
        elseif ($filter=="all") {

            unset($posts); 
            $posts = array( );    
            for($i=0;$i<count($responses);$i++)
            {
                $posts[] = Post::where('id', $responses[$i])->get()->all()/*paginate(5)*/;
            }

        }

        $num_posts = count($posts);
        return view('myResponses', ['post' => $posts])->with(['num_posts' => $num_posts]);
    }

    public function getlikedQuestions()//show liked qts view
    { 
        $userid=Auth::user()->id;//get this users id
        $likedposts = Like::where('user_id',$userid)->where('like',1)->orderBy('created_at', 'desc')->pluck('post_id')->all();//get all ids of qts liked by this user from likes table
        $posts = array( );
        if(count($likedposts)>50)
        {
            $loop = 50;
        }
        else
        {
            $loop = count($likedposts);
        }
        for($i=0;$i<$loop;$i++)
        {
            $posts[] = Post::where('id', $likedposts[$i])->get()->all();//get all the qts corresponding to those ids above from posts table
        }
        // $posts = (object)$posts;
        //  $posts = $posts->paginate(5);
        
        $num_posts = count($posts);//count the number of liked qts


        return view('likedQuestions', ['post' => $posts])->with(['num_posts' => $num_posts]);
    }

    public function getViewPost(Request $input ,$post_id)//view a single post when clicked on it
    { 
        $userid=Auth::user()->id;//get this users id
        $post = Post::where('id', $post_id)->first();//get the question which i clicked on
        $reply = Response::where('post_id', $post_id)->get()/*paginate(5)*/;//get the responses to that question
        $filter = $input->get("topResponses");//filter for top rated or all responses

        if ($filter=="top") {
            $reply = Response::where('post_id', $post_id)->orderBy('rscore', 'desc')
                ->get()/*paginate(5)*/;//sort by top rated responses
        }
        elseif ($filter=="all") {
            $reply = Response::where('post_id', $post_id)->get()/*paginate(5)*/;//just get all responses
        }
        return view('viewPost', ['post' => $post,'reply' => $reply]);

    }

    public function postCreatePost(Request $request)//create new qts
    {
        $this->validate($request, [
            'title' =>'required|max:100',
            'body' => 'required|max:1000',
            'tags' => 'required|max:100'
        ]);//check if all fields are filled
        $post = new Post();//make new post in post table
        $post->title = $request['title'];//fill title
        $post->body = $request['body'];//fill body
        $post->tags = $request['tags'];//fill tags
        $message = 'There was an error';//if error
        if ($request->user()->posts()->save($post)) {//save the post in the posts table
            $message = 'Post successfully created!';//no error
        }
        return redirect()->route('dashboard')->with(['message' => $message]);
    }

    public function postCreateResponse(Request $request)//create new response
    {
        $this->validate($request, [
            'body' => 'required|max:1000'//check response
        ]);
        $post_id = $request['post_id'];//get post id where response is posted
        $userid=Auth::user()->id;//get user id
        $reply = new Response();//make new response in responses table
        $reply->body = $request['body'];//fill its body
        $reply->user_id = $userid;//fill user_id by whom its posted 
        $reply->post_id = $post_id;//fill post_id where response is given
        $reply->created_at= date("Y-m-d H:i:s");
        $reply->updated_at= date("Y-m-d H:i:s");
        $reply->save();//save response in that table
        $post = Post::where('id', $post_id)->first();//get the post where response was just posted
        $responses = Response::where('post_id', $post_id)->get();//get all the response on that post
        $num_responses = count($responses);//count number of response on that post
        $post->answers = $num_responses;//save that number in the answers column of that qts 
        $post->update();//update the table
        //$message = 'There was an error';
        //dd(responses());
        // if ($request->post()->responses()->save($reply)) {
        //     $message = 'Response successfully posted!';
        // }
        return redirect()->route('post.view', ['post_id' => $post_id]);
    }

    public function getDeletePost($post_id)//delete a qts
    {
        $post = Post::where('id', $post_id)->first();//get the post which user clicked on
        $responses = Response::where('post_id', $post_id);
        $responses_id = Response::where('post_id', $post_id)->pluck('id')->all();
        $likes = Like::where('post_id',$post_id);
        // $rlikes = array();
        // $rlikes = Rlike::where('post_id',$post_id)->get();
        if (Auth::user() != $post->user) {//check if post belongs to that user
            return redirect()->back();//if not send him back
        }
        for($i=0;$i<count($responses_id);$i++)
        {
            $rlikes = Rlike::where('response_id',$responses_id[$i]);
            $rlikes->delete();
        }
        $likes->delete();
        $responses->delete();
        $post->delete();// if belongs to user then delete
        return redirect()->route('dashboard')->with(['message' => 'Successfully deleted!']);
    }

    public function getDeleteResponse($response_id,$post_id)//delete a respponse
    {
        $reply = Response::where('id', $response_id)->first();//get the response which user clicked 
        $rlikes = Rlike::where('response_id',$response_id);
        if (Auth::user() != $reply->user) {//check if that response belongs to user
            return redirect()->back();//if not send him back
        }
        $reply->delete();// if belongs to user then delete
        //then again update the count of response on that qts
        $rlikes->delete();
        $post = Post::where('id', $post_id)->first();
        $responses = Response::where('post_id', $post_id)->get();
        $num_responses = count($responses);
        $post->answers = $num_responses;
        $post->update();
        return redirect()->route('post.view', ['post_id' => $post_id]);
    }

    public function postEditPost(Request $request)//edit a qts
    {
        $this->validate($request, [
            'title' =>'required',
            'body' => 'required'
        ]);//check all fields
        $post = Post::find($request['postId']);//find the post user clicked on | postId comes from ajax
        if (Auth::user() != $post->user) {//check if post belongs to user
            return redirect()->back();//if not send him back
        }
        //if belongs then start editing
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->update();//save the edited post
        return response()->json(['new_body' => $post->body,'new_title' => $post->title], 200);
    }

    public function postLikePost(Request $request)//like a qts
    {
        $post_id = $request['postId'];//get post id from ajax for the qts to be liked
        $is_like = $request['isLike'] === 'true';
        $update = false;
        $post = Post::find($post_id);
        if (!$post) {
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id', $post_id)->first();
        if ($like) {
            $already_like = $like->like;
            $update = true;
            if ($already_like == $is_like) {
                $like->delete();
                $likedata = Like::where('post_id',$post_id)->where('like',1)->get();
                $dislikedata = Like::where('post_id',$post_id)->where('like',0)->get();
                $score = (count($likedata) - count($dislikedata));
                $post->score = $score;
                $post->update();
                return null;
            }
        } else {
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        if ($update) {
            $like->update();
        } else {
            $like->save();
        }
        //after liking that post calculate the score for that post and save in score column
        $likedata = Like::where('post_id',$post_id)->where('like',1)->get();
        $dislikedata = Like::where('post_id',$post_id)->where('like',0)->get();
        $score = (count($likedata) - count($dislikedata));
        $post->score = $score;
        $post->update();
        return null;
    }

    public function postLikeResponse(Request $request)//like a response
    {
        $response_id = $request['replyId'];//get response id from ajax for the reply to be liked
        $is_like = $request['isrLike'] === 'true';
        $update = false;
        $response = Response::find($response_id);
        if (!$response) 
        {
            return null;
        }
        $user = Auth::user();
        $like = $user->rlikes()->where('response_id', $response_id)->first();
        if ($like) 
        {
            $already_like = $like->like;
            $update = true;
            if ($already_like == $is_like) 
            {
                $like->delete();
                $rlikedata = Rlike::where('response_id',$response_id)->where('like',1)->get();
                $rdislikedata = Rlike::where('response_id',$response_id)->where('like',0)->get();
                $rscore = (count($rlikedata) - count($rdislikedata));
                $response->rscore = $rscore;
                $response->update();
                return null;
            }

        } else 
        {
            $like = new Rlike();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->response_id = $response->id;
        if ($update) {
            $like->update();
        } else {
            $like->save();
        }
        //after liking that reply calculate the score for that reply and save in rscore column
        $rlikedata = Rlike::where('response_id',$response_id)->where('like',1)->get();
        $rdislikedata = Rlike::where('response_id',$response_id)->where('like',0)->get();
        $rscore = (count($rlikedata) - count($rdislikedata));
        $response->rscore = $rscore;
        $response->update();
        return null;
    }


}