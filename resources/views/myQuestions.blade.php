@extends('layouts.master')

@section('content')
    @include('includes.message-block')

    <section class="row searchposts">
        <div class="col-md-6 col-md-offset-3 SearchParent">
            <header><h3>Search from My Questions</h3></header>
            <div class="searchArea">
            <form action="myQuestions" method="get"><input type="text" name="searchBar" id="searchBar"><input type="submit" value="Search">Search Returned : {{$searched_posts}} Questions </form>
            <form action="myQuestions" method="get"> 
            <select name="topMyQuestions">
              <option value="top">Show Top Rated Questions</option>
              <option value="all">Show All Questions</option>
            </select> 
            <input type="submit" value="Filter"> 
            </form>
            </div>
        </div>
    </section>

    <section class="row posts">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>My Questions : {{$num_posts}}</h3></header>
            @foreach($posts as $post)
                <article class="post" data-postid="{{ $post->id }}">
                	<h2>{{ $post->title }}</h2>
                    <!-- <p>{{-- $post->body --}}</p> -->
                    <div class="info">
                        Posted by @if(Auth::user()->id == $post->user->id) You @else {{ $post->user->first_name }} @endif | on {{ $post->created_at }} | Score: {{$post->score}} | Answers: {{ $post->answers }}
                    </div>
                    <div class="interaction">
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like'  }}</a> |
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike'  }}</a> |
                        <a href="{{ route('post.view', ['post_id' => $post->id]) }}" class="view">View/Reply</a>
                        @if(Auth::user() == $post->user)
                            | 
                            <a href="{{ route('post.delete', ['post_id' => $post->id]) }}">Delete</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    {{ $posts->links() }}

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
        var urlLike = '{{ route('like') }}';
    </script>



@endsection