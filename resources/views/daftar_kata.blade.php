@extends('header')
@section('content')
@if(session('success'))
<div class="alert alert-success" role="alert">
    {{session('success')}}
</div>
@endif
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
                            @foreach ($daftarKata as $key=>$kata)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$kata->katadasar}}</td>
                                <td>{{$kata->arti_kata}}</td>
                                <td><button class="btn btn-warning text-white"
                                        onclick="showModal({{$kata->id_katadasar}},'{{$kata->katadasar}}','{{$kata->arti_kata}}')">
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
<!-- Button trigger modal -->


<!-- Modal -->
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
                        <label for="exampleInputEmail1">Kata Asli</label>
                        <input type="text" class="form-control" name="katadasar" id="formKataAsli"
                            aria-describedby="emailHelp" placeholder="Masukkan Kata Asli">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Terjemahan</label>
                        <input type="text" class="form-control" name="arti_kata" id="formTerjemahan"
                            aria-describedby="emailHelp" placeholder="Masukkan Terjemahan">
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
}
);
 function showModal(id,kataAsli,artiKata){
    document.getElementById('title').innerText=`Ubah ${kataAsli}`;
    document.getElementById('formKataAsli').value=kataAsli;
    document.getElementById('formTerjemahan').value=artiKata;
    document.getElementById('formUpdate').action=`{{url('daftar-kata/update/${id}')}}`;
    $("#modalUpdate").modal();
    }
</script>
@endsection