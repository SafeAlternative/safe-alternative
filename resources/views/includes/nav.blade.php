<nav class="navbar navbar-expand-md navbar-custom bg-primary"> 
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Safe Alternative') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                

                <li class="nav-item dropdown"> 
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="{{ route('curieri') }}" role="button" aria-haspopup="true" aria-expanded="false">WooCommerce</a>            
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('curieri.fan') }}">Fan Courier & Woocommerce</a>
                        <a class="dropdown-item" href="{{ route('curieri.cargus') }}">Cargus & Woocommerce</a>
                        <a class="dropdown-item" href="{{ route('curieri.nemo') }}">Nemo & Woocommerce</a>
                    </div>                        
                </li>
                <li class="nav-item" > <a class="nav-link" href="{{ route('despre-noi') }}">Despre noi</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('portofoliu') }}">Portfoliu</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('echipa') }}"> Echipa </a> </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact.create') }}">Contact</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">





                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                       <a class="nav-link" href="{{ route('login') }}"> <i class='fa fa-user'></i> {{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"> <i class='fa sign-out'></i>
                                {{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                    <li class="nav-item">
                       <a class="nav-link" href="{{ route('login') }}"> <i class='fa fa-user'></i> Contul meu ( {{ Auth::user()->username }} ) </a>
                    </li>


                    <li class="nav-item">
                       <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                        </form>
                    </li>

                    
                @endguest
            </ul>
        </div>
    </div>
</nav>