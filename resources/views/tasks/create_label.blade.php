<style>
    .suggest-colors a {
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }

</style>
<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.menu.taskLabel')</h5>
    <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-table class="table-bordered" headType="thead-light">
        <x-slot name="thead">
            <th>#</th>
            <th>@lang('app.labelName')</th>
            <th>@lang('modules.sticky.colors')</th>
            <th>@lang('app.description')</th>
            @if (in_array('projects', user_modules())) <th>@lang('app.project')</th> @endif
            <th class="text-right">@lang('app.action')</th>
        </x-slot>

        @forelse($taskLabels as $key=>$item)
            <tr id="label-{{ $item->id }}">
                <td>{{ $key + 1 }}</td>
                <td data-row-id="{{ $item->id }}" data-column="label_name" contenteditable="true">
                    {!! $item->label_name !!}
                </td>
                <td data-row-id="{{ $item->id }}" data-column="label_color" contenteditable="true">
                    {!! $item->label_color !!}
                </td>
                <td data-row-id="{{ $item->id }}" data-column="description" contenteditable="true">{!! $item->description !!}
                </td>
                @if (in_array('projects', user_modules()))
                    <td data-row-id="{{ $item->id }}" data-column="project">
                        <select class="form-control select-picker change-project" name="project" id="project_id"
                        data-live-search="true" data-size="8" data-label-id="{{ $item->id }}">
                            <option value="">--</option>
                            @foreach ($projects as $project)
                                <option @selected($project->id == $item->project_id) value="{{ $project->id }}">
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <input type="hidden" name="parent_project_id" value="{{ $projectId }}">
                @endif
                <td class="quentin-table tw-flex tw-justify-end tw-gap-2 quentin-table">
                    @if (user()->permission('task_labels') == 'all')
                        <x-forms.button-secondary data-label-id="{{ $item->id }}" icon="trash" class="delete-label">
                            @lang('app.delete')
                        </x-forms.button-secondary>
                    @endif
                </td>
            </tr>
        @empty
            <x-cards.no-record-found-list colspan="5" />
        @endforelse
    </x-table>

    <x-form id="createTaskLabelForm">
        <div class="row border-top-grey ">
            <div class="col-md-6">
                <x-forms.text fieldId="label_name" :fieldLabel="__('app.label') .' '. __('app.name')"
                    fieldName="label_name" fieldRequired="true" :fieldPlaceholder="__('placeholders.label')">
                </x-forms.text>
            </div>
            <div class="col-md-6">
                <x-forms.text fieldId="label_color" :fieldLabel="__('modules.sticky.colors')" fieldName="color"
                    fieldRequired="true">
                </x-forms.text>
            </div>
            @if (in_array('projects', user_modules()))
                <div class="col-md-6">
                    <x-forms.select fieldId="project_id" :fieldLabel="__('app.project')" fieldName="project_id"
                    search="true">
                        <option value="">--</option>
                        @foreach($projects as $project)
                            <option @selected($project->id == $projectId) value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </x-forms.select>
                    <input type="hidden" name="parent_project_id" value="{{ $projectId }}">
                </div>
            @endif
            <div class="col-sm-12 col-md-12">
                <x-forms.textarea :fieldLabel="__('app.description')" fieldName="description" fieldId="description">
                </x-forms.textarea>
            </div>
            <div class="col-md-12">
                <div class="suggest-colors">
                    <a style="background-color: #0033CC" data-color="#0033CC" href="javascript:;">&nbsp;
                    </a><a style="background-color: #428BCA" data-color="#428BCA" href="javascript:;">&nbsp;
                    </a><a style="background-color: #CC0033" data-color="#CC0033" href="javascript:;">&nbsp;
                    </a><a style="background-color: #44AD8E" data-color="#44AD8E" href="javascript:;">&nbsp;
                    </a><a style="background-color: #A8D695" data-color="#A8D695" href="javascript:;">&nbsp;
                    </a><a style="background-color: #5CB85C" data-color="#5CB85C" href="javascript:;">&nbsp;
                    </a><a style="background-color: #69D100" data-color="#69D100" href="javascript:;">&nbsp;
                    </a><a style="background-color: #004E00" data-color="#004E00" href="javascript:;">&nbsp;
                    </a><a style="background-color: #34495E" data-color="#34495E" href="javascript:;">&nbsp;
                    </a><a style="background-color: #7F8C8D" data-color="#7F8C8D" href="javascript:;">&nbsp;
                    </a><a style="background-color: #A295D6" data-color="#A295D6" href="javascript:;">&nbsp;
                    </a><a style="background-color: #5843AD" data-color="#5843AD" href="javascript:;">&nbsp;
                    </a><a style="background-color: #8E44AD" data-color="#8E44AD" href="javascript:;">&nbsp;
                    </a><a style="background-color: #FFECDB" data-color="#FFECDB" href="javascript:;">&nbsp;
                    </a><a style="background-color: #AD4363" data-color="#AD4363" href="javascript:;">&nbsp;
                    </a><a style="background-color: #D10069" data-color="#D10069" href="javascript:;">&nbsp;
                    </a><a style="background-color: #FF0000" data-color="#FF0000" href="javascript:;">&nbsp;
                    </a><a style="background-color: #D9534F" data-color="#D9534F" href="javascript:;">&nbsp;
                    </a><a style="background-color: #D1D100" data-color="#D1D100" href="javascript:;">&nbsp;
                    </a><a style="background-color: #F0AD4E" data-color="#F0AD4E" href="javascript:;">&nbsp;
                    </a><a style="background-color: #AD8D43" data-color="#AD8D43" href="javascript:;">&nbsp;
                    </a>
                </div>
            </div>
            <input type="hidden" name="task_id" id="task_id" value="{{$taskId}}">
            <input type="hidden" name="project_template_task_id" id="project_template_task_id" value="{{$projectTemplateTaskId}}">
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-label" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    $('.suggest-colors a').click(function() {
        var color = $(this).data('color');
        $('#label_color').val(color);
        $('.asColorPicker-trigger span').css('background', color);
    });

    $(".select-picker").selectpicker();


    $('.delete-label').click(function() {
        var taskId = $('#task_id').val();
        var projectTemplateTaskId = $('#project_template_task_id').val();
        var id = $(this).data('label-id');
        var url = "{{ route('task-label.destroy', ':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

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
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        'taskId': taskId,
                        'projectTemplateTaskId': projectTemplateTaskId,
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#label-'+id).remove();
                            $('#task_labels').html(response.data);
                            $('#task_labels').selectpicker('refresh');
                        }
                    }
                });
            }
        });

    });

    $('#save-label').click(function() {
        var url = "{{ route('task-label.store') }}";
        $.easyAjax({
            url: url,
            container: '#createTaskLabelForm',
            type: "POST",
            data: $('#createTaskLabelForm').serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    $('#task_labels').html(response.data);
                    $(MODAL_XL).modal('hide');
                    $('#task_labels').selectpicker('refresh');
                }
            }
        })
    });

    $('[contenteditable=true]').focus(function() {
        $(this).data("initialText", $(this).html());
        let rowId = $(this).data('row-id');
    }).blur(function() {
        if ($(this).data("initialText") !== $(this).html()) {
            let id = $(this).data('row-id');
            let tableId = $(this).parent().attr('id');

            if(id){
                var url = "{{ route('task-label.update', ':id') }}";
                url = url.replace(':id', id);
                var token = "{{ csrf_token() }}";
                var projectId = "{{ $projectId }}";
                let selectedLabels = $('#task_labels').val();

                $('#'+tableId).each(function() {
                    let labelName =  $(this).find("td:nth-child(2)").html();
                    let labelColor =  $(this).find("td:nth-child(3)").html();
                    let description =  $(this).find("td:nth-child(4)").html();

                    $.easyAjax({
                        url: url,
                        container: '#row-' + id,
                        type: "POST",
                        data: {
                            'label_name': labelName,
                            'color': labelColor,
                            'description': description,
                            'parent_project_id': projectId,
                            '_token': token,
                            '_method': 'PUT'
                        },
                        blockUI: true,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#task_labels').selectpicker('refresh');
                                $('#task_labels').html(response.data);
                                $('#task_labels').val(selectedLabels);
                                $('#task_labels').selectpicker('refresh');

                            }
                        }
                    })

                });
            }

        }
    });

    $.fn.projectLabel = function(projectId, selectedLabels){
        let id = projectId;

        if (id === '') {
            id = 0;
        }
        let url = "{{ route('projects.labels', ':id') }}";
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            container: '#save-task-data-form',
            blockUI: true,
            redirect: true,
            success: function (data) {
                $('#task_labels').html(data.data);
                $('#task_labels').val(selectedLabels);
                $('#task_labels').selectpicker('refresh');
            }
        })
    }

    $('#example').on('change keyup', '#project_id',function() {
        var id = $(this).data('label-id');

        if(id){
            var url = "{{ route('task-label.update', ':id') }}";
            url = url.replace(':id', id);
            var token = "{{ csrf_token() }}";
            var projectId = $(this).val();
            var taskId = $('#task_id').val();
            var labelId = $('#label_id').val();
            var parentProjectId = "{{ $projectId }}";
            let selectedLabels = $('#task_labels').val();

            if (id != "") {
                $.easyAjax({
                    url: url,
                    container: '#row-' + id,
                    type: "POST",
                    data: {
                        'task_id': taskId,
                        'label_id': id,
                        'project_id': projectId,
                        'parent_project_id': parentProjectId,
                        '_token': token,
                        '_method': 'PUT'
                    },
                    blockUI: true,
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#task_labels').selectpicker('refresh');
                            $('#task_labels').html(response.data);
                            $('#task_labels').val(selectedLabels);
                            $('#task_labels').selectpicker('refresh');
                            $.fn.projectLabel(parentProjectId, selectedLabels);
                        }
                    }
                })
            }
        }

    });

</script>
