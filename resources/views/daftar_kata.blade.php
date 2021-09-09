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
                            @foreach ($daftarKata as $key=>$kata)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$kata->katadasar}}</td>
                                <td>{{$kata->arti_kata}}</td>
                                <td>
                                    <button class="btn btn-warning text-white" onclick="showModal({{json_encode($kata)}})">
                                        Update
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <label for="exampleInputEmail1">Kata Asli</label>
                        <input type="text" required class="form-control" name="katadasar" id="formKataAsli" aria-describedby="emailHelp" placeholder="Masukkan Kata Asli">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Terjemahan</label>
                        <input type="text" required class="form-control" name="arti_kata" id="formTerjemahan" aria-describedby="emailHelp" placeholder="Masukkan Terjemahan">
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
    $(document).ready(function() {
        $('#data-table').DataTable();
    });

    function showModal(json) {
        if (json == null || json == "") {
            document.getElementById('title').innerText = `Tambah Kata Dasar`;
            document.getElementById('formUpdate').action = `{{url('daftar-kata/tambah')}}`;
            $("#modalUpdate").modal();
        } else {
            document.getElementById('title').innerText = `Ubah ${json.katadasar}`;
            document.getElementById('formKataAsli').value = json.katadasar;
            document.getElementById('formTerjemahan').value = json.arti_kata;
            document.getElementById('formUpdate').action = `{{url('daftar-kata/update/${json.id_katadasar}')}}`;
            $("#modalUpdate").modal();

        }
    }
</script>
@endsection