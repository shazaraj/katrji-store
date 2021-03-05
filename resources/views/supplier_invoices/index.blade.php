@extends("admin.admin_layout")
@section('content')

    <section id="input-style">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ فاتورة شركة موردة
                            </p>
                            <br>
                            <div class="row">

                                @if(count($errors) > 0)
                                    <div class="alert alert-danger">
                                        error <br>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li> {{ $error }} </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($message = Session::get('success'))
                                    <div class="alert alert-success alert">
                                        <button type="button" class="close" data-dismiss="alert"> X </button>
                                        <strong> {{ $message }} </strong>
                                    </div>

                                @endif

                            </div>
                            <!-- <button type="button" id="createNew"
                                    data-toggle="modal" data-target="#advertModal" class="btn gradient-purple-bliss" style="margin: 5px">إضافة</button> -->
                        </div>

                        <br>
                        <div class="row">

                            <div class="col-sm-12">
                                <table id="tableData" class="table table-striped table-sm data-table" dir="rtl">

                                    <thead>


                                    <tr>
                                        <th> #</th>
                                        <th> اسم الشركة </th>
                                        <th >المبلغ المتبقي / المدين به</th>
                                        <th >تاريخ العملية</th>
                                        <th >العمليات</th>


                                    </tr>

                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade text-left" id="advertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="modelheading"> تفاصيل فاتورة  </label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>

                    </button>
                </div>

                <form method="post" id="productForm" enctype="multipart/form-data">
                    <input type="hidden" name="_id" id="_id">
                    <div class="modal-body">
                        <label> اسم الشركة </label>
                        <input type="text" id="supplier_id" class="form-control" disabled>
                        <br/>
                        <div  >
                            <table class="table table-striped">
                                <thead >
                                <tr>
                                    <th>المادة</th>
                                    <th>الكمية </th>
                                    <th>المبلغ </th>
                                    <th>الصلاحية </th>
                                </tr>
                                </thead>
                                <tbody id="raws" ></tbody>
                            </table>
                        </div>
                        <label>  المبلغ الإجمالي </label>
                        <input type="text" id="all_price" class="form-control" disabled>
                        <br/>
                        <label> المبلغ المدفوع </label>
                        <input type="text" id="paid" class="form-control" disabled>
                        <br/>
                        <label> المبلغ المتبقي </label>
                        <input type="text" id="remain" class="form-control" disabled>
                        <br/>
                        <label> مبلغ الحسم </label>
                        <input type="text" id="discount" class="form-control" disabled>
                        <br/>
                        <label> تاريخ الفاتورة </label>
                        <input type="text" id="date" class="form-control" disabled>
                        <br/>

                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="_id" id="_id"/>
                        <input type="hidden" name="operation" id="operation"/>
{{--                        <input type="reset" class="btn bg-light-secondary" data-dismiss="modal"><i class="fa fa-close"></i>--}}
{{--                        <button type="submit" name="action" id="action" class="btn btn-success" ><i class="fa fa-print"></i>--}}
{{--                        </button>--}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="print_form_1_print" style="display: none !important;">

    </div>

@endsection


@push('pageJs')


    <script type="text/javascript">

        $(function () {


            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });


            var table = $('#tableData').DataTable({
                "language": {
                    "processing": " جاري المعالجة",
                    "paginate": {
                        "first": "الأولى",
                        "last": "الأخيرة",
                        "next": "التالية",
                        "previous": "السابقة"
                    },
                    "search": "البحث :",
                    "loadingRecords": "جاري التحميل...",
                    "emptyTable": " لا توجد بيانات",
                    "info": "من إظهار _START_ إلى _END_ من _TOTAL_ النتائج",
                    "infoEmpty": "Showing 0 إلى 0 من 0 entries",
                    "lengthMenu": "إظهار _MENU_ البيانات",
                },
                processing: true,

                serverSide: true,

                ajax: "{{ route('supplier_invoices.index')}}",

                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},

                    {data: 'supplier', name: 'supplier'},
                    {data: 'remain', name: 'remain'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]

            });


            $('#createNew').click(function () {

                $('#action').val("إضافة");

                $('#_id').val('');

                $('#productForm').trigger("reset");

                $('#modelHeading').html("  إضافة جديد  ");

            });

            $('body').on('click', '.editProduct', function () {
                var item=$(this);
                item.html("<i class='fa fa-spinner'></i>");
                var product_id = $(this).data('id');

                $.get("{{ route('supplier.sale1') }}" + '/' + product_id + '/edit', function (data) {
                    item.html("<i class='fa fa-eye'></i>");
                    $('#modelheading').html("تفاصيل فاتورة");
                    $('#advertModal').modal('show');

                    $('#_id').val(data.id);
                    $('#supplier_id').val(data.supplier);
                    $("#raws").html("");
                    if(data.row){
                        var ad = "";
                        for(var i =0; i< data.row.length;i++){
                             // alert(data.row[i].name);
                            ad +="<tr >" +
                                "<td> " + data.row[i].name +" </td>" +
                                "<td> " + data.row[i].amount +" </td>" +
                                "<td> " + data.row[i].price +" </td>" +
                                "<td> " + data.row[i].expiry +" </td>" +
                                "</tr>";
                        }
                        $("#raws").html(ad);
                    }
                    $('#paid').val(data.invoice.paid);
                    $('#all_price').val(data.invoice.all_price);
                    $('#remain').val(data.invoice.remain);
                    $('#discount').val(data.invoice.discount);

                    $('#date').val(data.invoice.created_at);
                })

            });


            $('body').on('click', '.deleteProduct', function () {
                var item=$(this);
                item.html("<i class='fa fa-spinner'></i>");

                var product_id = $(this).data("id");

                var co = confirm("  هل أنت متأكد من الحذف  !");
                if (!co) {
                    return;
                }


                $.ajax({

                    type: "DELETE",

                    url: "{{ route('supplier_invoices.store') }}" + '/' + product_id,

                    success: function (data) {
                        toastr.success('تم الحذف بنجاح');
                        item.html("<i class='fa fa-trash-o'></i>");
                        table.draw();

                    },

                    error: function (data) {
                        toastr.success('this is an error');
                        console.log('خطأ:', data);

                    }

                });

            });





        });

    </script>
@endpush
