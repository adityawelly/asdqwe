@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit User</h4>
            {{ Breadcrumbs::render('user-edit', $user->id) }}
        </div>
        <div class="row">
            <div class="col-md-9 ml-auto mr-auto">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Formulir User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="user-form" action="{{ route('user.update', $user->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="">Email <span class="required-label">*</span></label>
                                <input type="email" class="form-control" name="email" required value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin diubah">
                            </div>
                            <div class="form-group">
                                <label for="">Role <span class="required-label">*</span></label>
                                <select class="form-control selectpicker" id="role" name="role[]" multiple required>
                                    <option></option>
                                    @foreach ($roles as $role)
                                        <option {{ $user->hasRole($role->name) ? 'selected':'' }} value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Permissions</label>
                                    </div>
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="permissions_show[]" value="{{ $permission->name }}" disabled>
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Extra/Direct Permission</label>
                                <select class="form-control selectpicker" id="extra_permissions" name="extra_permissions[]" multiple>
                                    <option></option>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}" {{ in_array($permission->name, $user_direct_permissions) ? 'selected':'' }}>{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-text text-muted">*Permission khusus yang tidak diberikan oleh roles</span>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="pull-right">
                            <button class="btn btn-primary btn-md" onclick="submitForm(this)"><i class="fas fa-save"></i> Update</button>
                            <button class="btn btn-default btn-md" onclick="redirect('{{ route('user.index') }}')"><i class="fas fa-chevron-left"></i> Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var form = $('#user-form');
    var roles = {!! $roles->toJson() !!};

    function passwordRequired() {
        return $('input[name=password]').val().length > 0;
    }

    form.validate({
        rules: {
            password:{
                required: passwordRequired,
                minlength: {
                    param: 6,
                    depends: passwordRequired
                }
            },
        },

    });

    function submitForm(el){
        if (form.valid()) {
            $(el).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    }

    $(document).ready(function(){
        $('select[name="role[]"]').trigger('change');
        $('#extra_permissions').val(user_direct_permissions).trigger('change');
    });

    $('select[name="role[]"]').on('change', function(){
        var selected = [];
        $.each($(this).val(), function(i, val){
            var role = roles.find(obj => {
                return obj.name === val;
            });
            selected = arrayUnique(selected.concat(role.permissions.map(result => result.name)));
        });
        $('input[name="permissions_show[]"]').val(selected);
        // var role = roles.find(obj => {
        //     return obj.name === $(this).val();
        // });

        // $('#extra_permissions').val(function(){
        //     return role.permissions.map(result => result.name);
        // }).trigger('change');
    });
</script>
@endsection