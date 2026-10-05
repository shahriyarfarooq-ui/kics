@php
    $name = $field['name'];
    $type = $field['type'] ?? 'text';
    $label = $field['label'] ?? Str::headline($name);
    $value = old($name, $record->{$name} ?? ($field['default'] ?? ''));
    $inputId = ($idPrefix ?? 'field') . '_' . $name;
    $required = $field['required'] ?? false;
@endphp

@if($type === 'checkbox')
    <div class="col-md-6">
        <div class="form-check mt-4">
            <input type="checkbox"
                   class="form-check-input"
                   id="{{ $inputId }}"
                   name="{{ $name }}"
                   value="1"
                   @checked((int) $value === 1)>
            <label class="form-check-label" for="{{ $inputId }}">{{ $label }}</label>
        </div>
    </div>
@elseif($type === 'textarea')
    <div class="col-12">
        <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
        <textarea class="form-control"
                  id="{{ $inputId }}"
                  name="{{ $name }}"
                  rows="5"
                  @if($required) required @endif>{{ $value }}</textarea>
    </div>
@elseif($type === 'select')
    <div class="col-md-6">
        <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
        <select class="form-select"
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if($required) required @endif>
            <option value="">Select {{ $label }}</option>
            @foreach(($field['options'] ?? []) as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
@elseif($type === 'file')
    <div class="col-12">
        <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
        <input type="file"
               class="form-control"
               id="{{ $inputId }}"
               name="{{ $name }}"
               @if($required && !$record) required @endif>
        @if($value)
            <div class="form-text">Current: {{ $value }}</div>
        @endif
    </div>
@else
    <div class="col-md-6">
        <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
        <input type="{{ $type === 'date' ? 'date' : ($type === 'number' ? 'number' : 'text') }}"
               class="form-control"
               id="{{ $inputId }}"
               name="{{ $name }}"
               value="{{ $value }}"
               @if($type === 'number') step="any" @endif
               @if($required) required @endif>
    </div>
@endif
