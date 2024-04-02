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
        <div class="container"><a class="navbar-brand logo" href="#">VSWBMS</a><button data-bs-toggle="collapse"
                class="navbar-toggler" data-bs-target="#navbarNav"><span class="visually-hidden">Toggle
                    navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="page lanidng-page">
        <section class="portfolio-block mobile-app">
            <div class="container align-items-center">
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-3 offset-lg-2">
                        <div class="portfolio-phone-mockup">
                            <div class="phone-screen"
                                style="background: url(&quot;assets/img/image1.png&quot;) center / contain no-repeat;">
                            </div>
                            <div class="home-button"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-5 text">
                        <h3>Vincenzo Sagun Water Billing Management System (Web-Base) - VSWBMS</h3>
                        <p>Researchers believe that the proposed web-based water billing system will contribute to the
                            growth and improvement of Vincenzo Sagun Municipality's services to the community and its
                            stakeholders.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="page-footer">
        <div class="container">
            <div class="social-icons"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i
                        class="icon ion-social-instagram-outline"></i></a><a href="#"><i
                        class="icon ion-social-twitter"></i></a></div>
        </div>
    </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pikaday.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</body>

</html>
