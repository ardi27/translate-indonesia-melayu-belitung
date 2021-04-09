@extends('header')
@section('content')
@if(session('success'))
<div class="alert alert-success" role="alert">
    {{session('success')}}
</div>
@endif
@if($errors->any())
<div class="alert alert-danger" role="alert">
    {{$errors->first()}}
</div>
@endif
<div class="card mx-5 my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-info text-white float-right my-3" onclick="showModal()">
                        Tambah
                    </button>
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
                                <td> <button class="btn btn-warning text-white"
                                        onclick="showModal({{json_encode($value)}})">
                                        Update
                                    </button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <form action="" id="formUpdate" method="POST">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Aturan Belitung</label>
                        <input type="text" required class="form-control" name="aturan_belitung" id="formAturanBelitung"
                            aria-describedby="emailHelp" placeholder="Masukkan Aturan Belitung">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Aturan Indonesia</label>
                        <input type="text" required class="form-control" name="aturan_indo" id="formAturanIndo"
                            aria-describedby="emailHelp" placeholder="Masukkan Aturan Indonesia">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
    $(document).ready( function () {
    $('#data-table').DataTable();
} );
function showModal(json){
    if(json==null||json==""){
        document.getElementById('title').innerText=`Tambah Imbuhan`;
        document.getElementById('formUpdate').action=`{{url('imbuhan/tambah')}}`;
        $("#modalUpdate").modal();
    }else{
    document.getElementById('title').innerText=`Ubah ${json.aturan_belitung}`;
    document.getElementById('formAturanBelitung').value=json.aturan_belitung;
    document.getElementById('formAturanIndo').value=json.aturan_indo;
    document.getElementById('formUpdate').action=`{{url('imbuhan/update/${json.id_aturan}')}}`;
    $("#modalUpdate").modal();
    }
    }
</script>
@endsection