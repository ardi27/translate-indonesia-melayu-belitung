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
          <form action="{{url('konfigurasi')}}/{{$konfigurasi[0]->id}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" name='leven' type="checkbox" value="1" id="flexCheckDefault"
                  {{$konfigurasi[0]->leven=='1'?'checked':'' }}>
                <label class="form-check-label" for="flexCheckDefault">
                  Levenshtein aktif
                </label>
              </div>
              <button type="submit" class="btn btn-info text-white">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Button trigger modal -->


<!-- Modal -->
@endsection