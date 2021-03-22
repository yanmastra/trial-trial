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

    <style>
        .btn-focused:focus{
            border: solid 2px #128294;
            box-shadow: #c1d1df;
        }
    </style>
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3>{{ (@$action == 'check'?'Check stock for ':'Add stock for ').' '.@$product->name}}</h3>
                            <h3>Current stock in system : {{@number_format($product->stock, 2, ',', '.')}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{url('product/'.(@$action == 'check'?'check_stock/':'up_stock/').@$id)}}" method="post">
                                <div class="form-group">
                                    <label>{{(@$action == 'check'?'Real stock ':'Stock ')}}</label>
                                    @csrf
                                    <input class="form-control" id="stock" type="number" min="{{(@$action == 'check'?0:1)}}" value="{{(@$action == 'check'?0:1)}}" onkeyup="countDiff()" name="stock" placeholder="Stock..." required autofocus >
                                </div>
                                @if(@$action == 'check')
                                    <div class="form-group">
                                        <label>Difference</label>
                                        <input class="form-control" type="number" value="0" id="diff">
                                        <input id="stock_in_system" value="{{@$product->stock}}" hidden>
                                    </div>
                                    <div class="form-group" id="remark-container">
                                    </div>
                                @endif
                                <button type="submit" id="submit" class="btn btn-default btn-focused btn-lg" autofocus><i class="fas fa-save"></i>SAVE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function countDiff() {
            var system_stock = $('#stock_in_system').val();
            var real_stock = $('#stock').val();
            var diff = 0;
            diff = system_stock - real_stock;
            var inputRemark = "\n" +
                "                                        <label>Remark</label>\n" +
                "                                        <input class=\"form-control\" type=\"text\" name=\"remark\" id=\"remark\" required>";
            if(diff !== 0){
                $('#remark-container').html(inputRemark);
            } else{
                $('#remark-container').html('');
            }
            $('#diff').val(diff);
        }
        countDiff();
    </script>
@endsection
