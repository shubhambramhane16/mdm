@extends('admin.layout.default')

@section($json['slug'], 'active menu-item-open')
@php $moduleName = $json['module'] ?? 'default'; @endphp
@section('content')
<div class="card card-custom">
    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-12">
                    <form method="POST" action="" class="w-100" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- read the json from the same folder and create --}}
                           @php

                            $jsonData = $json['data'] ?? [];
                           @endphp
                            @foreach($jsonData as $key => $value)
                                @foreach($value['fields'] as $fieldKey => $fieldValue)
                                    @php
                                        $col = isset($fieldValue['column']) ? 'col-md-' . $fieldValue['column'] : 'col-md-12';
                                        $validation = isset($fieldValue['validation']) ? $fieldValue['validation'] : [];
                                        $isRequired = isset($validation['isRequired']) && $validation['isRequired'] ? 'isrequired=required' : '';
                                        $maxLength = isset($validation['maxLength']) ? 'maxlength='.$validation['maxLength'] : '';
                                        $formValue = isset($details[$fieldValue['fieldKey']]) ? $details[$fieldValue['fieldKey']] : '';
                                        // dump($fieldValue['fieldKey'],$details);
                                    @endphp
                                    <div class="form-group {{ $col }}">
                                        @if(empty($fieldValue['visibilityCondition']))
                                            @php
                                                $translationKey = $moduleName . '/edit_form.form.' . $fieldValue['fieldKey'];
                                            @endphp
                                            @if (Lang::has($translationKey))
                                                <label>{{ __($translationKey) }}</label>
                                            @else
                                                <label>{{ $fieldValue['label'] }}</label>
                                            @endif
                                        @endif
                                        @switch($fieldValue['type'])

                                            @case('text')
                                                <input type="text" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }} tooltip="{{ $fieldValue['tooltip'] ?? '' }}" @if(isset($fieldValue['validation'])) max="{{ $fieldValue['validation']['maxLength'] ?? '' }}" min="{{ $fieldValue['validation']['minLength'] ?? '' }}" @endif>
                                                @break

                                            @case('email')
                                                <input type="email" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                            @case('password')
                                                <input type="password" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                            @case('number')
                                                <input type="text" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control decimal" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>
                                                @break
                                            @case('date')
                                                <input type="date" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                            @case('datetime')
                                                <input type="datetime-local" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" value="{{ old($fieldKey, $formValue) }}"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                            @case('textarea')
                                                <textarea name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control"
                                                    placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}>{{ old($fieldKey, $formValue) }}</textarea>
                                                @break
                                            @case('file')
                                                <input type="file" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}{{ isset($fieldValue['isMultiple']) && $fieldValue['isMultiple'] ? '[]' : '' }}"
                                                    class="form-control-file file-input" {{ $isRequired }}
                                                    @if (isset($fieldValue['isMultiple']) && $fieldValue['isMultiple']) multiple @endif>
                                                @if (isset($fieldValue['isMultiple']) && $fieldValue['isMultiple'])
                                                    <small class="form-text text-muted">You can upload multiple files.</small>
                                                @else
                                                    <small class="form-text text-muted">You can upload a single file.</small>
                                                @endif

                                                @if (isset($details[$fieldValue['fieldKey']]) && is_array($details[$fieldValue['fieldKey']]))
                                                    <div class="mt-2">
                                                        <strong>Uploaded Files:</strong>
                                                        <div>
                                                            @foreach($details[$fieldValue['fieldKey']] as $idx => $uploadedFile)
                                                                <div class="d-inline-block position-relative mr-2 mb-2">
                                                                    <a href="{{ asset('attachments/' . $uploadedFile) }}" target="_blank">
                                                                        <img src="{{ asset('attachments/' . $uploadedFile) }}" alt="Uploaded Image" style="max-width: 200px; max-height: 200px;">
                                                                    </a>
                                                                    <form method="POST" action="{{ url('admin/'. $moduleName . '/remove') }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this file?');">
                                                                        @csrf
                                                                        <input type="hidden" name="fieldKey" value="{{ $fieldValue['fieldKey'] }}">
                                                                        <input type="hidden" name="file" value="{{ $uploadedFile }}">
                                                                        <button type="submit" class="btn btn-sm btn-danger position-absolute" style="top: 0; right: 0;">Remove</button>
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif (isset($details[$fieldValue['fieldKey']]) && !is_array($details[$fieldValue['fieldKey']]) && $details[$fieldValue['fieldKey']])
                                                    @if(!empty($details[$fieldValue['fieldKey']]))
                                                        <div class="mt-2">
                                                            <strong>Uploaded File:</strong>
                                                            <div>
                                                                @php
                                                                    $file = $details[$fieldValue['fieldKey']];
                                                                    // Handle if $file is a JSON array string
                                                                    if (is_string($file) && Str::startsWith($file, '[')) {
                                                                        $fileArray = json_decode($file, true);
                                                                    } else {
                                                                        $fileArray = [$file];
                                                                    }
                                                                @endphp
                                                                @foreach($fileArray as $uploadedFile)
                                                                    @if(!empty($uploadedFile))
                                                                        <div class="d-inline-block position-relative mr-2 mb-2">
                                                                            <a href="{{ asset('attachments/' . $uploadedFile) }}" target="_blank">
                                                                                <img src="{{ asset('attachments/' . $uploadedFile) }}" alt="Uploaded Image" style="max-width: 200px; max-height: 200px;">
                                                                            </a>
                                                                            <form method="POST" action="{{ url('admin/'. $moduleName . '/remove') }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this file?');">
                                                                                @csrf
                                                                                <input type="hidden" name="id" value="{{ $details['id'] ?? '' }}">
                                                                                <input type="hidden" name="fieldKey" value="{{ $fieldValue['fieldKey'] }}">
                                                                                <input type="hidden" name="file" value="{{ $uploadedFile }}">
                                                                                <button type="submit" class="btn btn-sm btn-danger position-absolute" style="top: 0; right: 0;">Remove</button>
                                                                            </form>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                @break

                                            @case('radio')
                                                @foreach($fieldValue['dataSourceValue'] as $option)
                                                    <div class="form-check">
                                                        <input type="radio" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-check-input"
                                                            id="{{ $fieldValue['fieldKey'] ?? $fieldKey . '_' . $option }}" value="{{ $option }}">
                                                        <label class="form-check-label" for="{{ $fieldValue['fieldKey'] ?? $fieldKey . '_' . $option }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                                @break

                                            @case('button')
                                                <button type="button" class="btn btn-primary" id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" {{ $isRequired }}  @if(isset($fieldValue['functions'])) @foreach($fieldValue['functions'] as $function => $params) @if(isset($params['event']) && $params['event']){{ $params['event'] }}="{{ $params['action']['functionName']}}(@if(isset($params['action']['params']) && is_array($params['action']['params']))@foreach($params['action']['params'] as $idx =>$param)@if(isset($param['targetFieldKey']))'{{$param['targetFieldKey']}}'@if(!$loop->last),@endif @endif @endforeach @endif)" @endif @endforeach @endif
                                               >
                                                    {{ $fieldValue['fieldKey'] }}
                                                </button>
                                                @break


                                            @case('dropdown')

                                                    @if($fieldValue['dataSourceType'] == 'static')

                                                        <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control" {{ $isRequired }}>
                                                            @foreach($fieldValue['dataSourceValue'] as $option)
                                                                <option value="{{ $option }}"
                                                                    @if(old($fieldKey, $formValue) == $option) selected @endif>
                                                                    {{ $option }}
                                                                </option>

                                                            @endforeach

                                                        </select>
                                                    @elseif($fieldValue['dataSourceType'] == 'master')
                                                        @php
                                                            $dataSource = $fieldValue['dataSource'] ?? [];
                                                        @endphp

                                                        @if ($dataSource)
                                                            @php $moduleName = ucfirst($dataSource); @endphp

                                                            @php
                                                                $modelClass = 'App\\Models\\' . $moduleName;
                                                                if (!empty($fieldValue['condition'])) {
                                                                    $options = app($modelClass)
                                                                        ::where(
                                                                            $fieldValue['condition']['fieldKey'],$fieldValue['condition']['operator'],
                                                                            $fieldValue['condition']['value'],
                                                                        )
                                                                        ->get();
                                                                } else {
                                                                    $options = app($modelClass)::all();
                                                                }
                                                            @endphp

                                                            <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                                class="form-control select"
                                                                id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" {{ $isRequired }}>
                                                                <option value="">Select {{ $moduleName }}</option>
                                                                @foreach ($options as $detail)
                                                                    <option value="{{ $detail['id'] }}"
                                                                        @if (old($fieldKey, $formValue) == $detail['id']) selected @endif>
                                                                        {{ $detail['name'] }}
                                                                    </option>

                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    @endif
                                                    @break
                                            @case('repeater')
                                            <div class="repeater">
                                                    <div class="">
                                                        <div class="row" data-item-id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}-items">
                                                            <div class="col-md-10 repeater-items" data-item-id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}-items">
                                                                @php
                                                                    // Use $details['extra_info'] for repeater data
                                                                    $repeaterData = [];
                                                                    if (isset($details[$fieldValue['fieldKey']])) {
                                                                        if (is_array($details[$fieldValue['fieldKey']])) {
                                                                            $repeaterData = $details[$fieldValue['fieldKey']];
                                                                        } elseif (is_string($details[$fieldValue['fieldKey']]) && Str::startsWith($details[$fieldValue['fieldKey']], '[')) {
                                                                            $repeaterData = json_decode($details[$fieldValue['fieldKey']], true) ?? [];
                                                                        }
                                                                    }
                                                                    $numItems = is_array($repeaterData) && count($repeaterData) > 0 ? count($repeaterData) : ($fieldValue['defaultItems'] ?? 1);
                                                                @endphp

                                                                @for($i = 0; $i < $numItems; $i++)
                                                                    <div class="repeater-item row align-items-end ">
                                                                        @foreach($fieldValue['fields'] as $subField)
                                                                            @php
                                                                                $subCol = isset($subField['column']) ? 'col-md-' . $subField['column'] : 'col-md-12';
                                                                                $subValidation = isset($subField['validation']) ? $subField['validation'] : [];
                                                                                $subIsRequired = (isset($subValidation['isRequired']) && $subValidation['isRequired']) ? 'required' : '';
                                                                                $subMaxLength = isset($subValidation['maxLength']) ? 'maxlength='.$subValidation['maxLength'] : '';
                                                                                $subMinLength = isset($subValidation['minLength']) ? 'minlength='.$subValidation['minLength'] : '';
                                                                                $subMin = isset($subValidation['min']) ? 'min='.$subValidation['min'] : '';
                                                                                $subMax = isset($subValidation['max']) ? 'max='.$subValidation['max'] : '';
                                                                                $fieldName = $fieldValue['fieldKey'] ?? $fieldKey;
                                                                                $subFieldKey = $subField['fieldKey'];
                                                                                $valueData = '';
                                                                                if (is_array($repeaterData) && isset($repeaterData[$i][$subFieldKey])) {
                                                                                    $valueData = $repeaterData[$i][$subFieldKey];
                                                                                }
                                                                            @endphp
                                                                            <div class="form-group {{ $subCol }}">
                                                                                <label class="font-weight-bold">{{ $subField['label'] }}</label>
                                                                                @switch($subField['type'])
                                                                                    @case('text')
                                                                                        <input type="text" name="{{ $fieldName }}[{{ $i }}][{{ $subFieldKey }}]"
                                                                                            class="form-control"
                                                                                            placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                            value="{{ old($fieldName.'.'.$i.'.'.$subFieldKey, $valueData) }}"
                                                                                            {{ $subIsRequired }} {{ $subMaxLength }} {{ $subMinLength }} {{ $subMin }} {{ $subMax }}>
                                                                                        @break
                                                                                    @case('number')
                                                                                        <input type="text" name="{{ $fieldName }}[{{ $i }}][{{ $subFieldKey }}]"
                                                                                            class="form-control decimal"
                                                                                            placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                            value="{{ old($fieldName.'.'.$i.'.'.$subFieldKey, $valueData) }}"
                                                                                            {{ $subIsRequired }} {{ $subMaxLength }} {{ $subMinLength }} {{ $subMin }} {{ $subMax }}>
                                                                                        @break
                                                                                    @case('textarea')
                                                                                        <textarea name="{{ $fieldName }}[{{ $i }}][{{ $subFieldKey }}]"
                                                                                            class="form-control"
                                                                                            placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                            {{ $subIsRequired }} {{ $subMaxLength }} {{ $subMinLength }} {{ $subMin }} {{ $subMax }}>{{ old($fieldName.'.'.$i.'.'.$subFieldKey, $valueData) }}</textarea>
                                                                                        @break
                                                                                    {{-- Add more cases as needed --}}
                                                                                @endswitch
                                                                            </div>
                                                                        @endforeach
                                                                        @if($i > 0)
                                                                            <div class="col-12 mt-2">
                                                                                <button type="button" class="btn btn-danger remove-repeater-item">Remove</button>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-primary add-repeater-item mt-8">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                        @endswitch

                                    </div>
                                @endforeach
                                <div class="form-group col-md-{{ $value['gridColumns'] ?? '12' }}">
                                        <center>
                                            @php
                                                $submitTranslationKey = $moduleName . '/add_form.actions.submit';
                                                $submitLabel = Lang::has($submitTranslationKey)
                                                    ? __($submitTranslationKey)
                                                    : 'Submit';
                                            @endphp
                                            <button class="btn btn-success">{{ $submitLabel }}</button>
                                        </center>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<style>
    textarea.form-control {
        height: 250px !important;
    }
