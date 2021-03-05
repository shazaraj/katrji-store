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
                        </div>
                            <div class="row">
                            <div class="col-md-12 form-group pull-right">
                                <div class="nav-tabs-custom">
                                    <div class="tab-content">
           <div class="tab-pane active" id="tab_1-1">
             <div class="row">
    <form method="post" id="productForm" style="margin:10px;" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="_id" id="_id"/>
          <div class="col-md-6 pull-right">

                          <label for="">تحديد الزبون</label>
              <label for="client_id"></label><select name="client_id" id="client_id" class="form-control select2">
                                  @if(count($client) >0 )
                                      @foreach ($client as $mt)
                                          <option value="{{$mt->id}}">{{$mt->name}}</option>
                                      @endforeach
                                  @endif
                              </select>
          </div>
          <div class="col-md-6 pull-right">
              <label for="">إجمالي الديون</label>
              <label for="remain"></label><input type="text" class="form-control" name="remain" id="remain" placeholder="" disabled>
          </div>
          <div class="col-md-6 pull-right">
              <label for="">إجمالي الفواتير</label>
              <label for="all_price"></label><input type="text" class="form-control" name="all_price" id="all_price" placeholder="" disabled >
          </div>
          <div class="col-md-6 pull-right">
              <label for="">الدفعات السابقة</label>
              <label for="paid1"></label><input type="text" class="form-control" name="paid1" id="paid1" placeholder="" disabled >
          </div>
          <div class="col-md-6 pull-right">
              <label for="">الحسومات</label>
              <label for="discount"></label><input type="text" class="form-control" name="discount" id="discount" placeholder="" disabled >
          </div>
          <br>
          <div class="col-md-6 pull-right">
              <label for="">الدفعة الحالية</label>
              <label for="paid"></label><input type="text" class="form-control" name="paid" id="paid" placeholder="" >
          </div>
          <br>
        <div style="margin: 15px; ">
                     <br>
                     <br>
                     <br>
                     <br>
                     <br>
                     <br>
          <div class="pull-right">
              <button style="margin: 15px; " type="submit" id="save_paid" class="btn btn-info"> حفظ دفعة التسديد   </button>
          </div>
          <div class="pull-left">
              <button style="margin: 15px; " type="submit" name="action" id="action" class="btn btn-success" >طباعة وصل بالدفعات</button>
          </div>
        </div>
          <br>
          <br>
          <div>
              <br>           <table class="table table-striped">
                                        <thead >
                                        <tr>
                                            <th style="text-align: center;">المبلغ </th>
                                            <th style="text-align: center;" >التاريخ </th>
                                        </tr>
                                        </thead>
                                        <tbody id="raws" style="text-align: center;"></tbody>
                                    </table>
                                </div>
         </form>
             </div>
           </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div id="print_form_1_print" style="display: none !important;">

        </div>
                    </div>
                </div>
            </div>
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

            $('#action').click(function (e) {

                e.preventDefault();

                $('#action').html('Printing..');

                var item_id =    $("#client_id").val();

                // alert(item_id)

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
                        $('#action').html('<button class="btn btn-success">طباعة وصل بالدفعات</button>');

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

                var client = $("#client_id").val();
                $.ajax({

                    data: $('#productForm').serialize(),

                    url: "{{ route('bills.store') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function (data) {
                        $('#save_paid').html('حفظ دفعة التسديد');
                        if(data.status === 200){
                            toastr.success('تم الحفظ بنجاح');

                            $('#productForm').trigger('reset');
                            $("#raws").html("");
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
