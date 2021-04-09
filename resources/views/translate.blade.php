@extends('app')
@section('content')
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
                    placeholder="Terjemahan Bahasa Indonesia"></textarea>

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
@endsection
@section('script')
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
@endsection