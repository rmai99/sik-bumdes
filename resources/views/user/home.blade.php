@extends('user/layout/template')

@section('title', 'Upgrade to PRO')

@section('title-page', 'Upgrade to PRO')

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-8 ml-auto mr-auto">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">PRO Akun</h4>
            <p class="card-category">Dapatkan semua fitur dengan mengubah perusahaan kamu menjadi PRO</p>
          </div>
          <div class="card-body">
            <div class="table-responsive table-upgrade">
              <table class="table">
                <thead>
                  <tr>
                    <th style="background: none!important;color: #3c4858;"></th>
                    <th class="text-center" style="background: none!important;color: #3c4858;">Free</th>
                    <th class="text-center" style="background: none!important;color: #3c4858;">PRO</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Tambah Bisnis</td>
                    <td class="text-center">
                        <span class="material-icons" style="color:#f44336;font-size:1.1rem;">close</span>
                    </td>
                    <td class="text-center">
                        <span class="material-icons" style="color:#4caf50;font-size:1.1rem;">check</span>
                    </td>
                  </tr>
                  <tr>
                    <td>Tambah Karyawan</td>
                    <td class="text-center">
                        <span class="material-icons" style="color:#f44336;font-size:1.1rem;">close</span>
                    </td>
                    <td class="text-center">
                        <span class="material-icons" style="color:#4caf50;font-size:1.1rem;">check</span>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="text-center">Free</td>
                    <td class="text-center">Just $49</td>
                  </tr>
                  <tr>
                    <td class="text-center" style="border-bottom:none;"></td>
                    <td class="text-center" style="border-bottom:none;">
                      <a href="#" class="btn btn-round btn-fill btn-default disabled">Current Version</a>
                    </td>
                    <td class="text-center" style="border-bottom:none;">
                      <a target="_blank" href="#" class="btn btn-round btn-fill btn-info">Upgrade to PRO</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection