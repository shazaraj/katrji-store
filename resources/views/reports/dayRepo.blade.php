@extends("admin.admin_layout")
@section('content')

<section id="input-style">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-header">
                        <p>
                            الرئيسية/ التقارير اليومية
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
                    </div>
<div class="card-body">
            <section class="content" style="text-align: right;margin-right: 25px;">
                <h4>
                    <b> تقرير اليوم المحدد :_ {{$date??''}} </b>
                </h4>
                <br>
                <div class="col" dir="rtl">
                    <div class="col-12">
                        <div class="row rtl" >
                            <div class="col">
                                <div class="info-box">
                                    <span class="info-box-icon bg-blue-gradient"><i class="fa fa-dollar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المقبوضات</span>
                                        <h3 class="info-box-number"> {{$sales??''}}</h3>
                                    </div>
                                </div>

                                <div class="info-box">
                                    <span class="info-box-icon bg-green-gradient"><i class="fa fa-btc"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المدفوعات</span>
                                        <h3 class="info-box-number">{{$payments??''}}</h3>
                                    </div>
                                </div>
                                <div class="info-box">
                                    <span class="info-box-icon bg-blue-active"><i class="fa fa-puzzle-piece"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">مجموع المواد في المستودع </span>
                                        <h3 class="info-box-number">{{$medicines??''}}</h3>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col">

                                <div class="info-box">
                                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-in"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">دائن _ على الزبون</span>
                                        <h3 class="info-box-number">{{$remain_clients??''}}</h3>
                                    </div>
                                </div>


                                <div class="info-box">
                                    <span class="info-box-icon bg-red-gradient"><i class="fa fa-sign-out"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المدين _ إلى الشركة</span>
                                        <h3 class="info-box-number">{{$remain_suppliers??''}}</h3>
                                    </div>
                                </div>
                                <div class="info-box">
                                    <span class="info-box-icon bg-red-gradient"><i class="fa fa-yen"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">إجمالي الأرباح </span>
                                        <h3 class="info-box-number">{{$remain??''}}</h3>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


            </section>
</div>
                <br>
                <br>
                <br>
                <br>
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

            $.get("{{ url('getDay') }}" , function (data) {
                //
                // $('#sales').val(sales);
                // $('#payments').val(payments);
                // $('#remain_clients').val(remain_clients);
                // $('#remain_suppliers').val(remain_suppliers);
                // $('#medicines').val(medicines);
                // $('#date').val(date);



            })


        });

    </script>
@endpush
