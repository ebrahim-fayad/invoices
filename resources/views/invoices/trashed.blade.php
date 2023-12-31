@extends('layouts.master')
@section('title')
قائمة الفواتير المحذوفة
@stop
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
  <div class="my-auto">
    <div class="d-flex">
      <h4 class="content-title mb-0 my-auto">الفواتير </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
        الفواتير المحذوفة</span>
    </div>
  </div>

</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (session('success'))
<script>
  window.onload = function() {

    swal({
      title: "Invoices"
      , text: "{{ session('success') }}"
      , type: "success"
      , timer: 1500
    });
  }

</script>
@endif


<!-- row -->
<div class="row">
  <!--div-->
  <div class="col-xl-12">
    <div class="card mg-b-20">
      <div class="card-header pb-0">


      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50' style="text-align: center">
            <thead>
              <tr>
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0">رقم الفاتورة</th>
                <th class="border-bottom-0">تاريخ القاتورة</th>
                <th class="border-bottom-0">تاريخ الاستحقاق</th>
                <th class="border-bottom-0">المنتج</th>
                <th class="border-bottom-0">القسم</th>
                <th class="border-bottom-0">الخصم</th>
                <th class="border-bottom-0">نسبة الضريبة</th>
                <th class="border-bottom-0">قيمة الضريبة</th>
                <th class="border-bottom-0">الاجمالي</th>
                <th class="border-bottom-0">الحالة</th>
                <th class="border-bottom-0">ملاحظات</th>
                <th class="border-bottom-0">العمليات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($invoices as $key => $invoice)
              <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $invoice->invoice_number }} </td>
                <td>{{ $invoice->invoice_Date }}</td>
                <td>{{ $invoice->Due_date }}</td>
                <td>{{ $invoice->product }}</td>
                <td>
                  <a href="{{ route('invoicesDetails.show', $invoice->id) }}">{{ $invoice->section->section_name }}</a>
                </td>
                <td>{{ $invoice->Discount }}</td>
                <td>{{ $invoice->Rate_VAT }}</td>
                <td>{{ $invoice->Value_VAT }}</td>
                <td>{{ $invoice->Total }}</td>
                <td>
                  @if ($invoice->Value_Status == 1)
                  <span class="text-success">{{ $invoice->Status }}</span>
                  @elseif($invoice->Value_Status == 2)
                  <span class="text-danger">{{ $invoice->Status }}</span>
                  @else
                  <span class="text-warning">{{ $invoice->Status }}</span>
                  @endif

                </td>
                <td>
                  <div class="dropdown">
                    <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                    <div class="dropdown-menu tx-13">
                      {{-- @can('تعديل الفاتورة')  --}}
                      <a class="dropdown-item" href="#restore_invoice{{ $invoice->id }}" data-toggle="modal">استرداد
                        الفاتورة</a>
                      {{-- @endcan  --}}

                      {{-- @can('حذف الفاتورة')  --}}
                      <a class="dropdown-item" href="#delete_invoice{{ $invoice->id }}" data-toggle="modal"><i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                        الفاتورة</a>
                      {{-- @endcan  --}}

                      {{-- @can('تغير حالة الدفع')  --}}
                      {{-- <a class="dropdown-item" href="{{ URL::route('Status_show', [$invoice->id]) }}"><i class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                      حالة
                      الدفع</a> --}}
                      {{-- @endcan  --}}

                      {{-- @can('ارشفة الفاتورة')  --}}
                      {{-- <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}" data-toggle="modal" data-target="#Transfer_invoice"><i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                      الارشيف</a> --}}
                      {{-- @endcan  --}}

                      {{-- @can('طباعةالفاتورة')  --}}
                      {{-- <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}"><i class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                      الفاتورة
                      </a> --}}
                      {{-- @endcan  --}}
                    </div>
                  </div>

                </td>
                <!-- حذف الفاتورة -->
                <div class="modal fade" id="delete_invoice{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{ route('invoices.hardDeleteInvoices', $invoice->id) }}" method="post">
                          @method('DELETE')
                          @csrf
                      </div>
                      <div class="modal-body">
                        هل انت متاكد من عملية حذف نهائيا ؟
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- استرداد الفاتورة -->
                <div class="modal fade" id="restore_invoice{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{ route('invoices.restoreInvoices', $invoice->id) }}" method="get">
                          @csrf
                      </div>
                      <div class="modal-body">
                        هل انت متاكد من عملية الاسترداد لهذه الفاتورة ؟
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                <td>{{ $invoice->note }}</td>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--/div-->
</div>


<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
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
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>

<script>
  $('#delete_invoice').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var invoice_id = button.data('invoice_id')
    var modal = $(this)
    modal.find('.modal-body #invoice_id').val(invoice_id);
  })

</script>

<script>
  $('#Transfer_invoice').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var invoice_id = button.data('invoice_id')
    var modal = $(this)
    modal.find('.modal-body #invoice_id').val(invoice_id);
  })

</script>







@endsection
