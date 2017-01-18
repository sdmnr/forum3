<header style=" position:fixed; top:0;left: 0;height:40px; width: 100%;z-index: 99999;">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div>                
                <a class="navbar-brand" href="{{ route('dashboard') }}"><p style="font-size: 100%;"> ITLH: Learning Redifined</p></a></div>
                <!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                
                </ul>
            </div> -->
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('dashboard') }}">{{Auth::user()->first_name}} : {{Auth::user()->uscore}} : Dashboard</a></li>
                    <li><a href="{{ route('allQuestions') }}">All Questions</a></li>
                    <li><a href="{{ route('myQuestions') }}">My Questions</a></li>
                    <li><a href="{{ route('likedQuestions') }}">Liked Questions</a></li>
                    <li><a href="{{ route('myResponses') }}">My Responses</a></li>
                    <li><a href="{{ route('account') }}">Account</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>