@extends('admin')
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div>
    </div>
  </div>
</div>
<section class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-8 offset-lg-2">
              <div class="row">
                  <div class="col-md-6 col-12">
                      <a href="{{url('transaction/form')}}">
                          <div class="info-box bg-gradient-green">
                              <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                              <div class="info-box-content">
                                  <h2 class="info-box-text">Create Transaction</h2>
                                  <span class="progress-description">Go to transaction form</span>
                              </div>
                          </div>
                      </a>
                  </div>

                  <div class="col-md-6 col-12">
                      <a href="{{url('transaction')}}">
                          <div class="info-box bg-gradient-blue">
                              <span class="info-box-icon"><i class="fas fa-list"></i></span>
                              <div class="info-box-content">
                                  <h2 class="info-box-text">Transaction List</h2>
                                  <span class="progress-description">Go to transaction list</span>
                              </div>
                          </div>
                      </a>
                  </div>

                  <div class="col-md-6 col-12">
                      <a href="{{url('product')}}">
                          <div class="info-box bg-gradient-cyan">
                              <span class="info-box-icon"><i class="fas fa-list"></i></span>
                              <div class="info-box-content">
                                  <h2 class="info-box-text">Product</h2>
                                  <span class="progress-description">Go to master data product</span>
                              </div>
                          </div>
                      </a>
                  </div>


                  <div class="col-md-6 col-12">
                      <a href="{{url('close_cash')}}">
                          <div class="info-box bg-gradient-orange">
                              <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                              <div class="info-box-content">
                                  <h2 class="info-box-text">Close Cash Report</h2>
                                  <span class="progress-description">Go to Closed cash list</span>
                              </div>
                          </div>
                      </a>
                  </div>
                  <!-- /.col -->
              </div>
          </div>
      </div>
  </div>
</section>
@endsection
