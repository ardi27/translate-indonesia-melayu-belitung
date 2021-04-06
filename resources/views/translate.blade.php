<!doctype html>
<html lang="en">


<head>
    <title>Translate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">

    <link rel="stylesheet" href="/assets/fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">

    <!-- Theme Style -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <header role="banner">

        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="height: 70px;">
            <div class="container">
                <a class="navbar-brand  absolute" href="index.html">Translate Bahasa Melayu Belitung</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
                    aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


            </div>
        </nav>
    </header>
    <!-- END header -->

    <section class="site-hero overlay" data-stellar-background-ratio="0."
        style="background-image: url(assets/images/bg-iso.jpg);">
        <div class="container">
            <div class="container">
                <br />
                <br />
                <br />
                <div class="form-group shadow-textarea">
                    <textarea class="form-control z-depth-1" name="search_text" id="search_text" rows="8"
                        placeholder="Masukkan Bahasa Melayu Belitung"></textarea>
                    <textarea class="form-control z-depth-1" name="result" readonly id="result" rows="8"
                        placeholder="Tenjemahan Bahasa Indonesia"></textarea>

                </div>
                <br />

            </div>
            <div style="clear:both"></div>
            <br />
            <br />
            <br />
            <br />
        </div>
    </section>





    <!-- loader -->

    <script src="/assets/js/jquery-3.2.1.min.js"></script>
    <script src="/assets/js/jquery-migrate-3.0.0.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/owl.carousel.min.js"></script>
    <script src="/assets/js/jquery.waypoints.min.js"></script>
    <script src="/assets/js/jquery.stellar.min.js"></script>
    <script src="/assets/js/jquery.animateNumber.min.js"></script>

    <script src="/assets/js/jquery.magnific-popup.min.js"></script>

    <script src="/assets/js/main.js"></script>
    <script>
        $(document).ready(function() {

            load_data();

            function load_data(query) {
                $.ajax({
                    url: "/proses",
                    method: "GET",
                    data: {
                        kata: query
                    },
                    success: function(data) {

                        let kalimat = "";
                        for (let i = 0; i < data.length; i++) {
                            kalimat += data[i];
                        }
                        $('#result').val(kalimat);
                    }
                })
            }

            function cepat(f, delay) {
                var timer = null;
                return function() {
                    var context = this,
                        args = arguments;
                    clearTimeout(timer);
                    timer = window.setTimeout(function() {
                        f.apply(context, args)
                    }, delay || 1000);

                };
            }

            $('#search_text').keyup(cepat(function() {

                var search = $(this).val();
                load_data(search);

            }));
        });
    </script>
</body>

</html>