@extends('layouts.master')

@section('content')
    @include('includes.message-block')

    <section class="row" style="padding-top: 20px;">
        <div class="col-md-6 col-md-offset-3">
            <form action="myResponses" method="get"> 
            <select name="topMyResponses">
              <option value="top">My Top Rated Responses</option>
              <option value="all">All Responses By Me</option>
            </select> 
            <input type="submit" value="Filter"> 
            </form>
            </div>
    </section>

    <section class="row posts">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>You Responded To: {{$num_posts}} Question(s)</h3></header>
            @for($i=0;$i<count($post);$i++)
                <article class="post" data-postid="{{ $post[$i][0]->id }}">
                    <h2>{{ $post[$i][0]->title }}</h2>
                    <div class="info">
                        Posted by @if(Auth::user()->id == $post[$i][0]->user->id) You @else {{ $post[$i][0]->user->first_name }} @endif | on {{ $post[$i][0]->created_at }} Score: {{$post[$i][0]->score}} | Answers: {{ $post[$i][0]->answers }}
                    </div>
                    <div class="interaction">
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post[$i][0]->id)->first() ? Auth::user()->likes()->where('post_id', $post[$i][0]->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like'  }}</a> |
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post[$i][0]->id)->first() ? Auth::user()->likes()->where('post_id', $post[$i][0]->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike'  }}</a> |
                        <a href="{{ route('post.view', ['post_id' => $post[$i][0]->id]) }}" class="view">View/Reply</a>
                        @if(Auth::user() == $post[$i][0]->user)
                            | 
                            <a href="{{ route('post.delete', ['post_id' => $post[$i][0]->id]) }}">Delete</a>
                        @endif
                    </div>
                </article>
            @endfor
        </div>
    </section>


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