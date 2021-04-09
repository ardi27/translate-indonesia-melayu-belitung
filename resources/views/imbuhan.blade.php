@extends('header')
@section('content')
<div class="card mx-5 my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Melayu Belitung</th>
                                <th>Indonesia</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($imbuhan as $key=>$value)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$value->aturan_belitung}}</td>
                                <td>{{$value->aturan_indo}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready( function () {
    $('#data-table').DataTable();
} );
</script>
@endsection