@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Staffs</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.staffs.index') }}">Staffs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Staff: {{ $staff->name }}</div>
            </div>

            <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ $staff->name }}" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ $staff->email }}" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $staff->role == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Permissions Section --}}
                    <h4 class="mt-4 mb-3 border-bottom pb-2">Permissions</h4>
                    <div class="row">

                        @foreach($groupedPermissions as $module => $actions)
                            <div class="col-lg-3 col-md-4 mb-3">
                                <div class="card shadow-sm border-secondary">

                                    {{-- Card Header + SELECT ALL --}}
                                    <div class="card-header bg-secondary text-white py-2 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ ucfirst($module) }} Access</h6>

                                        {{-- SELECT ALL CHECKBOX --}}
                                        <input type="checkbox" class="select-all" data-module="{{ $module }}">
                                    </div>

                                    <div class="card-body py-2">
                                        @foreach($actions as $action => $permission)
                                            @if($permission)
                                                <div class="form-check">

                                                    <input class="form-check-input permission-checkbox"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        id="perm_{{ $permission->id }}"
                                                        data-module="{{ $module }}"
                                                        {{ in_array($permission->id, $staffPermissions) ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ ucfirst($action) }}
                                                    </label>

                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Staff</button>
                    <a href="{{ route('admin.staffs.index') }}" class="btn btn-default">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- ======================= SELECT ALL JS ======================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    // When Select All checkbox is clicked
    document.querySelectorAll(".select-all").forEach(selectAll => {
        selectAll.addEventListener("change", function () {

            let module = this.getAttribute("data-module");
            let isChecked = this.checked;

            // Select / Deselect all permissions of this module
            document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                .forEach(checkbox => checkbox.checked = isChecked);
        });
    });

});
</script>

@endsection