</style>
@endsection

{{-- Scripts Section --}}
@section('scripts')

<script>
    $(document).ready(function() {
        // Handle add repeater item button click
        $(document).on('click', '.add-repeater-item', function() {
            var $repeater = $(this).closest('.repeater');
            var $itemsContainer = $repeater.find('.repeater-items');
            var $firstItem = $itemsContainer.children('.repeater-item:first');
            var $newItem = $firstItem.clone();

            // Determine new index for the repeater item
            var newIndex = $itemsContainer.children('.repeater-item').length;

            // Clear input/textarea values and update names/ids in the cloned item
            $newItem.find('input, textarea').each(function() {
                $(this).val('');
                // Update name attribute index
                var name = $(this).attr('name');
                if (name) {
                    name = name.replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', name);
                }
                // Update id attribute index if present
                var id = $(this).attr('id');
                if (id) {
                    id = id.replace(/_\d+$/, '_' + newIndex);
                    $(this).attr('id', id);
                }
            });

            // Remove any existing remove button to avoid duplicates
            $newItem.find('.remove-repeater-item').closest('.col-12').remove();

            // Add remove button
            $newItem.append('<div class="col-12 mt-2"><button type="button" class="btn btn-danger remove-repeater-item">Remove</button></div>');

            $itemsContainer.append($newItem);
        });

        // Handle remove repeater item button click
        $(document).on('click', '.remove-repeater-item', function() {
            var $item = $(this).closest('.repeater-item');
            var $itemsContainer = $item.parent();
            $item.remove();

            // Re-index the remaining repeater items
            $itemsContainer.children('.repeater-item').each(function(index) {
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        name = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', name);
                    }
                    var id = $(this).attr('id');
                    if (id) {
                        id = id.replace(/_\d+$/, '_' + index);
                        $(this).attr('id', id);
                    }
                });
            });
        });
    });
