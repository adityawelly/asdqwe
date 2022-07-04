@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Role Permissions</h4>
            {{ Breadcrumbs::render('role-permissions', $role->id) }}
        </div>
        <div class="row">
            <div class="col-md-9 ml-auto mr-auto">
                @include('layouts.partials.alert')
            </div>
            <div class="col-md-9 ml-auto mr-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Beri akses permission</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.assign', $role->id) }}" method="POST" id="user-permissions">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Role</label>
                                <input type="text" class="form-control" value="{{ $role->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Permissions <span class="required-label">*</span></label>
                                <select name="permissions[]" id="permissions" class="form-control selectpicker" multiple required>
                                    @foreach ($permissions as $permission)
                                        <option {{ in_array($permission->name, $role_has_permissions) ? 'selected':'' }} value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" onclick="submitForm(this)"><i class="fas fa-save"></i> Simpan</button>
                        <button class="btn btn-default" onclick="location.assign('{{ route('role.index') }}')"><i class="fas fa-chevron-left"></i> Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var form = $('#user-permissions');

    var validatedForm = form.validate();

    function submitForm(e) {
        if (form.valid()) {
            $(e).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    }
</script>
@endsection