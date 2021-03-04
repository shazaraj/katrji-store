@extends("admin.admin_layout")
@section('content')

<section id="input-style">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <p>
                            الرئيسية/ التقارير
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
                <button type="button" class="close" data-dismiss="alert"> X </button><strong> {{ $message }} </strong>
            </div>
        @endif

                        </div>
                    </div>
    <div class="container">
        <!-- BEGIN : Main Content-->
        <div class="content-right">
            <div class="row col-pd-12" >
                <div class="col-md-6 pull-right text-right">
                    <div class="card pd-20 pd-sm-40 white">
                        <h4 style="text-align: right;"> التقارير اليومية </h4>
                        <div class="col ">
{{--                            <form action="" method="POST" id="date_form">--}}
                                <br>
                                <h5 style="text-align: right;"> قم بتحديد اليوم</h5>
                                <br>
                                <br>
                                <div class="col-pd-3">
                                    <input type="date" class="form-control" id="day_repo" name="day_repo">
                                </div>
                                <br>
                                <div class="col-pd-3">
                                    <a href="{{url('/getDay')}}" id="date_btn" type="submit" class="btn btn-primary form-control "> عرض النتيجة</a>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pull-right text-right">
                    <div class="card pd-20 pd-sm-40 white">
                        <h4 style="text-align: right;"> التقارير الشهرية </h4>
                        <div class="col ">
                                <br>
                                <h5 style="text-align: right;"> قم بتحديد الفترة الزمنية</h5>
                                    <div class="col-pd-3">
                                        <h6 for="">من</h6> <input type="date" name="from_date" id="from_date" class="form-control" placeholder="من" />
                                    </div>
                                    <div class="col-pd-3">
                                        <h6 for="">إلى</h6>  <input type="date" name="to_date" id="to_date" class="form-control" placeholder="إلى" />
                                    </div>
                                <br>
                                    <div class="col-pd-3">
                                        <a href="{{url('/month/report')}}" type="button" name="filter" id="filter" class="btn btn-primary form-control">عرض النتيجة</a>
                                   </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END : End Main Content-->
        <!-- BEGIN : Footer-->
        <footer class="footer undefined undefined">
        </footer>
        <!-- End : Footer--><!-- Scroll to top button -->
        <button class="btn btn-primary scroll-top" type="button"><i class="ft-arrow-up"></i></button>

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


            $('#date_btn').click(function (e) {
                e.preventDefault();
                var day_repo = $('#day_repo').val();
                // alert(day_repo);

                if(day_repo != '')
                {
                    var url ="{{ url('/getDay') }}" + '/' + day_repo;
                    window.location.href = url;
                    $(this).html('جاري توليد التقارير...');
                }
                else {
                    // toastr.success('يجب تحديد التاريخ أولا !');
                    alert('يجب تحديد التاريخ أولا !');
                }


                $.ajax({


                    dataType: 'json',

                    success: function (data) {
                        toastr.success('get date');
                        table.draw();


                    },

                    error: function (data) {

                        console.log('Error:', data);

                    }

                });

            });

            $('#filter').click(function (e) {
                e.preventDefault();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                if(from_date != '' &&  to_date != '')
                {
                    var url ="{{ url('/month/report') }}" + '/' + from_date + '/' + to_date ;
                    window.location.href = url;
                    $(this).html('جاري توليد التقارير...');

                }
                else {

                    alert('كلا التاريخين مطلوبين');
                }

                $.ajax({

                    dataType: 'json',
                    success: function (data) {
                        toastr.success('get date');
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

        });

    </script>
@endpush
