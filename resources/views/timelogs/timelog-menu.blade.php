<a href="{{ route('timelogs.index') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14 @if($timelogMenuType == 'index') btn-active @endif" data-toggle="tooltip"
data-original-title="@lang('app.menu.timeLogs')"><i class="side-icon bi bi-list-ul"></i></a>

<a href="{{ route('weekly-timesheets.index') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14 @if($timelogMenuType == 'weekly-timesheets') btn-active @endif" data-toggle="tooltip"
data-original-title="@lang('app.menu.weeklyTimesheets')"><i class="side-icon bi bi-calendar-week"></i></a>

<a href="{{ route('timelog-calendar.index') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14 @if($timelogMenuType == 'calendar') btn-active @endif" data-toggle="tooltip"
data-original-title="@lang('app.menu.calendar')"><i class="side-icon bi bi-calendar"></i></a>

<a href="{{ route('timelogs.by_employee') }}" class="tw-bg-[#838383] tw-p-2 px-3 hover:tw-bg-[#838383]/70  hover:tw-text-white  tw-rounded-md !tw-text-white  f-14 @if($timelogMenuType == 'byEmployee') btn-active @endif" data-toggle="tooltip"
data-original-title="@lang('app.employeeTimeLogs')"><i
     class="side-icon bi bi-person"></i></a>

<a href="javascript:;" class="img-lightbox btn btn-secondary f-14"
data-image-url="{{ asset('img/timesheet-lc.png') }}" data-toggle="tooltip"
data-original-title="@lang('app.howItWorks')"><i class="side-icon bi bi-question-circle"></i></a>
