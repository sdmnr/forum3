@extends('layouts.master')

@section('content')
    @include('includes.message-block')
    <section class="row posts">
        <div class="col-md-6 col-md-offset-3">
                <article class="post" data-postid="{{ $post->id }}">
                	<h3>{{ $post->title }}</h3>
                    <p>{{ $post->body }}</p>
                    <form>Tags:<input name="tags" value="{{ $post->tags }}" data-role="tagsinput" rows="5" disabled="disabled" ></form>
                    <!-- <p>Tags: {{-- $post->tags --}}</p> -->
                    <div class="info">
                        Posted by @if(Auth::user()->id == $post->user->id) You @else {{ $post->user->first_name }} @endif | on {{ $post->created_at }} | Score: {{$post->score}} | Answers: {{ $post->answers }}
                    </div>
                    <div class="interaction">
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like'  }}</a> |
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike'  }}</a> <!-- | 
                            <a href="#" class="reply">Reply</a> -->
                        @if(Auth::user() == $post->user)
                            | 
                            <a href="#" class="edit">Edit</a> |
                            <a href="{{ route('post.delete', ['post_id' => $post->id]) }}">Delete</a>
                        @endif
                    </div>
                </article>
        </div>
    </section>

    <section class="row responses">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Responses</h3></header>
            <form action="{{ route('post.view', ['post_id' => $post->id]) }}" method="get"> 
            <select name="topResponses">
              <option value="top">Show Top Rated Responses</option>
              <option value="all">Show All Responses</option>
            </select> 
            <input type="submit" value="Filter"> 
            </form><br/>
            @foreach($reply as $reply)
                <article class="response" data-replyid="{{ $reply->id }}">
                    <p>{{ $reply->body }}</p>
                    <div class="info">
                        Posted by @if(Auth::user()->id == $reply->user->id) You @else<a href="#" class="profile">{{ $reply->user->first_name }}</a> @endif | on {{$reply->created_at }} | Score: {{$reply->rscore}}
                    </div>
                    <div class="rinteraction">
                        <a href="#" class="rlike">{{ Auth::user()->rlikes()->where('response_id', $reply->id)->first() ? Auth::user()->rlikes()->where('response_id', $reply->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like'  }}</a> |
                        <a href="#" class="rlike">{{ Auth::user()->rlikes()->where('response_id', $reply->id)->first() ? Auth::user()->rlikes()->where('response_id', $reply->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike'  }}</a>

                        @if(Auth::user() == $reply->user)
                            |
                            <a href="{{ route('response.delete', ['response_id' => $reply->id,'post_id' => $post->id]) }}">Delete</a>
                        @endif
                    </div>
                </article>
                @endforeach
        </div> 
    </section>

    <section class="row new-response">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>What do you have to say?</h3></header>
            <form action="{{ route('response.create') }}" method="post">
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" value="{{ Session::token() }}" name="_token">
                <div class="form-group">
                    <textarea class="form-control" name="body" id="new-reply" rows="5" placeholder="Description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Response</button>
                
            </form>
        </div>
    </section>

<div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Post</h4>
                </div>
                <div class="modal-body">
                    <form>
                    	<label for="post-body">Edit the Post</label>
                    	<div class="form-gorup">
                            <input type="text"  id="post-title" class="form-control" name="post-title" rows="5" placeholder="Title">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="post-body" id="post-body" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-save">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="profile-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Profile</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        var token = '{{ Session::token() }}';
        var urlEdit = '{{ route('edit') }}';
        var urlLike = '{{ route('like') }}';
        var urlrLike = '{{ route('rlike') }}';
    </script>
@endsection