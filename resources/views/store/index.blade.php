@extends("admin.admin_layout")
@section('content')

    <section id="input-style">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ محتويات المستودع من الأصناف
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
                                <table id="tableData" class="table table-striped table-sm data-table" dir="rtl">

                                    <thead>


                                    <tr>
                                        <th> #</th>
                                        <th> اسم المادة </th>
                                        <th> الكمية </th>
                                        <th> الصلاحية </th>
                                        <th> السعر </th>
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
{{--    <div class="modal fade text-left" id="advertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <label class="modal-title text-text-bold-600" id="modelheading"> إضافة مورد  </label>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true"><i class="fa fa-close"></i></span>--}}

{{--                    </button>--}}
{{--                </div>--}}

{{--                <form method="post" id="productForm" enctype="multipart/form-data">--}}
{{--                    <input type="hidden" name="_id" id="_id">--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="row">--}}
{{--                            @csrf--}}

{{--                            <div class="col-md-12"> <label> الاسم </label>--}}
{{--                                <div class="form-group">--}}
{{--                                    <input type="text" name="name" id="name" placeholder="" class="form-control">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12"> <label> الموبايل </label>--}}
{{--                                <div class="form-group">--}}
{{--                                    <input type="number" name="mobile" id="mobile" placeholder="" class="form-control">--}}
{{--                                </div>--}}
{{--                            </div>--}}


{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}

{{--                        <input type="hidden" name="operation" id="operation"/>--}}
{{--                        <input type="reset" class="btn bg-light-secondary" data-dismiss="modal" value="إغلاق">--}}
{{--                        <input type="submit" name="action" id="action" class="btn btn-primary" value="حفظ">--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

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

                ajax: "{{ route('store.index')}}",

                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},

                    {data: 'materials', name: 'materials'},
                    {data: 'amount', name: 'amount'},
                    {data: 'expiry', name: 'expiry'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]

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

                    url: "{{ route('store.store') }}" + '/' + product_id,

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
