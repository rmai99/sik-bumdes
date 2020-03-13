<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pricing</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{url('/')}}/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />

    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{url('/')}}/assets/demo/demo.css" rel="stylesheet" />

    <link href="{{url('/')}}/assets/css/style.css" rel="stylesheet" />
</head>

<body class="off-canvas-sidebar">
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
        <div class="container">
            <div class="navbar-wrapper">
                <a class="navbar-brand" href="javascript:;">Pricing Page</a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link">
                            <i class="material-icons">dashboard</i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper wrapper-full-page">
        <div class="page-header pricing-page header-filter"
            style="background-image: url('../../assets/img/city.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ml-auto mr-auto text-center">
                        <h2 class="title">Upgrade You Account Now</h2>
                        <h5 class="description">Dapatkan semua fitur dengan ubah akun kamu menjadi PRO.
                        </h5>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-pricing card-plain">
                            <h6 class="card-category"> Reguler</h6>
                            <div class="card-body">
                                <div class="card-icon icon-white ">
                                    <i class="material-icons">home</i>
                                </div>
                                <h3 class="card-title">FREE</h3>
                                <p class="card-description">This is good if your company only have one business.
                                </p>
                            </div>
                            <div class="card-footer justify-content-center ">
                                <a href="#" class="btn btn-round btn-white">Choose Plan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-pricing">
                            <h6 class="card-category text-white"> PRO</h6>
                            <div class="card-body">
                                <div class="card-icon icon-rose">
                                    <i class="material-icons">business</i>
                                </div>
                                <h3 class="card-title">Rp300.000</h3>
                                <p class="card-description">This is good if your company have more than 1 business</p>
                            </div>
                            <div class="card-footer justify-content-center ">
                                <a href="#" class="btn btn-round btn-rose">Choose Plan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>