<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - VSWBMS</title>
    <meta name="description" content="Margos sa Tubig Water Billing System (Web-Base) - MTWBS">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Lato.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/pikaday.min.css">
    <link rel="stylesheet" href="assets/css/Pricing-Free-Paid-badges.css">
    <link rel="stylesheet" href="assets/css/Pricing-Free-Paid-icons.css">
    <link rel="stylesheet" href="assets/css/Profile-with-data-and-skills.css">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-white portfolio-navbar gradient">
        <div class="container"><a class="navbar-brand logo" href="/">VSWBMS</a><button data-bs-toggle="collapse"
                class="navbar-toggler" data-bs-target="#navbarNav"><span class="visually-hidden">Toggle
                    navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link" type="button" data-bs-target="#login"
                                data-bs-toggle="modal">Login</a></li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>
    <main class="page lanidng-page">
        <section>
            <div class="container mt-2">
                <h2>About</h2>
                <p style="text-align: justify">
                    This Water Billing Management System can be only accessed by the Management. The system requires the
                    users to log in with their user credentials to gain access to the features and functionalities of
                    the system. This project has 3 types of user roles which are the Admin, Collector and Cashier users.
                    The Admin users have the privilege to access and manage all the features and functionalities on the
                    system while the collector and cashier has only limited permissions. The system stores the
                    consumer's basic information, and management can also list the billing history of the consumer. On
                    the billing feature, the system automatically calculates the total amount bill of the consumer. The
                    project also generates a printable Monthly Billing Report.
                </p>
            </div>

            <div class="container">
                <div class="row py-5 justify-content-center">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2 mb-3">
                        <img style="width: 100%; height: 150px" class="img-thumbnail" src="/assets/img/joyce_ann.jpg">
                        <div class="mt-2">
                            <h5 class="text-center mb-0">Joyce Ann Yalon</h5>
                            <p class="text-center">Analyst</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2 mb-3">
                        <img style="width: 100%; height: 150px" class="img-thumbnail" src="/assets/img/aisha.jpg">
                        <div class="mt-2">
                            <h5 class="text-center mb-0">Aisha Manto</h5>
                            <p class="text-center">Analyst</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2 mb-3">
                        <img style="width: 100%; height: 150px" class="img-thumbnail" src="/assets/img/jovelyn.jpg">
                        <div class="mt-2">
                            <h5 class="text-center mb-0">Jovelyn Pamada</h5>
                            <p class="text-center">Designer</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2 mb-3">
                        <img style="width: 100%; height: 150px" class="img-thumbnail" src="/assets/img/juvelyn.jpg" />
                        <div class="mt-2">
                            <h5 class="text-center mb-0">Juvelyn Chiong</h5>
                            <p class="text-center">Manager</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2 mb-3">
                        <img style="width: 100%; height: 150px" class="img-thumbnail" src="/assets/img/mark.jpg">
                        <div class="mt-2">
                            <h5 class="text-center mb-0">Mark Bryn Bogol</h5>
                            <p class="text-center">Programmer</p>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </main>
    <footer class="page-footer">
        <div class="container">
            <div class="social-icons"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i
                        class="icon ion-social-instagram-outline"></i></a><a href="#"><i
                        class="icon ion-social-twitter"></i></a>
            </div>
        </div>
    </footer>
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="login">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4><button class="btn-close" type="button" aria-label="Close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/login">
                        @csrf

                        <div class="text-center"><img class="mb-3" src="assets/img/image1.png" width="80">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Username</label>
                            @error('username')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <input class="form-control item" type="text" name="username">
                        </div>
                        <div class="mb-3"><label class="form-label" for="subject">Password</label><input
                                class="form-control item" type="password" name="password"></div>
                        <div class="mb-3"><button class="btn btn-primary btn-lg d-block w-100"
                                type="submit">Login</button></div>
                    </form>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button"
                        data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pikaday.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</body>
