@extends('layouts.app')

@push('datatable-styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    @include('sections.datatable_css')
@endpush

@section('filter-section')

    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <div class="select-status d-flex">
                <input type="text"
                    class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                    id="datatableRange2" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->

        <!-- EMPLOYEE START -->
        <div class="select-box d-flex  py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.employee')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="employee" id="employee" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                    @foreach ($employees as $employee)
                        <x-user-option :user="$employee" />
                    @endforeach
                </select>
            </div>
        </div>
        <!-- EMPLOYEE END -->

        <!-- PROJECT START -->
        <div class="select-box d-flex  py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.project')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="project_id" id="project_id" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- PROJECT END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>

        </div>

        <!-- RESET END -->

        <!-- MORE FILTERS START -->
        <x-filters.more-filter-box>

            <!-- CLIENT START -->
            @if (in_array('clients', user_modules()))
                <div class="more-filter-items">
                    <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.client')</label>
                    <div class="select-filter mb-4">
                        <div class="select-others">
                            <select class="form-control select-picker" name="client" id="client" data-live-search="true"
                                data-container="body" data-size="8">
                                <option value="all">@lang('app.all')</option>
                                @foreach ($clients as $client)
                                    <x-user-option :user="$client" />
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- CLIENT END -->
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="status" id="status" data-live-search="true"
                            data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            <option value="1">@lang('app.approved')</option>
                            <option value="0">@lang('app.pending')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.invoiceGenerate')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="invoice_generate" id="invoice_generate"
                            data-container="body" data-live-search="true" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            <option value="1">@lang('app.yes')</option>
                            <option value="0">@lang('app.no')</option>
                        </select>
                    </div>
                </div>
            </div>

        </x-filters.more-filter-box>
        <!-- MORE FILTERS END -->
    </x-filters.filter-box>


@endsection

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-flex flex-column">
            <!-- TASK STATUS START -->
            <x-cards.data id="task-chart-card" :title="__($pageTitle)" padding="false">
            </x-cards.data>
            <!-- TASK STATUS END -->
            <div class="d-grid d-lg-flex d-md-flex action-bar align-items-center mt-4">
            <div id="table-actions" class="flex-grow-1 align-items-center">
            </div>

                <div class="btn-group mt-2 mt-lg-0 mt-md-0 ml-0 ml-lg-3 ml-md-3" role="group">
                    <a href="{{ route('time-log-report.index') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14 btn-active" data-toggle="tooltip"
                        data-original-title="@lang('app.menu.timeLogReport')"><i class="side-icon bi bi-list-ul"></i></a>

                    <a href="{{ route('time-log-consolidated.report') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14" data-toggle="tooltip"
                        data-original-title="@lang('app.timelogConsolidatedReport')"><i class="side-icon bi bi-clipboard-data"></i></a>

                    <a href="{{ route('project-wise-timelog.report') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14" data-toggle="tooltip"
                        data-original-title="@lang('app.projectWiseTimeLogReport')"><i class="side-icon bi bi-list"></i></a>
                </div>
            </div>

        </div>

        <!-- Add Task Export Buttons End -->
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-4 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script type="text/javascript">
        function getDate() {
            var start = moment().clone().startOf('month');
            var end = moment();

            $('#datatableRange2').daterangepicker({
                locale: daterangeLocale,
                linkedCalendars: false,
                startDate: start,
                endDate: end,
                ranges: daterangeConfig
            }, cb);
        }
        $(function() {
            getDate()
            $('#datatableRange2').on('apply.daterangepicker', function(ev, picker) {
                showTable();
            });

        });
    </script>


    <script>
        $('#timelogs-table').on('preXhr.dt', function(e, settings, data) {

            var dateRangePicker = $('#datatableRange2').data('daterangepicker');
            var startDate = $('#datatableRange2').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            var projectID = $('#project_id').val();
            var employee = $('#employee').val();
            var client = $('#client').val();
            var approved = $('#status').val();
            var invoice = $('#invoice_generate').val();
            var searchText = $('#search-text-field').val();

            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['projectId'] = projectID;
            data['employee'] = employee;
            data['client'] = client;
            data['approved'] = approved;
            data['invoice'] = invoice;
            data['searchText'] = searchText;
        });
        const showTable = () => {
            window.LaravelDataTables["timelogs-table"].draw(true);
            pieChart();
        }

        $('#project_id, #employee, #client, #status, #invoice_generate').on('change keyup',
            function() {
                if ($('#status').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#employee').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#client').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#project_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#invoice_generate').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else {
                    $('#reset-filters').addClass('d-none');
                    showTable();
                }
            });

        $('#search-text-field').on('keyup', function() {
            if ($('#search-text-field').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            }
        });

        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();
            // getDate()

            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#reset-filters-2').click(function() {
            $('#filter-form')[0].reset();

            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        function pieChart() {
            var dateRangePicker2 = $('#datatableRange2').data('daterangepicker');
            var startDate = $('#datatableRange2').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker2.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker2.endDate.format('{{ company()->moment_date_format }}');
            }

            var data = new Array();
            var projectID = $('#project_id').val();
            var employee = $('#employee').val();
            var client = $('#client').val();
            var approved = $('#status').val();
            var invoice = $('#invoice_generate').val();
            var searchText = $('#search-text-field').val();

            var url = "{{ route('time-log-report.chart') }}";

            $.easyAjax({
                url: url,
                container: '#task-chart-card',
                blockUI: true,
                type: "POST",
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    projectId: projectID,
                    employee: employee,
                    client: client,
                    approved: approved,
                    invoice: invoice,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#task-chart-card').html(response.html);
                }
            });
        }
        pieChart();
    </script>
@endpush
