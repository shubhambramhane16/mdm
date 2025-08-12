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

                                $json = $json['data'] ?? []; @endphp
                                @foreach ($json as $key => $value)
                                    @foreach ($value['fields'] as $fieldKey => $fieldValue)
                                        @php
                                            $col = isset($fieldValue['column'])
                                                ? 'col-md-' . $fieldValue['column']
                                                : 'col-md-12';
                                            $validation = isset($fieldValue['validation'])
                                                ? $fieldValue['validation']
                                                : [];
                                            $isRequired =
                                                isset($validation['isRequired']) && $validation['isRequired']
                                                    ? 'isrequired=required'
                                                    : '';
                                            $maxLength = isset($validation['maxLength'])
                                                ? 'maxlength=' . $validation['maxLength']
                                                : '';
                                            $readonly =
                                                isset($fieldValue['restrictions']['readonly']) &&
                                                $fieldValue['restrictions']['readonly']
                                                    ? 'readonly'
                                                    : '';
                                            $numberValidation = isset($validation['pattern'])
                                                ? 'pattern="' . $validation['pattern'] . '"'
                                                : '';

                                        @endphp
                                        <div class="form-group {{ $col }}">
                                            @if (empty($fieldValue['visibilityCondition']))
                                                @php
                                                    $translationKey =
                                                        $moduleName .
                                                        '/add_form.form.' .
                                                        ($fieldValue['label'] ??
                                                            ($fieldValue['fieldKey'] ?? $fieldKey));
                                                @endphp
                                                @if (Lang::has($translationKey))
                                                    <label>{{ __($translationKey) }}</label>
                                                @else
                                                    <label>{{ $fieldValue['label'] }}</label>
                                                @endif
                                            @endif
                                            @switch($fieldValue['type'])
                                                @case('text')
                                                    <input type="text" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }} {{ $readonly }}
                                                        @if (!empty($fieldValue['tooltip'])) title="{{ $fieldValue['tooltip'] }}" @endif
                                                        @if (isset($fieldValue['validation']['maxLength'])) max="{{ $fieldValue['validation']['maxLength'] }}" @endif
                                                        @if (isset($fieldValue['validation']['minLength'])) min="{{ $fieldValue['validation']['minLength'] }}" @endif>
                                                @break

                                                @case('email')
                                                    <input type="email" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                                @case('password')
                                                    <input type="password" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                                @case('number')
                                                    <input type="text" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control decimal" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                                @case('date')
                                                    <input type="date" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                                @case('datetime')
                                                    <input type="datetime-local" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                        class="form-control" placeholder="{{ $fieldValue['placeholder'] ?? '' }}"
                                                        {{ $isRequired }} {{ $maxLength }}>
                                                @break

                                                @case('textarea')
                                                    <textarea name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}" class="form-control"
                                                        placeholder="{{ $fieldValue['placeholder'] ?? '' }}" {{ $isRequired }} {{ $maxLength }}></textarea>
                                                @break

                                                @case('file')
                                                    <input type="file"
                                                        name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}{{ isset($fieldValue['isMultiple']) && $fieldValue['isMultiple'] ? '[]' : '' }}"
                                                        class="form-control-file file-input" {{ $isRequired }}
                                                        @if (isset($fieldValue['isMultiple']) && $fieldValue['isMultiple']) multiple @endif
                                                        @if (isset($validation['allowedTypes'])) accept="{{ implode(',', array_map(fn($ext) => '.' . $ext, $validation['allowedTypes'])) }}" @endif>
                                                    @if (isset($validation['maxSizeMb']))
                                                        <small class="form-text text-muted">
                                                            Max file size: {{ $validation['maxSizeMb'] }}MB.
                                                        </small>
                                                    @endif
                                                    <div class="file-preview mt-2"></div>
                                                    @push('scripts')
                                                    @endpush
                                                @break

                                                @case('radio')
                                                    @foreach ($fieldValue['dataSourceValue'] as $option)
                                                        <div class="form-check">
                                                            <input type="radio" name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                                class="form-check-input"
                                                                id="{{ $fieldValue['fieldKey'] ?? $fieldKey . '_' . $option }}"
                                                                value="{{ $option }}">
                                                            <label class="form-check-label"
                                                                for="{{ $fieldValue['fieldKey'] ?? $fieldKey . '_' . $option }}">{{ $option }}</label>
                                                        </div>
                                                    @endforeach
                                                @break

                                                @case('dropdown')
                                                    @if ($fieldValue['dataSourceType'] == 'static')
                                                        <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                            class="form-control select" {{ $isRequired }}>
                                                            @foreach ($fieldValue['dataSourceValue'] as $option)
                                                                <option value="{{ $option }}" @if(request('id') == $option) selected @endif>
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
                                                                            $fieldValue['condition']['fieldKey'],
                                                                            $fieldValue['condition']['operator'],
                                                                            $fieldValue['condition']['value'],
                                                                        )
                                                                        ->get();
                                                                } else {
                                                                    $options = app($modelClass)::all();
                                                                }
                                                            @endphp

                                                            <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                                class="form-control select"
                                                                id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}"
                                                                {{ $isRequired }}>
                                                                <option value="">Select {{ $moduleName }}</option>
                                                                @foreach ($options as $detail)
                                                                    <option value="{{ $detail['id'] }}" @if(request('id') == $detail['id']) selected @endif>
                                                                        {{ $detail['name'] ?? $detail['title'] ?? $detail['label'] }}
                                                                    </option>

                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    @endif
                                                @break

                                                @case('repeater')
                                                    <div class="repeater">
                                                        <div class="">
                                                            <div class="row"
                                                                data-item-id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}-items">
                                                                <div class="col-md-10 repeater-items"
                                                                    data-item-id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}-items">
                                                                    @for ($i = 0; $i < ($fieldValue['defaultItems'] ?? 1); $i++)
                                                                        <div class="repeater-item row align-items-end">
                                                                            @foreach ($fieldValue['fields'] as $subField)
                                                                                @php
                                                                                    $subCol = isset($subField['column'])
                                                                                        ? 'col-md-' .
                                                                                            $subField['column']
                                                                                        : 'col-md-12';
                                                                                    $subValidation = isset(
                                                                                        $subField['validation'],
                                                                                    )
                                                                                        ? $subField['validation']
                                                                                        : [];
                                                                                    $subIsRequired =
                                                                                        isset($subValidation['isRequired']) && $subValidation['isRequired']
                                                                                            ? 'isrequired=required'
                                                                                            : '';
                                                                                    $subMaxLength = isset(
                                                                                        $subValidation['maxLength'],
                                                                                    )
                                                                                        ? 'maxlength=' .
                                                                                            $subValidation['maxLength']
                                                                                        : '';
                                                                                    $subMinLength = isset(
                                                                                        $subValidation['minLength'],
                                                                                    )
                                                                                        ? 'minlength=' .
                                                                                            $subValidation['minLength']
                                                                                        : '';
                                                                                    $subMin = isset(
                                                                                        $subValidation['min'],
                                                                                    )
                                                                                        ? 'min=' . $subValidation['min']
                                                                                        : '';
                                                                                    $subMax = isset(
                                                                                        $subValidation['max'],
                                                                                    )
                                                                                        ? 'max=' . $subValidation['max']
                                                                                        : '';
                                                                                @endphp
                                                                                <div class="form-group {{ $subCol }}">
                                                                                    <label>{{ $subField['label'] }}</label>
                                                                                    @switch($subField['type'])
                                                                                        @case('text')
                                                                                            <input type="text"
                                                                                                name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control"
                                                                                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                                {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }} {{ $subMin }}
                                                                                                {{ $subMax }}>
                                                                                            @break
                                                                                        @case('email')
                                                                                            <input type="email"
                                                                                                name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control"
                                                                                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                                {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }}>
                                                                                            @break

                                                                                        @case('number')
                                                                                            <input type="text"
                                                                                                name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control decimal"
                                                                                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                                {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }}>
                                                                                            @break
                                                                                        @case('date')
                                                                                            <input type="date"
                                                                                                name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control"
                                                                                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                                {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }}>
                                                                                            @break

                                                                                        @case('datetime-local')
                                                                                            <input type="{{ $subField['type'] }}"
                                                                                                name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control"
                                                                                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                                                                                {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }} {{ $subMin }}
                                                                                                {{ $subMax }}>
                                                                                        @break

                                                                                        @case('textarea')
                                                                                            <textarea name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                class="form-control" placeholder="{{ $subField['placeholder'] ?? '' }}" {{ $subIsRequired }} {{ $subMaxLength }}
                                                                                                {{ $subMinLength }}></textarea>
                                                                                        @break

                                                                                        @case('dropdown')
                                                                                            @if ($subField['dataSourceType'] == 'static')
                                                                                                <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                    class="form-control select dropbox_{{ $subField['fieldKey'] }}" {{ $subIsRequired }}>
                                                                                                    <option value="">Select {{ $subField['label'] ?? '' }}</option>
                                                                                                    @foreach ($subField['dataSourceValue'] ?? [] as $option)
                                                                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            @elseif($subField['dataSourceType'] == 'master')
                                                                                                @php
                                                                                                    $dataSource = $subField['dataSource'] ?? [];
                                                                                                @endphp

                                                                                                @if ($dataSource)
                                                                                                    @php $subModuleName = ucfirst($dataSource); @endphp

                                                                                                    @php
                                                                                                        $modelClass = 'App\\Models\\' . $subModuleName;
                                                                                                        if (!empty($subField['condition'])) {
                                                                                                            $options = app($modelClass)
                                                                                                                ::where(
                                                                                                                    $subField['condition']['fieldKey'],
                                                                                                                    $subField['condition']['operator'],
                                                                                                                    $subField['condition']['value'],
                                                                                                                )
                                                                                                                ->get();
                                                                                                        } else {
                                                                                                            $options = app($modelClass)::all();
                                                                                                        }
                                                                                                    @endphp

                                                                                                    <select name="{{ $fieldValue['fieldKey'] ?? $fieldKey }}[{{ $i }}][{{ $subField['fieldKey'] }}]"
                                                                                                        class="form-control select dropbox_{{ $subField['fieldKey'] }}"
                                                                                                        id="{{ $fieldValue['fieldKey'] ?? $fieldKey }}_{{ $subField['fieldKey'] }}_{{ $i }}"
                                                                                                        {{ $subIsRequired }}>
                                                                                                        <option value="">Select {{ $subModuleName }}</option>
                                                                                                        @foreach ($options as $detail)
                                                                                                            <option value="{{ $detail['id'] }}">
                                                                                                                {{ $detail['name'] ?? $detail['title'] ?? $detail['label'] }} ({{$detail['code']}})
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @endif
                                                                                            @endif
                                                                                        @break

                                                                                    @endswitch
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button"
                                                                        class="btn btn-primary add-repeater-item mt-8"
                                                                        type="button">+</button>
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
        $('.decimal').on('input', function() {
        // Allow only numbers and a single decimal point
        this.value = this.value.replace(/[^0-9.]/g, '');
        // Prevent more than one decimal point
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.replace(/\.+$/, "");
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

                $newItem.find('.dropbox').each(function() {
                    // Reset the select2 dropdown
                    $(this).val('').trigger('change');
                });

                // Remove any existing remove button to avoid duplicates
                $newItem.find('.remove-repeater-item').closest('.col-12').remove();

                // Add remove button
                $newItem.append(
                    '<div class="col-12 mt-2"><button type="button" class="btn btn-danger remove-repeater-item">Remove</button></div>'
                );

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
        // create module slug on click of module title
        $(document).on('change', 'input[name="name"]', function() {
            var moduleTitle = $(this).val();
            var slug = moduleTitle.toLowerCase().replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '');
            $('input[name="slug"]').val(slug);
        });
    </script>
    <script>
        // get the subcategory based on parent category
        $(document).on('change', 'select[id="category_id"]', function() {
            var parentId = $(this).val();
            var subCategorySelect = $('select[id="subcategory_id"]');
            subCategorySelect.empty();
            subCategorySelect.append('<option value="">Select Sub Category</option>');
            if (parentId) {
                $.ajax({
                    url: '{{ url('/admin/subcategory-list') }}',
                    type: 'GET',
                    data: {
                        category_id: parentId
                    },
                    success: function(data) {
                        subCategorySelect.empty();
                        if (data && Array.isArray(data) && data.length > 0) {
                            $.each(data, function(index, subCategory) {
                                subCategorySelect.append(
                                    '<option value="' + subCategory.id + '">' + subCategory
                                    .name + '</option>'
                                );
                            });
                        } else {
                            subCategorySelect.append(
                                '<option value="">No Sub Categories Found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        subCategorySelect.empty();
                        subCategorySelect.append(
                            '<option value="">Error fetching subcategories</option>');
                    }
                });
            } else {
                subCategorySelect.append('<option value="">Select Sub Category</option>');
            }
        });
    </script>
    <script>
        $(document).on('change', '.file-input', function(e) {
            var $preview = $(this).siblings('.file-preview');
            $preview.empty();
            var files = this.files;
            if (files && files.length > 0) {
                $.each(files, function(i, file) {
                    var fileType = file.type;
                    var fileId = 'file-preview-' + Math.random().toString(36).substr(2, 9);
                    if (fileType.startsWith('image/')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $preview.append(
                                '<div id="' + fileId + '" class="d-inline-block position-relative mr-2 mb-2">' +
                                    '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width:120px;max-height:120px;">' +
                                    '<button type="button" class="btn btn-sm btn-danger remove-preview position-absolute" style="top:2px;right:2px;padding:0 6px;line-height:1;">&times;</button>' +
                                '</div>'
                            );
                        }
                        reader.readAsDataURL(file);
                    } else {
                        $preview.append(
                            '<div id="' + fileId + '" class="mb-1 position-relative d-inline-block">' +
                                '<i class="fa fa-file mr-1"></i>' + file.name +
                                '<button type="button" class="btn btn-sm btn-danger remove-preview position-absolute" style="top:2px;right:2px;padding:0 6px;line-height:1;">&times;</button>' +
                            '</div>'
                        );
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

        // Remove preview on click
        $(document).on('click', '.remove-preview', function() {
            $(this).parent().remove();
            // Note: This only removes the preview, not the file from the input (cannot remove specific files from input for security reasons)
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
