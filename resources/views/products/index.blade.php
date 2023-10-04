@extends('layouts.master')
@section('title')
المنتجات
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/sweet-alert.css') }}" rel="stylesheet">
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
  <div class="my-auto">
    <div class="d-flex">
      <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
        المنتجات</span>
    </div>
  </div>
  <div class="d-flex my-xl-auto right-content">
    <div class="pr-1 mb-3 mb-xl-0">
      <button type="button" class="btn btn-info btn-icon ml-2"><i class="mdi mdi-filter-variant"></i></button>
    </div>
    <div class="pr-1 mb-3 mb-xl-0">
      <button type="button" class="btn btn-danger btn-icon ml-2"><i class="mdi mdi-star"></i></button>
    </div>
    <div class="pr-1 mb-3 mb-xl-0">
      <button type="button" class="btn btn-warning  btn-icon ml-2"><i class="mdi mdi-refresh"></i></button>
    </div>
    <div class="mb-3 mb-xl-0">
      <div class="btn-group dropdown">
        <button type="button" class="btn btn-primary">14 Aug 2019</button>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuDate" data-x-placement="bottom-end">
          <a class="dropdown-item" href="#">2015</a>
          <a class="dropdown-item" href="#">2016</a>
          <a class="dropdown-item" href="#">2017</a>
          <a class="dropdown-item" href="#">2018</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- breadcrumb -->
@endsection


@section('content')
@error('product_name')
<script>
  window.onload = function() {

    swal({
      title: "اسم المنتج"
      , text: "{{ $message }}"
      , type: "error"
      , timer: 3000
    });
  }

</script>
@enderror
@error('description')
<script>
  window.onload = function() {

    swal({
      title: "وصف المنتج"
      , text: "{{ $message }}"
      , type: "error"
      , timer: 3000
    });
  }

</script>
@enderror
<div class="row">



  @if (session('success'))
  <script>
    window.onload = function() {

      swal({
        title: "product name"
        , text: "{{ session('success') }}"
        , type: "success"
        , timer: 1500
      });
    }

  </script>
  @endif

  <div class="col-xl-12">
    <div class="card mg-b-20">
      <div class="card-header pb-0">
        <div class="col-sm-6 col-md-12">
             @can('اضافة منتج')
          <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8">اضافة منتج</a>
          @endcan
        </div>


      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example1" class="table key-buttons text-md-nowrap">
            <thead>
              <tr>
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0">اسم المنتج</th>
                <th class="border-bottom-0">اسم القسم</th>
                <th class="border-bottom-0">ملاحظات</th>
                <th class="border-bottom-0">العمليات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $key => $product)
              <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->section->section_name }}</td>
                <td>
                     @can('تعديل منتج')
                  <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" data-description="{{ $product->desipcrtion }}" data-toggle="modal" href="#exampleModal{{ $product->id }}" title="تعديل"><i class="las la-pen"></i></a>

                  @endcan

                                            


                  <!-- edit -->
                  <div class="modal fade" id="exampleModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">تعديل القسم</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">

                          <form action="{{ route('products.update', $product->id) }}" method="post" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                              <label for="recipient-name" class="col-form-label">اسم
                                المنتج:</label>
                              <input class="form-control" name="product_name" id="section_name" type="text" value="{{ $product->product_name }}">
                            </div>
                            <div class="mb-3">
                              <select class="form-select form-control" name="section_id">

                                <option value="{{ $product->section->id }}">
                                  {{ $product->section->section_name }}</option>
                                @foreach ($sections as $section)
                                <option value="{{ $section->id }}">
                                  {{ $section->section_name }}</option>
                                @endforeach

                              </select>
                            </div><!-- end section_id -->
                            <div class="form-group">
                              <label for="message-text" class="col-form-label">ملاحظات:</label>
                              <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">تاكيد</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>




                  @can('حذف منتج')
                  <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-toggle="modal" href="#modaldemoexampleModal{{ $product->id }}" title="حذف"><i class="las la-trash"></i></a>
                  @endcan
                  <div class="modal" id="modaldemoexampleModal{{ $product->id }}">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                          <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form action="{{ route('products.destroy', $product->id) }}" method="post">
                          @csrf
                          @method('DELETE')
                          <div class="modal-body">
                            <h3>هل انت متاكد من عملية الحذف ؟</h3><br>
                            <p>القسم الذي سوف يحذف هو : <span style="font-size: 22px; color: red">
                                {{ $product->product_name }}</span></p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                          </div>
                      </div>
                      </form>
                    </div>
                  </div>

                </td>

              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



  <!-- Basic modal -->
  <div class="modal" id="modaldemo8">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <h6 class="modal-title">اضافة منتج</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
        <form action="{{ route('products.store') }}" method="post">

          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">اسم المنتج</label>
              <input type="text" class="form-control" name="product_name" id="exampleFormControlInput1" placeholder="اسم المنتج">

            </div><!-- end section_name -->

            <div class="mb-3">
              <select class="form-select form-control" name="section_id">

                <option>اختار القسم</option>
                @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                @endforeach

              </select>
            </div><!-- end section_id -->

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">ملاحظات</label>

              <textarea name="description" name="description" rows="5" class="form-control"></textarea>
            </div><!-- end description -->

          </div>
          <div class="modal-footer">
            <button class="btn ripple btn-success" type="submit">تاكيد</button>
            <button class="btn ripple btn-danger" data-dismiss="modal" type="button">اغلاق</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Basic modal -->


</div><!-- end row -->
@endsection



@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>
<script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>
@endsection
