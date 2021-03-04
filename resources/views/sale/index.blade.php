@extends("admin.admin_layout")
@section('content')

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ المبيعات
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
                        <br>
     <div class="row">
         <div class="col-sm-12">
             <div class="row" style="margin-top: 30px;">
                 <div class="col-md-11 pull-right text-right">
                     <form action="" method="post" id="client_sale_form">
                         @csrf

                         <div class="col-md-12" style="text-align:right;direction: rtl;">
                             <div class="col-md-4 pull-right form-group">

                                 <label> اسم الزبون </label>
                                 <select name="client_id" id="client_id" class="form-control">
                                     @if(count($clients) >0 )
                                         @foreach ($clients as $cl)
                                             <option value="{{$cl->id}}">{{$cl->name}}</option>
                                         @endforeach
                                     @endif
                                 </select>
                                 <br>
                                 <br>
                                 <br>
                                 <br>
                                 <label>  إجمالي الفاتورة    </label>
                                 <input type="text" name="all_price" id="all_price" class="form-control">
{{--                                 <br>--}}
                                 <label> واصل :</label>
                                 <input type="text" name="paid" id="paid" class="form-control">
                                 <label> حسم من قبلنا :</label>
                                 <input type="text" name="discount" id="discount" class="form-control" value="0">

                             </div>
                         </div>
          <div class="row">
              <div class="col-md-12 form-group pull-right">
                  <div class="nav-tabs-custom">
                      <div class="tab-content">
                          <div class="tab-pane active" id="tab_1-1">
                              <div class="row">
                                  <div class="col-md-4 pull-right">
                                      <label for="">تحديد مادة</label>
                                      <select name="material_id" id="material_id" class="form-control select2">
                                          @if(count($materials) >0 )
                                              @foreach ($materials as $mt)
                                                  <option value="{{$mt->id}}">{{$mt->name}}</option>
                                              @endforeach
                                          @endif
                                      </select>
                                  </div>
                                  <div class="col-md-4 pull-right">
                                      <label for="">الكمية</label>
                                      <input type="text" class="form-control" name="amount" id="amount"  placeholder="">
                                  </div>
                                  <div class="col-md-4 pull-right">
                                      <label for="">السعر</label>
                                      <input type="text" class="form-control" name="price" id="price" placeholder="" >
                                  </div>
                                     <div class="col-md-4" align="right" style="padding:15px;">
                                         <label for=""></label>
                                         <button  type="button" class="btn btn-success" id="add_new_material"><i class="fa fa-cart-plus"></i></button>
                                     </div>
                                 </div>
                                 <div class="row">
                                     <table class="table tab-info" >
                                         <tr>
                                             <th>المادة</th>
                                             <th>الكمية </th>
                                             <th>السعر الاجمالي</th>
                                         </tr>
                                         <tbody id="materials_table" > </tbody>
                                     </table>

                                 </div>
                             </div>
                         </div>
                     </div>
                  <button type="submit" id="save_paid" class="btn btn-info"> حفظ الفاتورة   </button>

              </div>
             </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>

@endsection


@push('pageJs')


    <script type="text/javascript">

        $("#material_id").on('click', function() {
            var product_id = this.value;
            $.get("{{ route('materials.index') }}" + '/' + product_id + '/edit', function (data) {

                $('#amount').val(data.amount);
                $('#price').val(data.total_price);

            })
        });
        $(function () {

            $("#add_new_material").click(function (e) {
                e.preventDefault();
                var name = $("#material_id option:selected").text();
                var material_id = $("#material_id").val();
                var quantity = $("#amount").val();
                var price = $("#price").val();
                var mt_price = price*quantity;
                // $("#all_price").val(amount*price);
                var all_price = Number( $("#all_price").val() );
                all_price += Number( mt_price );
                $("#all_price").val(all_price);
                // $("#paid").val(paid);
                var all_price = $("#all_price").val();

                $("#materials_table").append("<tr>" +
                    "<td><input type='hidden' name='raws_id[]' value='"+material_id+"'>"+name+"</td>" +
                    "<td><input type='hidden' name='raws_amount[]' value='"+quantity+"'> "+quantity+"</td>" +
                    "<td><input type='hidden' name='raws_price[]' value='"+mt_price+"'> "+mt_price+"</td>" +

                    "</tr>")

            })


            $('input[type=radio][name=client_selection]').change(function() {
                if (this.value == 'oldRadio') {
                    $("#client_id").show();
                    $("#name").hide();

                }

                else if (this.value == 'newRadio') {
                    $("#name").show();
                    $("#client_id").hide();
                }
            });

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

            $('#save_paid').click(function (e) {

                e.preventDefault();
                // var item=$(this);
                // item.html("<i class='fa fa-spinner'></i>");


                $.ajax({

                    data: $('#client_sale_form').serialize(),

                    url: "{{ route('client_invoices.store') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function (data) {
                        $('#action').html('إضافة');
                        if(data.status == 200){
                            toastr.success('تم الحفظ بنجاح');
                            // window.reload();
                            // item.html("<i>حفظ الفاتورة</i>");
                            $('#client_sale_form').trigger('reset');
                            // $("#materials_table").trigger('reset');
                            $("#materials_table").html("");
                            // $("#materials_table").cleanData;
                            // table.draw();
                            // $("#materials_table").append("<tr></tr>" );
                        }
                        else {
                            toastr.warning(data.success);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        toastr.err('error');

                        $('#action').html('إضافة');
                    }
                });
            });
            // $("#materials_table").show();
        });

    </script>
@endpush
