@extends("admin.admin_layout")
@section('content')

    <section id="input-style">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ وصل تسديد
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
{{--                            <button type="button" id="createNew"--}}
{{--                                    data-toggle="modal" data-target="#advertModal" class="btn gradient-purple-bliss" style="margin: 5px">إضافة</button>--}}
                        </div>
                        <form action="" method="post" id="client_sale_form" style="margin-right: 12px;">
                            @csrf

                            <div class="row">
                            <div class="col-md-12 form-group pull-right">
                                <div class="nav-tabs-custom">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1-1">
                               <div class="row">
      <form method="post" id="productForm" enctype="multipart/form-data">

                                   <div class="col-md-6 pull-right">

                          <label for="">تحديد الزبون</label>
                              <select name="client_id" id="client_id" class="form-control select2">
                                  @if(count($client) >0 )
                                      @foreach ($client as $mt)
                                          <option value="{{$mt->id}}">{{$mt->name}}</option>
                                      @endforeach
                                  @endif
                              </select>
                                </div>
                                                <div class="col-md-6 pull-right">
                                                    <label for="">إجمالي الديون</label>
                                                    <input type="text" class="form-control" name="remain" id="remain"  placeholder="" disabled>
                                                </div>
                                                <div class="col-md-6 pull-right">
                                                    <label for="">إجمالي الفواتير</label>
                                                    <input type="text" class="form-control" name="all_price" id="all_price" placeholder="" disabled >
                                                </div>
                                                <div class="col-md-6 pull-right">
                                                    <label for="">الدفعات السابقة</label>
                                                    <input type="text" class="form-control" name="paid1" id="paid1" placeholder="" disabled >
                                                </div>
                                                <div class="col-md-6 pull-right">
                                                    <label for="">الحسومات</label>
                                                    <input type="text" class="form-control" name="discount" id="discount" placeholder="" disabled >
                                                </div>
                                                <br>
                                                <br>
                                                <div class="col-md-6 pull-right">
                                                    <label for="">الدفعة الحالية</label>
                                                    <input type="text" class="form-control" name="paid" id="paid" placeholder="" >
                                                </div>


                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
<div class="pull-right">
       <button type="submit" id="save_paid" class="btn btn-info"> حفظ دفعة التسديد   </button>
</div>

<div class="pull-left">
    <input type="hidden" name="_id" id="_id"/>
   <button type="submit" name="action" id="action" class="btn btn-success" >طباعة وصل بالدفعات</button>
</div>
                                <br>
                                <br>
                                <div >
                                    <table class="table table-striped">
                                        <thead >
                                        <tr>
                                            <th>المبلغ </th>
                                            <th>التاريخ </th>
                                        </tr>
                                        </thead>
                                        <tbody id="raws" ></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                  </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div id="print_form_1_print" style="display: none !important;">

        </div>
   </section>
@endsection


@push('pageJs')


    <script type="text/javascript">

        $(function () {


            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

            $("#client_id").on('click', function() {
                var product_id = this.value;
                $.get("{{ route('bills.filter') }}" + '/' + product_id + '/edit', function (data) {

                    $('#remain').val(data.remain);
                    $('#paid1').val(data.paid);
                    $('#all_price').val(data.all_price);
                    $('#discount').val(data.discount);

                })
            });

            $("#client_id").on('click', function() {
                var product_id = this.value;
                $.get("{{ route('bills.get') }}" + '/' + product_id + '/edit', function (data) {

                    $("#raws").html("");
                    if(data.row){
                        var ad = "";
                        for(var i =0; i< data.row.length;i++){
                            // alert(data.row[i].paid);
                            ad +="<tr >" +
                                "<td> " + data.row[i].paid +" </td>" +
                                "<td> " + data.row[i].created_at +" </td>" +
                                "</tr>";
                        }
                        $("#raws").html(ad);
                    }

                })

            })


            $('#createNew').click(function () {

                $('#action').val("إضافة");

                $('#_id').val('');

                $('#productForm').trigger("reset");

                $('#modelHeading').html("  إضافة جديد  ");

            });
            $('#action').click(function (e) {

                e.preventDefault();

                $('#action').html('Printing..');

                var item = $(this);


                var item_id =   $('#_id').val();


                $.ajax({
                    url:"{{url('print_bills')}}/"+item_id,
                    method:"POST",
                    data:{
                        '_token':'{{csrf_token()}}',
                        'cart_id':item_id
                    },
                    // dataType:"json",
                    success:function(data)
                    {
                        $("#print_form_1_print").html(data);
                        printReport(data);
                        $('#action').html('<i class="fa fa-print">');


                        // reload();

                    },
                    error:function(data){
                        alert(data.responseText);
                    }
                })

            });


            $('body').on('click', '.deleteProduct', function () {
                var item = $(this);
                item.html("<i class='fa fa-spinner'></i>");

                var product_id = $(this).data("id");

                var co = confirm("  هل أنت متأكد من الحذف  !");
                if (!co) {
                    return;
                }


                $.ajax({

                    type: "DELETE",

                    url: "{{ route('bills.store') }}" + '/' + product_id,

                    success: function (data) {
                        toastr.success('تم الحذف بنجاح');
                        item.html("<i class='fa fa-trash-o'></i>");
                        table.draw();

                    },

                    error: function (data) {
                        alert(data.responseText);

                    }

                });

            });

            $('#save_paid').click(function (e) {

                e.preventDefault();
                // var item=$(this);
                // item.html("<i class='fa fa-spinner'></i>");


                $.ajax({

                    data: $('#client_sale_form').serialize(),

                    url: "{{ route('bills.store') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function (data) {
                        $('#action').html('إضافة');
                        if(data.status == 200){
                            toastr.success('تم الحفظ بنجاح');
                            // window.reload();
                            // item.html("<i>حفظ الفاتورة</i>");
                            $('#client_sale_form').trigger('reset');
                            $("#raws").html("");
                            // $('#raws').trigger('reset');
                            // $("#materials_table").trigger('reset');
                            // $("#materials_table").html("");
                            // $("#materials_table").cleanData;
                            // table.draw();
                            // $("#materials_table").append("<tr></tr>" );
                        }
                        else {
                            toastr.warning(data.err);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        toastr.warning('حدث خطأ ما!');

                        $('#save_paid').html('حفظ دفعة التسديد');
                    }
                });
            });
        });

    </script>
@endpush
