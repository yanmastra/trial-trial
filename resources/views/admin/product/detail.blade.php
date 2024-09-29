@extends('admin')
@section('content')
    <!-- <div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product</h1>
      </div>
    </div>
  </div>
</div> -->
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-default">
                        <div class="card-header">
                            Detail Product
                        </div>
                        <div class="card-body">
                            <table border="0">
                                <thead>
                                    <th colspan="3">{{@$product->name}}</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Code</td>
                                        <td>:</td>
                                        <td>{{@$product->code}}</td>
                                    </tr>
                                    <tr>
                                        <td>Price</td>
                                        <td>:</td>
                                        <td>Rp {{ number_format(@$product->price, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Cost Price</td>
                                        <td>:</td>
                                        <td>Rp {{ number_format(@$product->cost_price, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Stock</td>
                                        <td>:</td>
                                        <td>{{@$product->stock}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
