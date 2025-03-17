@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.network-switches.update", [$networkSwitch->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.networkSwitch.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.networkSwitch.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $networkSwitch->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.networkSwitch.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.networkSwitch.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $networkSwitch->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.networkSwitch.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ip">{{ trans('cruds.networkSwitch.fields.ip') }}</label>
                <input class="form-control {{ $errors->has('ip') ? 'is-invalid' : '' }}" type="text" name="ip" id="ip" value="{{ old('ip', $networkSwitch->ip) }}">
                @if($errors->has('ip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.networkSwitch.fields.ip_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="databases">{{ trans('cruds.networkSwitch.fields.physical_switches') }}</label>
                <select class="form-control select2 {{ $errors->has('physical_routers') ? 'is-invalid' : '' }}" name="physicalSwitches[]" id="physicalSwitches" multiple>
                    @foreach($physicalSwitches as $id => $name)
                    <option value="{{ $id }}" {{ (in_array($id, old('physicalSwitches', [])) || $networkSwitch->physicalSwitches->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @if($errors->has('physicalSwitches'))
                <div class="invalid-feedback">
                    {{ $errors->first('physicalSwitches') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.networkSwitch.fields.physical_switches_helper') }}</span>
            </div>

        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.network-switches.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection
