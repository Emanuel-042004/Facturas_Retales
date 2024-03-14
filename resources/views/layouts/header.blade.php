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
                        <h1 class="title">{{Auth::user()->name}}</h1><br>
                    </a>
                    <h3 class="title">{{Auth::user()->area}}</h3>
                </li>


                <li>

                    <a href="{{route ('home')}}">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Home</span>
                    </a>
                </li>

                <li>
                    <a href="{{route ('home')}}">
                        <span class="icon">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                        </span>
                        <span class="title">Importar Archivos</span>
                    </a>
                </li>

                <li>
                    <a href=" {{route('pendientes.index') }}">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Facturas DIAN</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('reembolsos.index') }}">
                        <span class="icon">
                        <ion-icon name="refresh-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Reembolsos</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('cargados.index') }}">
                        <span class="icon">
                        <ion-icon name="save-outline"></ion-icon>

                        </span>
                        <span class="title">Cargadas</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('causados.index') }}">
                        <span class="icon">
                        <ion-icon name="calculator-outline"></ion-icon>
                        </span>
                        <span class="title">Causaciones</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                        <ion-icon name="wallet-outline"></ion-icon>
                        </span>
                        <span class="title">Pagos</span>
                    </a>
                </li>

                

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Finalizadas</span>
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

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <!--<div class="search">
                    <label>
                        <input type="text" placeholder="Buscar">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>-->

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


    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>