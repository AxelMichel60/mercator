@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.vlans.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.vlan.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.vlan.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" maxlength="64" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.vlan.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="name" class="recommended" >{{ trans('cruds.vlan.fields.vlan_id') }}</label>
                        <input type="number" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="vlan_id" name="vlan_id" min="1" max="4096" value="{{ old('vlan_id') }}">
                        @if($errors->has('vlan_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('vlan_id') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.vlan.fields.vlan_id_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">{{ trans('cruds.vlan.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.vlan.fields.description_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="recommended" for="subnetworks">{{ trans('cruds.vlan.fields.subnetworks') }}</label>
                <select class="form-control select2 {{ $errors->has('subnetworks') ? 'is-invalid' : '' }}" name="subnetworks[]" id="subnetworks" multiple>
                    @foreach($subnetworks as $id => $subnetwork)
                        <option value="{{ $id }}" {{ in_array($id, old('subnetworks', [])) ? 'selected' : '' }}>{{ $subnetwork }}</option>
                    @endforeach
                </select>
                @if($errors->has('subnetworks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subnetworks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.subnetworks_helper') }}</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.vlans.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>

@endsection
