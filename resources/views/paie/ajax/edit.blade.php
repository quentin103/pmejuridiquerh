@php
$addDesignationPermission = user()->permission('add_designation');
$addDepartmentPermission = user()->permission('add_department');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/tagify.css') }}">
<style>
    .tagify_tags .height-35 {
        height: auto !important;
    }

</style>

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.accountDetails')</h4>
                @include('sections.password-autocomplete-hide')
                <div class="row p-20">
                    <div class="col-lg-9 col-xl-10">
                      <div class="row">
                        <div class="col-lg-3 col-md-6">
                          <x-forms.text fieldId="employee_id" :fieldLabel="__('modules.employees.employeeId')"
                              fieldName="employee_id" :fieldValue="$employee->employeeDetail->employee_id"
                              fieldRequired="true" :fieldPlaceholder="__('modules.employees.employeeIdInfo')">
                          </x-forms.text>
                        </div>
                        <div class="col-lg-3 col-md-6">
                          <x-forms.text fieldId="name" :fieldLabel="__('modules.employees.employeeName')"
                              fieldName="name" :fieldValue="$employee->name" fieldRequired="true"
                              :fieldPlaceholder="__('placeholders.name')">
                          </x-forms.text>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <x-forms.text fieldId="lastname" :fieldLabel="__('modules.employees.lastname')" :fieldValue="$employee->lastname" fieldName="lastname" fieldRequired="true" :fieldPlaceholder="__('placeholders.lastname')">
                            </x-forms.text>
                        </div>
                        <div class="col-lg-3 col-md-6">
                          <x-forms.datepicker fieldId="date_of_birth" :fieldLabel="__('modules.employees.dateOfBirth')"
                              fieldName="date_of_birth" :fieldPlaceholder="__('placeholders.date')" :fieldValue="($employee->employeeDetail->date_of_birth ? $employee->employeeDetail->date_of_birth->format(global_setting()->date_format) : '')" />
                        </div>
                        <div class="col-lg-3 col-md-6">
                          <x-forms.text fieldId="birth_place" :fieldLabel="__('modules.employees.birth_place')" :fieldValue="$employee->employeeDetail->birth_place" fieldName="birth_place" fieldRequired="true" :fieldPlaceholder="__('placeholders.birth_place')">
                          </x-forms.text>
                        </div>
                        <div class="col-lg-3 col-md-6">
                          <x-forms.select fieldId="country" :fieldLabel="__('app.country')" fieldName="country" search="true">
                            <option value="">--</option>
                            @foreach ($countries as $item)
                              <option @if ($employee->country_id == $item->id) selected @endif data-tokens="{{ $item->iso3 }}" data-content="<span
                              class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span>
                            {{ $item->nicename }}" value="{{ $item->id }}">{{ $item->nicename }}</option>
                            @endforeach
                          </x-forms.select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                          <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')" fieldName="gender">
                            <option value="">--</option>
                            <option @if ($employee->gender == 'male') selected @endif value="male">@lang('app.male')</option>
                            <option @if ($employee->gender == 'female') selected @endif value="female">@lang('app.female')</option>
                          </x-forms.select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                          <x-forms.select fieldId="marital_status" :fieldLabel="__('modules.employees.marital_status')"
                              fieldName="marital_status">
                            <option value="">--</option>
                            <option @if ($employee->employeeDetail->marital_status == 'Célibataire') selected @endif value="Célibataire">@lang('app.Single')</option>
                            <option @if ($employee->employeeDetail->marital_status == 'Marié') selected @endif value="Marié">@lang('app.Married')</option>
                            <option @if ($employee->employeeDetail->marital_status == 'Divorcé') selected @endif value="Divorcé">@lang('app.Divorced')</option>
                            <option @if ($employee->employeeDetail->marital_status == 'Veuf(ve)') selected @endif value="Veuf(ve)">@lang('app.Widowed')</option>
                          </x-forms.select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                          <x-forms.number fieldId="children_number" :fieldLabel="__('modules.employees.children_number')" step=".01" min="0" :fieldValue="$employee->employeeDetail->children_number" 
                              fieldName="children_number" fieldRequired="false" :fieldPlaceholder="__('placeholders.children_number')">
                          </x-forms.number>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-xl-2">
                      @php
                          $userImage = $employee->hasGravatar($employee->email) ? str_replace('?s=200&d=mp', '', $employee->image_url) : asset('img/avatar.png');
                      @endphp
                      <x-forms.file allowedFileExtensions="png jpg jpeg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                          :fieldLabel="__('modules.profile.profilePicture')"
                          :fieldValue="($employee->image ? $employee->image_url : $userImage)" fieldName="image"
                          fieldId="image" fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                      <x-forms.select fieldId="type_ID" :fieldLabel="__('modules.employees.type_ID')"
                          fieldName="type_ID">
                        <option value="">--</option>
                        <option @if ($employee->employeeDetail->type_ID == 'CNI') selected @endif value="CNI">CNI</option>
                        <option @if ($employee->employeeDetail->type_ID == "Attestation d'identité") selected @endif value="Attestation d'identité">Attestation d'identité</option>
                        <option @if ($employee->employeeDetail->type_ID == 'Passeport') selected @endif value="Passeport">Passeport</option>
                        <option @if ($employee->employeeDetail->type_ID == 'Autre') selected @endif value="Autre">Autre</option>
                      </x-forms.select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.text fieldId="num_ID" :fieldLabel="__('modules.employees.num_ID')" :fieldValue="$employee->employeeDetail->num_ID"
                          fieldName="num_ID" fieldRequired="true" :fieldPlaceholder="__('placeholders.num_ID')">
                      </x-forms.text>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.text fieldId="email" :fieldLabel="__('modules.employees.employeeEmail')"
                          fieldName="email" fieldRequired="true" :fieldValue="$employee->email"
                          :fieldPlaceholder="__('placeholders.email')">
                      </x-forms.text>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.label class="mt-3" fieldId="password"
                      :fieldLabel="__('app.password')">
                      </x-forms.label>
                      <x-forms.input-group>
                          <input type="password" name="password" id="password" autocomplete="off"
                              class="form-control height-35 f-14">
                          <x-slot name="preappend">
                              <button type="button" data-toggle="tooltip"
                                  data-original-title="@lang('app.viewPassword')"
                                  class="btn btn-outline-secondary border-grey height-35 toggle-password"><i
                                      class="fa fa-eye"></i></button>
                          </x-slot>
                          <x-slot name="append">
                              <button id="random_password" type="button" data-toggle="tooltip"
                                  data-original-title="@lang('modules.client.generateRandomPassword')"
                                  class="btn btn-outline-secondary border-grey height-35"><i
                                      class="fa fa-random"></i></button>
                          </x-slot>
                      </x-forms.input-group>
                      <small class="form-text text-muted">@lang('modules.client.passwordUpdateNote')</small>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.tel fieldId="mobile" :fieldLabel="__('app.mobile')" :fieldValue="$employee->mobile" fieldName="mobile"
                      fieldPlaceholder="EX:0701010101"></x-forms.tel>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.tel fieldId="other_mobile" :fieldLabel="__('app.other_mobile')" :fieldValue="$employee->other_mobile" fieldName="other_mobile"
                      fieldPlaceholder="EX:0102030405"></x-forms.tel>
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <x-forms.label class="my-3" fieldId="designation"
                        :fieldLabel="__('app.designation')" fieldRequired="true">
                      </x-forms.label>
                      <x-forms.input-group>
                        <select class="form-control select-picker" name="designation"
                          id="employee_designation" data-live-search="true">
                          <option value="">--</option>
                          @foreach ($designations as $designation)
                              <option @if ($employee->employeeDetail->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">
                                  {{ $designation->name }}</option>
                          @endforeach
                        </select>
                        @if ($addDesignationPermission == 'all' || $addDesignationPermission == 'added')
                            <x-slot name="append">
                              <button id="designation-setting-edit" type="button"
                              class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                            </x-slot>
                        @endif
                      </x-forms.input-group>
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <x-forms.label class="my-3" fieldId="department"
                        :fieldLabel="__('app.department')" fieldRequired="true">
                      </x-forms.label>
                      <x-forms.input-group>
                        <select class="form-control select-picker" name="department"
                          id="employee_department" data-live-search="true">
                          <option value="">--</option>
                          @foreach ($teams as $team)
                            <option @if ($employee->employeeDetail->department_id == $team->id) selected @endif value="{{ $team->id }}">
                            {{ $team->team_name }}</option>
                          @endforeach
                        </select>
                        @if ($addDepartmentPermission == 'all' || $addDepartmentPermission == 'added')
                          <x-slot name="append">
                            <button id="department-setting" type="button"
                            class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                          </x-slot>
                        @endif
                      </x-forms.input-group>
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <x-forms.text fieldId="num_cnps" :fieldLabel="__('modules.employees.num_cnps')" 
                        :fieldValue="$employee->employeeDetail->num_cnps"
                          fieldName="num_cnps" fieldRequired="true" :fieldPlaceholder="__('placeholders.num_cnps')">
                      </x-forms.text>
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <x-forms.text fieldId="bank_account_num" :fieldLabel="__('modules.employees.bank_account_num')" :fieldValue="$employee->employeeDetail->bank_account_num" fieldName="bank_account_num"
                      fieldPlaceholder="N° de compte bancaire"></x-forms.text>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.datepicker fieldId="joining_date" :fieldLabel="__('modules.employees.joiningDate')"
                            fieldName="joining_date" :fieldPlaceholder="__('placeholders.date')" fieldRequired="true"
                            :fieldValue="$employee->employeeDetail->joining_date->format(global_setting()->date_format)" />
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.datepicker fieldId="date_end_contrat" :fieldLabel="__('modules.employees.date_end_contrat')"
                          fieldName="date_end_contrat" :fieldPlaceholder="__('placeholders.date')" fieldRequired="true"
                          :fieldValue="$employee->employeeDetail->date_end_contrat->format(global_setting()->date_format)" />
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.select fieldId="type_contrat" :fieldLabel="__('modules.employees.type_contrat')"
                          fieldName="type_contrat">
                          <option value="">--</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'CDD') selected @endif value="CDD">CDD</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'CDI') selected @endif value="CDI">CDI</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Intérim') selected @endif value="Intérim">Intérim</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Consultance') selected @endif value="Consultance">Consultance</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Freelance') selected @endif value="Freelance">Freelance</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Temps partiel') selected @endif value="Temps partiel">Temps partiel</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Saisonnier') selected @endif value="Saisonnier">Saisonnier</option>
                            <option @if ($employee->employeeDetail->type_contrat == 'Apprentissage') selected @endif value="Apprentissage">Apprentissage</option>
                      </x-forms.select>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <x-forms.text class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                        :fieldValue="$employee->employeeDetail->address" fieldName="address" fieldId="address"
                        :fieldPlaceholder="__('placeholders.address')">
                      </x-forms.text>
                    </div>
                </div>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.client.clientOtherDetails')</h4>
                <div class="row p-20">

                    @if ($employee->id != user()->id)
                        <div class="col-lg-2 col-md-4">
                            <div class="form-group my-3">
                                <label class="f-14 text-dark-grey mb-12 w-100"
                                    for="usr">@lang('modules.client.clientCanLogin')</label>
                                <div class="d-flex">
                                    <x-forms.radio fieldId="login-yes" :fieldLabel="__('app.yes')" fieldName="login"
                                        fieldValue="enable" :checked="($employee->login == 'enable') ? 'checked' : ''">
                                    </x-forms.radio>
                                    <x-forms.radio fieldId="login-no" :fieldLabel="__('app.no')" fieldValue="disable"
                                        fieldName="login" :checked="($employee->login == 'disable') ? 'checked' : ''">
                                    </x-forms.radio>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-2 col-md-4">
                      <div class="form-group my-3">
                          <label class="f-14 text-dark-grey mb-12 w-100"
                              for="usr">@lang('modules.emailSettings.emailNotifications')</label>
                          <div class="d-flex">
                              <x-forms.radio fieldId="notification-yes" :fieldLabel="__('app.yes')" fieldValue="1"
                                  fieldName="email_notifications"
                                  :checked="($employee->email_notifications) ? 'checked' : ''">
                              </x-forms.radio>
                              <x-forms.radio fieldId="notification-no" :fieldLabel="__('app.no')" fieldValue="0"
                                  fieldName="email_notifications"
                                  :checked="(!$employee->email_notifications) ? 'checked' : ''">
                              </x-forms.radio>
                          </div>
                      </div>
                    </div>

                    {{-- Users cannot change their own status --}}
                    @if ($employee->id != user()->id && $employee->id != 1)
                      <div class="col-lg-2 col-md-4">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100" for="usr">@lang('app.status')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="status-active" :fieldLabel="__('app.active')"
                                    fieldValue="active" fieldName="status"
                                    checked="($employee->status == 'active') ? 'checked' : ''">
                                </x-forms.radio>
                                <x-forms.radio fieldId="status-inactive" :fieldLabel="__('app.inactive')"
                                    fieldValue="deactive" fieldName="status"
                                    :checked="($employee->status == 'deactive') ? 'checked' : ''">
                                </x-forms.radio>
                            </div>
                        </div>
                      </div>
                    @endif


                    <div class="col-lg-2 col-md-4">
                        <x-forms.label class="my-3" fieldId="hourly_rate"
                            :fieldLabel="__('modules.employees.hourlyRate')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span
                                    class="input-group-text f-14 bg-white-shade">{{ global_setting()->currency->currency_symbol }}</span>
                            </x-slot>

                            <input type="number" step=".01" min="0" class="form-control height-35 f-14"
                                value="{{ $employee->employeeDetail->hourly_rate ?? '' }}" name="hourly_rate"
                                id="hourly_rate">
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <x-forms.label class="my-3" fieldId="nbre_heure_semaine"
                          :fieldLabel="__('modules.employees.nbre_heure_semaine')"></x-forms.label>
                      <x-forms.input-group>
                          <x-slot name="prepend">
                              <span
                                  class="input-group-text f-14 bg-white-shade"><i class="fa fa-clock" aria-hidden="true"></i></span>
                          </x-slot>

                          <input type="number" step=".01" min="0" class="form-control height-35 f-14" value="{{ $employee->employeeDetail->nbre_heure_semaine ?? '' }}"
                              name="nbre_heure_semaine" id="nbre_heure_semaine">
                      </x-forms.input-group>
                    </div>
                    <div class="col-lg-2 col-md-4" style="display:none">
                        <x-forms.label class="my-3" fieldId="slack_username"
                            :fieldLabel="__('modules.employees.slackUsername')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span class="input-group-text f-14 bg-white-shade">@</span>
                            </x-slot>

                            <input type="text" class="form-control height-35 f-14" autocomplete="off"
                                value="{{ $employee->employeeDetail->slack_username ?? '' }}" name="slack_username"
                                id="slack_username">
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-12">
                        <x-forms.text class="tagify_tags" fieldId="tags" :fieldLabel="__('app.skills')"
                            fieldName="tags" :fieldPlaceholder="__('placeholders.skills')"
                            :fieldValue="implode(',', $employee->skills())" />
                    </div>

                    @if (function_exists('sms_setting') && sms_setting()->telegram_status)
                        <div class="col-md-4">
                            <x-forms.number fieldName="telegram_user_id" fieldId="telegram_user_id"
                                fieldLabel="<i class='fab fa-telegram'></i> {{ __('sms::modules.telegramUserId') }}"
                                :fieldValue="$employee->telegram_user_id" :popover="__('sms::modules.userIdInfo')" />
                        </div>
                    @endif


                </div>

                @if (isset($fields) && count($fields) > 0)
                    <div class="row p-20">
                        @foreach ($fields as $field)
                            <div class="col-md-4">
                                @if ($field->type == 'text')
                                    <x-forms.text fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldLabel="$field->label"
                                        fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldPlaceholder="$field->label"
                                        :fieldRequired="($field->required == 'yes') ? 'true' : 'false'"
                                        :fieldValue="$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''">
                                    </x-forms.text>
                                @elseif($field->type == 'password')
                                    <x-forms.password
                                        fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldLabel="$field->label"
                                        fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldPlaceholder="$field->label"
                                        :fieldRequired="($field->required === 'yes') ? true : false"
                                        :fieldValue="$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''">
                                    </x-forms.password>
                                @elseif($field->type == 'number')
                                    <x-forms.number
                                        fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldLabel="$field->label"
                                        fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldPlaceholder="$field->label"
                                        :fieldRequired="($field->required === 'yes') ? true : false"
                                        :fieldValue="$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''">
                                    </x-forms.number>
                                @elseif($field->type == 'textarea')
                                    <x-forms.textarea :fieldLabel="$field->label"
                                        fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldRequired="($field->required === 'yes') ? true : false"
                                        :fieldPlaceholder="$field->label"
                                        :fieldValue="$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''">
                                    </x-forms.textarea>
                                @elseif($field->type == 'radio')
                                    <div class="form-group my-3">
                                        <x-forms.label
                                            fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                            :fieldLabel="$field->label"
                                            :fieldRequired="($field->required === 'yes') ? true : false">
                                        </x-forms.label>
                                        <div class="d-flex">
                                            @foreach ($field->values as $key => $value)
                                                <x-forms.radio fieldId="optionsRadios{{ $key . $field->id }}"
                                                    :fieldLabel="$value"
                                                    fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                    :fieldValue="$value"
                                                    :checked="(isset($employeeDetail) && $employeeDetail->custom_fields_data['field_'.$field->id] == $value) ? true : false" />
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($field->type == 'select')
                                    <div class="form-group my-3">
                                        <x-forms.label
                                            fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                            :fieldLabel="$field->label"
                                            :fieldRequired="($field->required === 'yes') ? true : false">
                                        </x-forms.label>
                                        {!! Form::select('custom_fields_data[' . $field->name . '_' . $field->id . ']', $field->values, isset($employeeDetail) ? $employeeDetail->custom_fields_data['field_' . $field->id] : '', ['class' => 'form-control select-picker']) !!}
                                    </div>
                                @elseif($field->type == 'date')
                                    <x-forms.datepicker custom="true"
                                        fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldRequired="($field->required === 'yes') ? true : false"
                                        :fieldLabel="$field->label"
                                        fieldName="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        :fieldValue="($employeeDetail->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::parse($employeeDetail->custom_fields_data['field_'.$field->id])->format(global_setting()->date_format) : \Carbon\Carbon::now()->format(global_setting()->date_format)"
                                        :fieldPlaceholder="$field->label" />
                                @elseif($field->type == 'checkbox')
                                    <div class="form-group my-3">
                                        <x-forms.label
                                            fieldId="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                            :fieldLabel="$field->label"
                                            :fieldRequired="($field->required === 'yes') ? true : false">
                                        </x-forms.label>
                                        <div class="d-flex checkbox-{{ $field->id }}">
                                            <input type="hidden"
                                                name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                id="{{ $field->name . '_' . $field->id }}"
                                                value="{{ $employeeDetail->custom_fields_data['field_' . $field->id] }}">

                                            @foreach ($field->values as $key => $value)
                                                <x-forms.checkbox fieldId="optionsRadios{{ $key . $field->id }}"
                                                    :fieldLabel="$value" fieldName="$field->name.'_'.$field->id.'[]'"
                                                    :fieldValue="$value"
                                                    :fieldRequired="($field->required === 'yes') ? true : false"
                                                    onchange="checkboxChange('checkbox-{{ $field->id }}', '{{ $field->name . '_' . $field->id }}')"
                                                    :checked="$employeeDetail->custom_fields_data['field_'.$field->id] != '' && in_array($value ,explode(', ', $employeeDetail->custom_fields_data['field_'.$field->id]))" />
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <x-form-actions>
                    <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('employees.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>

<script src="{{ asset('vendor/jquery/tagify.min.js') }}"></script>
<script>
    $(document).ready(function() {

        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        datepicker('#joining_date', {
            position: 'bl',
            @if (!is_null($employee->employeeDetail->joining_date))
            dateSelected: new Date("{{ str_replace('-', '/', $employee->employeeDetail->joining_date) }}"),
            @endif
            ...datepickerConfig
        });

        datepicker('#date_of_birth', {
            position: 'bl',
            maxDate: new Date(),
            @if (!is_null($employee->employeeDetail->date_of_birth))
            dateSelected: new Date("{{ str_replace('-', '/', $employee->employeeDetail->date_of_birth) }}"),
            @endif
            ...datepickerConfig
        });

        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist: {!! json_encode($skills) !!},
            });

        $('#save-form').click(function() {
            const url = "{{ route('employees.update', $employee->id) }}";

            $.easyAjax({
                url: url,
                container: '#save-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-form",
                file: true,
                data: $('#save-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#random_password').click(function() {
            const randPassword = Math.random().toString(36).substr(2, 8);

            $('#password').val(randPassword);
        });

        $('#designation-setting-edit').click(function() {
            const url = "{{ route('designations.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        })

        $('#department-setting').click(function() {
            const url = "{{ route('departments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        if ($('#last_date').length > 0) {
            datepicker('#last_date', {
                position: 'bl',
                @if ($employee->employeeDetail->last_date)
                    dateSelected: new Date("{{ str_replace('-', '/', $employee->employeeDetail->last_date) }}"),
                @endif
                ...datepickerConfig
            });
        }

        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

    $('.cropper').on('dropify.fileReady', function(e) {
            var inputId = $(this).find('input').attr('id');
            var url = "{{ route('cropper', ':element') }}";
            url = url.replace(':element', inputId);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
</script>
