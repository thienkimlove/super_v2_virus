@extends('layouts.app')

@section('inline_styles')
    <style>
        .select2-container--default {
            width: 250px !important;
        }
        .select2-container--default .select2-results > .select2-results__options {
            max-height: 500px;
            min-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('styles')
    <!-- DataTables -->
    <link href="/vendor/ubold/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/vendor/ubold/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="/admin/users/create"><button type="button" class="btn btn-default dropdown-toggle waves-effect" >Tạo mới <span class="m-l-5"><i class="fa fa-plus"></i></span></button></a>
            </div>

            <h4 class="page-title">Danh sách người dùng</h4>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-inline" role="form" id="search-form">
                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Username</label>
                                <input type="text" class="form-control" placeholder="Tên người dùng" name="username"/>
                            </div>

                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Group</label>
                                {!! Form::select('group_id', ['' => '--- Chọn Group ---'] + \App\Site::groupList(), null, ['class' => 'form-control select2']) !!}
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-l-15">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <p class="text-muted font-13 m-b-30"></p>
                <table id="dataTables-users" class="table table-striped table-bordered table-actions-bar">
                    <thead>
                    <tr>
                        <th width="10%">Username</th>
                        <th width="15%">Email</th>
                        <th width="15%">Group</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Ngày tạo</th>
                        <th width="8%"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/vendor/ubold/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.bootstrap.js"></script>

    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/buttons.bootstrap.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/jszip.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/vfs_fonts.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/responsive.bootstrap.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.colVis.js"></script>
    <script src="/vendor/ubold/assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

    <script src="/vendor/ubold/assets/pages/datatables.init.js"></script>
    <script src="/vendor/ubold/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="/js/handlebars.js"></script>

    <script src="/vendor/ubold/assets/plugins/moment/moment.js"></script>
    <script src="/vendor/ubold/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
@endsection

@section('inline_scripts')
    <script type="text/javascript">
        $('.select2').select2();

        $(function () {
            var datatable = $("#dataTables-users").DataTable({
                searching: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{!! route('users.dataTables') !!}',
                    data: function (d) {
                        d.username = $('input[name=username]').val();
                        d.group_id = $('select[name=group_id]').val();
                    }
                },
                columns: [
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'group_name', name: 'group_name'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[5, 'desc']]
            });

            $('#search-form').on('submit', function(e) {
                datatable.draw();
                e.preventDefault();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection