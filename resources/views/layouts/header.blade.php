<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LR | Facturacion</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a id="user" href="#">
                        <span class="icon">
                            <ion-icon id="icon1" name="person-circle-outline"></ion-icon>
                        </span>
                        <h2 class="title">{{Auth::user()->name}}</h2><br>
                    </a>
                    <h3 class="title">{{Auth::user()->rol}}</h3>
                </li>


              <!--  <li>
                    <a href="{{route ('home')}}">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Home</span>
                    </a>
                </li>-->
                @can('home')
                <li>
                    <a href="{{route ('home')}}">
                        <span class="icon">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                        </span>
                        <span class="title">Importar Archivos</span>
                    </a>
                </li>
                @endcan
                @can('cargar_pendiente')
                <li>
                    <a href="{{route('pendientes.index') }}">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Facturas DIAN</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Pen...</span>
                    </a>
                </li>
                @endcan
               
                <li>
                    <a href="{{route('reembolsos.index') }}">
                        <span class="icon">
                        <ion-icon name="refresh-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Reembolsos</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Reem...</span>
                    </a>
                </li>
                
                @can('causar')
                <li>
                    <a href="{{route('reembolsos_causados.index') }}">
                        <span class="icon">
                        <ion-icon name="bag-check-outline"></ion-icon>
                        </span>
                        <span class="title">Causaciones de reembolso</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Cau/Rbs</span>
                    </a>
                </li>
                @endcan

                @can('causar')
                <li>
                    <a href="{{ route('cargados.index') }}">
                        <span class="icon" id="save-icon">
                            <ion-icon name="save-outline"></ion-icon>
                        </span>
                        <span class="title">Cargadas</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Cargadas</span>
                    </a>
                </li>
                @endcan

                @can('causar')
                <li>
                    <a href="{{route('aprobadas.index') }}">
                        <span class="icon" id="save-icon">
                        <ion-icon name="checkmark-outline"></ion-icon>
                        </span>
                        <span class="title">Aprobadas</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Aprobadas</span>
                    </a>
                </li>
                @endcan
                
                @can('carga_egreso')
                <li>
                    <a href="{{route('causados.index') }}">
                        <span class="icon">
                        <ion-icon name="calculator-outline"></ion-icon>
                        </span>
                        <span class="title">Causaciones</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Causa...</span>
                    </a>
                </li>
                @endcan
                @can('carga_egreso')
                <li>
                    <a href="{{route('pagos.index')}}">
                        <span class="icon">
                        <ion-icon name="wallet-outline"></ion-icon>
                        </span>
                        <span class="title">Pagos</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Pagos</span>
                    </a>
                </li>
                @endcan

                

                <li>
                    <a href="{{route('finalizadas.index')}}">
                        <span class="icon">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Finalizadas</span>
                        <!-- Texto flotante -->
                        <span class="tooltip">Fin...</span>
                    </a>
                </li>

                <li>
                    <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">{{ __('Salir') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @csrf
                </li>

            </ul>
        </div>
        <script>
            // Obtén todos los elementos de navegación
            let navItems = document.querySelectorAll('.navigation ul li a');

            // Añade un evento de click a cada elemento de navegación
            navItems.forEach((navItem) => {
            // Obtén la ruta del elemento de navegación
            let navItemPath = navItem.getAttribute('href');

            // Compara la ruta del elemento de navegación con la ruta actual
            if (window.location.href.indexOf(navItemPath) !== -1) {
                // Añade la clase 'active' al elemento de navegación si las rutas coinciden
                navItem.parentElement.classList.add('active');
            }
            });

        </script>
        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="user">
                    <img src="{{asset('imagenes/logo2.jpeg')}}" alt="">
                </div>
            </div>

            <div class="cardBox">
                <a href="{{ url()->current() . '?area=Compras' }}" class="card">
                    <div>
                        <div class="areas">Compras</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="bag-handle-outline"></ion-icon>
                    </div>
                </a>

                <a href="{{ url()->current() . '?area=Tecnologia' }}" class="card">
                    <div>
                        <div class="areas">Tecnologia</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </div>
                </a>

                <a href="{{ url()->current() . '?area=Financiera' }}" class="card">
                    <div>
                        <div class="areas">Financiera</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                </a>

                <a href="{{ url()->current() . '?area=Logistica' }}" class="card">
                    <div>
                        <span class="areas">Logística</span>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="git-branch-outline"></ion-icon>
                    </div>
                </a>

                <a href="{{ url()->current() . '?area=Mantenimiento' }}" class="card">
                    <div>
                        <div class="areas">Mantenimiento</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="construct-outline"></ion-icon>
                    </div>
                </a>
            </div>

            <!-- ================ Order Details List ================= -->

            <div class="details">

                @yield('content')

            </div>
        </div>
    </div>



    <!-- =========== Scripts =========  -->
    <script>
        let list = document.querySelectorAll(".navigation li");

        function activeLink() {
            list.forEach((item) => {
                item.classList.remove("hovered");
            });
            this.classList.add("hovered");
        }

        list.forEach((item) => item.addEventListener("mouseover", activeLink));

        // Menu Toggle
        let toggle = document.querySelector(".toggle");
        let navigation = document.querySelector(".navigation");
        let main = document.querySelector(".main");

        toggle.onclick = function () {
            navigation.classList.toggle("active");
            main.classList.toggle("active")
        ;
      }
    </script>


     ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   
    <script src="{{ asset('js/costos.js') }}"></script>
</body>
<!--<footer>
    <p>Los Retales Todos los derechos reservados - Desarrollado por Emanuel Chara Gomez</p>
</footer>-->
</html> 