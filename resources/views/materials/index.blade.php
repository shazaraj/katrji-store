@extends("admin.admin_layout")
@section('content')

    <section id="input-style">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ الأصناف الدوائية
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
                            <button type="button" id="createNew"
                                    data-toggle="modal" data-target="#advertModal" class="btn gradient-purple-bliss" style="margin: 5px">إضافة</button>
                        </div>

                        <br>
                        <div class="row">

                            <div class="col-sm-12">
                                <table id="tableData" class="table table-striped table-sm data-table" dir="rtl">

                                    <thead>


                                    <tr>
                                        <th> #</th>
                                        <th> اسم الصنف </th>
                                        <th> الشركة الموردة </th>
                                        <th> مدة الصاحية </th>
                                        <th>السعر الإجمالي </th>
                                        <th> نسبة الربح </th>
                                        <th>  الكمية </th>
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
                    <label class="modal-title text-text-bold-600" id="modelheading"> إضافة صنف  </label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>

                    </button>
                </div>

                <form method="post" id="productForm" enctype="multipart/form-data">
                    <input type="hidden" name="_id" id="_id">
                    <div class="modal-body">
                        <div class="row">
                            @csrf

                            <div class="col-md-12"> <label> اسم الصنف </label>
                                <div class="form-group">
                                    <input type="text" name="name" id="name" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12"> <label>  مدة الصاحية </label>
                                <div class="form-group">
                                    <input type="date" name="expiry" id="expiry" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12"> <label> الشركة الموردة </label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    @if(count($supplier_id) > 0)
                                        @foreach($supplier_id as $sup)
                                            <option value="{{$sup->id}}">{{$sup->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12"> <label> السعر الإجمالي </label>
                                <div class="form-group">
                                    <input type="number" name="total_price" id="total_price" placeholder="" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12"> <label>نسبة الربح </label>
                                <div class="form-group">
                                    <input type="number" name="gain" id="gain" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12"> <label>   الكمية </label>
                                <div class="form-group">
                                    <input type="number" name="amount" id="amount" placeholder="" class="form-control">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">

                        <input type="hidden" name="operation" id="operation"/>
                        <input type="reset" class="btn bg-light-secondary" data-dismiss="modal" value="إغلاق">
                        <input type="submit" name="action" id="action" class="btn btn-primary" value="حفظ">
                    </div>
                </form>
            </div>
        </div>
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

                ajax: "{{ route('materials.index')}}",

                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},

                    {data: 'name', name: 'name'},
                    {data: 'supplier_id', name: 'supplier_id'},
                    {data: 'expiry', name: 'expiry'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'gain', name: 'gain'},
                    {data: 'amount', name: 'amount'},
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
                var product_id = $(this).data("id");


                $.get("{{ route('materials.index') }}" + '/' + product_id + '/edit', function (data) {
                    item.html("<i class='fa fa-edit'></i>");
                    $('#modelheading').html("تعديل بيانات المادة");

                    $("#action").html("تعديل");
                    $("#action").val("تعديل");
                    $('#advertModal').modal('show');

                    $('#_id').val(data.id);

                    $('#name').val(data.name);
                    $('#supplier_id').val(data.supplier_id);
                    $('#expiry').val(data.expiry);
                    $('#total_price').val(data.total_price);
                    $('#gain').val(data.gain);
                    $('#amount').val(data.amount);

                })

            });


            $('#action').click(function (e) {

                e.preventDefault();

                $('#action').html('Sending..');


                $.ajax({

                    data: $('#productForm').serialize(),

                    url: "{{ route('materials.store') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function (data) {
                        $('#action').html('إضافة');


                        if(data.status == 200){
                            toastr.success('تم الحفظ بنجاح');
                            $('#productForm').trigger("reset");
                            $('#advertModal').modal("hide");
                            $(".modal-backdrop").hide();
                            table.draw();
                        }
                        else {
                            toastr.warning(data.success);

                        }


                    },

                    error: function (data) {

                        console.log('Error:', data);

                        $('#action').html('إضافة');

                    }

                });

            });
            $('#submit').click(function (e) {

                e.preventDefault();

                $(this).html('saving..');


                $.ajax({

                    data: $('#productEditForm').serialize(),

                    url: "{{ route('materials.store') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function (data) {
                        $('#action').html('   حفظ التعديلات &nbsp; <i class="fa fa-save"></i> ');


                        $('#productEditForm').trigger("reset");
                        $('#ajaxModel').modal('hide');

                        table.draw();

                        toastr.success("تم التعديل بنجاح");

                    },

                    error: function (data) {

                        console.log('Error:', data);
                        $('#ajaxModel').modal('hide');

                        $('#editBtn').html('Save changes &nbsp; <i class="fa fa-save"></i> ');

                    }

                });

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

                    url: "{{ route('materials.store') }}" + '/' + product_id,

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
