{{-- Reusable permissions grid: prefix=createPerm or editPerm --}}
<div class="table-responsive mt-2">
<table class="table table-sm table-bordered align-middle permission-grid mb-0">
    <thead class="table-light"><tr><th>Section</th><th>View</th><th>Add</th><th>Edit</th><th>Delete</th></tr></thead>
    <tbody>
    @foreach(\App\Support\AccessPermissions::MODULES as $module => $actions)
        <tr><th scope="row">{{ ucfirst($module === 'reports' ? 'Financial Reports' : $module) }}</th>
        @foreach(['view','create','edit','delete'] as $action)
            <td>
            @if(in_array($action, $actions, true))
                <input type="checkbox" class="form-check-input {{ $prefix }}-checkbox"
                       name="permissions[]" value="{{ $module . '.' . $action }}"
                       aria-label="{{ ucfirst($module) }}: {{ $action }}"
                       @if($prefix === 'createPerm' && in_array($module . '.' . $action, old('permissions', []), true)) checked @endif>
            @else — @endif
            </td>
        @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</div>
