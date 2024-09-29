@extends('root')
@section('body')
    @if (count($print_ids) > 0)
        <table border="0">
                @foreach($print_products as $i => $item)
                    <td>
                                    <div class="print-barcode-product">
                                        <h3>{{@$item->name}}</h3>
                                        <span class="upc-barcode-lg"> {{ $item->code }} </span>
                                        <span class="upc-barcode-lg-code">{{ $item->code }}</span>
                                    </div>
                    </td>
                        @if ($i%2 == 1 && count($print_products) != $i)
                        </tr><tr>
                        @endif
                @endforeach
            </tr>
        </table>
    @endif
    <script>
        print();
    </script>
@endsection
@section('header_tag')
    <link rel="stylesheet" href="{{ url('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+EAN13+Text&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        .upc-barcode,
        .ean-barcode {
            font-family: 'Libre Barcode 128', sans-serif;
            font-size: 42px;
            transform: scale(.5, 1);
        }
        .upc-barcode-lg {
            font-family: 'Libre Barcode 128', sans-serif;
            font-size: 64px;
            transform: scale(.5, 1);
            line-height: 1;
        }
        .upc-barcode-lg-code {
            font-family: 'Libre Barcode EAN13 Text', sans-serif;
            font-size: 12pt;
            line-height: 0.5;
        }
        .print-barcode-product {
            display: inline-block;
            width: 8cm;
            padding: 16px;
            border: grey 1px solid;
            margin-right: 0.5cm;
            margin-bottom: 0.5cm;
        }
        .paper{
            display: block;
            margin-right: 0;
            margin-left: 0;
        }
    </style>
@endsection
@section('header_script')
    <script src="{{ url('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ url('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ url('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
@endsection
