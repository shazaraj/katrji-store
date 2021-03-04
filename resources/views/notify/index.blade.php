@extends("admin.admin_layout")
@section('content')

    <section id="input-style">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                الرئيسية/ اشعار صلاحية الاصناف الدوائية
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
                        <div class="row">
                            <div class="col-md-12 form-group pull-right">
                                <div class="nav-tabs-custom">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1-1">
                                            <div class="row">
                                                <div class="col-md-6 pull-right">
                                                    <div >
                                                        <table class="table table-striped">
                                                            <thead >
                                                            <tr>
                                                                <th>المادة </th>
                                                                <th>الكمية </th>
                                                                <th>الصلاحية </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="raws" ></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
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
            var product_id = this.value;
                $.get("{{ url('notify1') }}" , function (data) {

                    $("#raws").html("");
                    if(data.row){

                        $("#raws").html(data.row);
                    }
                    // $('#material_id').val(data.name);
                    // $('#amount').val(data.amount);
                    // $('#expiry').val(data.expiry);

                })

        });

    </script>
@endpush