</script>
<script>
    $(document).on('change', 'input[name="name"]', function() {
        var moduleTitle = $(this).val();
        var slug = moduleTitle.toLowerCase().replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '');
        $('input[name="slug"]').val(slug);
    });
</script>
<script>
    $(document).on('change', 'select[id="category_id"]', function() {
            var parentId = $(this).val();
            var subCategorySelect = $('select[id="subcategory_id"]');
            subCategorySelect.empty();
            subCategorySelect.append('<option value="">Select Sub Category</option>');
            if (parentId) {
            $.ajax({
                url: '{{ url("/admin/subcategory-list") }}',
                type: 'GET',
                data: {
                category_id: parentId
                },
                success: function(data) {
                subCategorySelect.empty();
                if (data && Array.isArray(data) && data.length > 0) {
                    $.each(data, function(index, subCategory) {
                    subCategorySelect.append(
                        '<option value="' + subCategory.id + '">' + subCategory.name + '</option>'
                    );
                    });
                } else {
                    subCategorySelect.append('<option value="">No Sub Categories Found</option>');
                }
                },
                error: function(xhr, status, error) {
                subCategorySelect.empty();
                subCategorySelect.append('<option value="">Error fetching subcategories</option>');
                }
            });
            } else {
            subCategorySelect.append('<option value="">Select Sub Category</option>');
            }
        });

