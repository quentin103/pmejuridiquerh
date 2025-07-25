@php
$addLeavePermission = user()->permission('add_leave');
@endphp

<!-- ROW START -->
<div class="row py-0 py-md-0 py-lg-3">
    <div class="col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <form action="" id="filter-form">
            <div class="d-block d-lg-flex d-md-flex my-3">
                <!-- STATUS START -->
                <div class="select-box py-2 px-0 mr-3">
                    <x-forms.label :fieldLabel="__('app.year')" fieldId="leave_year" />
                    <select class="form-control select-picker" name="leave_year" id="leave_year">
                        @for ($i = now(global_setting()->timezone)->subYears(2)->year; $i <= now(global_setting()->timezone)->addYear()->year; $i++)
                            <option {{ now(global_setting()->timezone)->year == $i ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <!-- STATUS END -->
                <!-- STATUS START -->
                <div class="select-box py-2 px-0 mr-3">
                    <x-forms.label :fieldLabel="__('modules.leaves.leaveType')" fieldId="leave_type" />
                    <select class="form-control select-picker" name="leave_type" id="leave_type" data-live-search="true"
                        data-size="8">
                        <option value="all">@lang('app.all')</option>
                        @foreach ($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ ucwords($leaveType->type_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- STATUS END -->
                <!-- STATUS START -->
                <div class="select-box py-2 px-0 mr-3">
                    <x-forms.label :fieldLabel="__('app.status')" fieldId="status" />
                    <select class="form-control select-picker" name="status" id="status" data-live-search="true"
                        data-size="8">
                        <option value="all">@lang('app.all')</option>
                        <option value="approved">@lang('app.approved')</option>
                        <option value="pending">@lang('app.pending')</option>
                        <option value="rejected">@lang('app.rejected')</option>
                    </select>
                </div>
                <!-- STATUS END -->

                <!-- SEARCH BY TASK START -->
                <div class="select-box py-2 px-lg-2 px-md-2 px-0 mr-3">
                    <x-forms.label fieldId="status" class="d-none d-lg-block d-md-block" />
                    <div class="input-group bg-grey rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-additional-grey">
                                <i class="fa fa-search f-13 text-dark-grey"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control f-14 p-1 height-35 border" id="search-text-field"
                            placeholder="@lang('app.startTyping')">
                    </div>
                </div>
                <!-- SEARCH BY TASK END -->

                <!-- RESET START -->
                <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 mt-0 mt-lg-4 mt-md-4">

                    <x-forms.button-secondary class="btn-xs d-none height-35" id="reset-filters" icon="times-circle">
                        @lang('app.clearFilters')
                    </x-forms.button-secondary>
                </div>
                <!-- RESET END -->
            </div>
        </form>

        <!-- Add Task Export Buttons Start -->
        <div class="d-flex justify-content-between action-bar">
            <div id="table-actions" class="align-items-center">
                @if ($addLeavePermission == 'all' || ($addLeavePermission == 'added' && user()->id == $employee->id))
                    <x-forms.link-primary :link="route('leaves.create').'?default_assign='.$employee->id"
                        class="mr-3 openRightModal float-left" data-redirect-url="{{ url()->full() }}" icon="plus">
                        @lang('modules.leaves.addLeave')
                    </x-forms.link-primary>
                @endif
            </div>

            <x-datatable.actions>
                <div class="select-status mr-3 pl-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <option value="change-leave-status">@lang('app.change') @lang('app.leaveStatus')</option>
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
                <div class="select-status mr-3 d-none quick-action-field" id="change-status-action">
                    <select name="status" class="form-control select-picker">
                        <option value="approved">@lang('app.approved')</option>
                        <option value="pending">@lang('app.pending')</option>
                        <option value="rejected">@lang('app.rejected')</option>
                    </select>
                </div>
            </x-datatable.actions>

        </div>

        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
</div>

@include('sections.datatable_js')

<script>
    $('#leaves-table').on('preXhr.dt', function(e, settings, data) {

        var employeeId = "{{ $employee->id }}";
        var leaveTypeId = $('#leave_type').val();
        var status = $('#status').val();
        var leaveYear = $('#leave_year').val();
        var searchText = $('#search-text-field').val();

        data['searchText'] = searchText;
        data['employeeId'] = employeeId;
        data['leaveTypeId'] = leaveTypeId;
        data['leave_year'] = leaveYear;
        data['status'] = status;

    });

    const showTable = () => {
        window.LaravelDataTables["leaves-table"].draw();
    }

    $('#search-text-field, #leave_type, #status, #leave_year').on('change keyup',
        function() {
            if ($('#leave_type').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#leave_year').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#status').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#search-text-field').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else {
                $('#reset-filters').addClass('d-none');
                showTable();
            }
        });

    $('#reset-filters').click(function() {
        $('#filter-form')[0].reset();

        $('.filter-box #status').val('not finished');
        $('.filter-box .select-picker').selectpicker("refresh");
        $('#reset-filters').addClass('d-none');
        showTable();
    });

    $('#quick-action-type').change(function() {
        const actionValue = $(this).val();

        if (actionValue != '') {
            $('#quick-action-apply').removeAttr('disabled');

            if (actionValue == 'change-leave-status') {
                $('.quick-action-field').addClass('d-none');
                $('#change-status-action').removeClass('d-none');
            } else {
                $('.quick-action-field').addClass('d-none');
            }
        } else {
            $('#quick-action-apply').attr('disabled', true);
            $('.quick-action-field').addClass('d-none');
        }
    });

    $('#quick-action-apply').click(function() {
        const actionValue = $('#quick-action-type').val();
        if (actionValue == 'delete') {
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    applyQuickAction();
                }
            });

        } else {
            applyQuickAction();
        }
    });

    $('body').on('click', '.delete-table-row', function() {
        var id = $(this).data('leave-id');
        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                confirmButton: 'btn btn-primary mr-3',
                cancelButton: 'btn btn-secondary'
            },
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('leaves.destroy', ':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
                    data: {
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            showTable();
                        }
                    }
                });
            }
        });
    });

    const applyQuickAction = () => {
        var rowdIds = $("#leaves-table input:checkbox:checked").map(function() {
            return $(this).val();
        }).get();

        var url = "{{ route('leaves.apply_quick_action') }}?row_ids=" + rowdIds;

        $.easyAjax({
            url: url,
            container: '#quick-action-form',
            type: "POST",
            disableButton: true,
            buttonSelector: "#quick-action-apply",
            data: $('#quick-action-form').serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    showTable();
                    resetActionButtons();
                }
            }
        })
    };

    $('body').on('click', '.show-leave', function() {
        var leaveId = $(this).data('leave-id');

        var url = '{{ route('leaves.show', ':id') }}';
        url = url.replace(':id', leaveId);

        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });

    $('body').on('click', '.leave-action', function() {
        var action = $(this).data('leave-action');
        var leaveId = $(this).data('leave-id');
        var url = '{{ route('leaves.leave_action') }}';

        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.changeLeaveStatusConfirmation')",
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('messages.confirm')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                confirmButton: 'btn btn-primary mr-3',
                cancelButton: 'btn btn-secondary'
            },
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
                    data: {
                        'action': action,
                        'leaveId': leaveId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            window.LaravelDataTables["leaves-table"].draw();
                        }
                    }
                });
            }
        });

    });

    $('body').on('click', '.leave-action-reject', function() {
        let action = $(this).data('leave-action');
        let leaveId = $(this).data('leave-id');
        let searchQuery = "?leave_action=" + action + "&leave_id=" + leaveId;
        let url = "{{ route('leaves.show_reject_modal') }}" + searchQuery;

        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });
</script>