// if the subcategory is selected, then get the subcategory based on parent category
        $(document).on('change', 'select[id="subcategory_id"]', function() {
            var subCategoryId = $(this).val();
            if (subCategoryId) {
                $.ajax({
                    url: '{{ url("/admin/subcategory-list") }}',
                    type: 'GET',
                    data: {
                        parent: subCategoryId
                    },
                    success: function(data) {
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                    }
                });
            }
        });

    $(document).on('change', 'select[id="client_id"]', function() {
        var clientId = $(this).val();
        var unitSelect = $('select[id="client_unit_id"]');
        unitSelect.empty();
        unitSelect.append('<option value="">Select Unit</option>');
        if (clientId) {
            $.ajax({
                url: '{{ url("/admin/unit-list") }}',
                type: 'GET',
                data: {
                    client_id: clientId
                },
                success: function(data) {
                    unitSelect.empty();
                    if (data && Array.isArray(data) && data.length > 0) {
                        $.each(data, function(index, unit) {
                            unitSelect.append(
                                '<option value="' + unit.id + '">' + unit.name + '</option>'
                            );
                        });
                    } else {
                        unitSelect.append('<option value="">No Units Found</option>');
                    }
                },
                error: function(xhr, status, error) {
                    unitSelect.empty();
                    unitSelect.append('<option value="">Error fetching units</option>');
                }
            });
        } else {
            unitSelect.append('<option value="">Select Unit</option>');
        }
    });



</script>
<script>
    $(document).ready(function() {
        $('.select').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
<script>
        $('.decimal').on('input', function() {
        // Allow only numbers and a single decimal point
        this.value = this.value.replace(/[^0-9.]/g, '');
        // Prevent more than one decimal point
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.replace(/\.+$/, "");
        }
    });
</script>

@endsection
