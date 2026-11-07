@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('content')
<div class="card mb-4"><div class="card-body">
<h5>Create Normal User</h5>
<form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="row g-3">@csrf
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" autocomplete="off" required></div>
<div class="col-md-6">
    <label for="createUserPassword" class="form-label">Password</label>
    <div class="password-field">
        <input id="createUserPassword" name="password" type="password"
               class="form-control" autocomplete="new-password" minlength="8" required>
        <button type="button" class="btn password-toggle"
                data-toggle-password="createUserPassword" aria-controls="createUserPassword"
                aria-label="Show password" aria-pressed="false">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/>
                <circle cx="12" cy="12" r="2.5"/>
                <path class="password-eye-slash" d="M3 3l18 18"/>
            </svg>
        </button>
    </div>
</div>
<div class="col-md-6">
    <label for="createUserPasswordConfirmation" class="form-label">Confirm Password</label>
    <div class="password-field">
        <input id="createUserPasswordConfirmation" name="password_confirmation" type="password"
               class="form-control" autocomplete="new-password" minlength="8" required>
        <button type="button" class="btn password-toggle"
                data-toggle-password="createUserPasswordConfirmation" aria-controls="createUserPasswordConfirmation"
                aria-label="Show confirm password" aria-pressed="false">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/>
                <circle cx="12" cy="12" r="2.5"/>
                <path class="password-eye-slash" d="M3 3l18 18"/>
            </svg>
        </button>
    </div>
</div>
<div class="col-md-6"><label class="form-label">Profile Photo (optional)</label><input name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="form-control"></div>
<div class="col-12"><h6 class="mb-1">Access Permissions</h6><p class="small text-muted mb-2">Select allowed actions. All permissions are off by default.</p>@include('users.partials.permissions-grid', ['prefix' => 'createPerm'])</div>
<div class="col-12"><button class="btn btn-primary">Create User</button></div>
</form></div></div>
<div class="card"><div class="card-body"><h5>All Users</h5><div class="table-responsive">
<table class="table table-striped align-middle" data-pagination="5" data-pagination-label="Users"><thead><tr><th>Photo</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@foreach($users as $user)
<tr>
<td>@if($user->avatar_path)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar_path) }}" alt="" class="user-table-avatar" width="40" height="40">@else<span class="text-muted">—</span>@endif</td>
<td>{{ $user->name }}</td><td>{{ $user->email }}</td>
<td>{{ $user->role === 'super_admin' ? 'Super Admin' : 'Normal User' }}</td>
<td class="user-status"><span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
<td>@if($user->role === 'user')
<button type="button" class="btn btn-outline-primary btn-sm js-user-edit"
 data-url="{{ route('users.update', $user) }}"
 data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-permissions='@json($user->permissions ?? [])'>Edit</button>
<form class="d-inline js-user-action" method="POST" action="{{ route('users.status', $user) }}" data-kind="status">@csrf @method('PATCH')
<button class="btn btn-outline-secondary btn-sm" type="submit">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button></form>
<button type="button" class="btn btn-outline-danger btn-sm js-user-delete" data-url="{{ route('users.destroy', $user) }}" data-name="{{ $user->name }}">Delete</button>
@else <span class="badge bg-secondary">Edit via My Profile</span> @endif</td>
</tr>
@endforeach
</tbody></table></div></div></div>
<dialog id="usersEditDialog" class="user-edit-dialog" aria-labelledby="usersEditHeading">
<div class="d-flex justify-content-between align-items-center mb-3"><h5 id="usersEditHeading" class="mb-0">Edit User</h5><button class="btn-close" id="usersEditClose" aria-label="Close" type="button"></button></div>
<form id="usersEditForm">
<div id="usersEditMessage" class="alert alert-danger d-none" role="alert"></div>
<div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required maxlength="255"></div>
<div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control" required maxlength="255"></div>
<div class="mb-3"><label class="form-label">New Password (leave blank to keep current)</label><input name="password" type="password" class="form-control" minlength="8" autocomplete="new-password"></div>
<div class="mb-3"><label class="form-label">Confirm New Password</label><input name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
<div class="mb-3"><label class="form-label">Profile Photo</label><input name="avatar" type="file" class="form-control" accept="image/jpeg,image/png,image/webp"></div>
<div class="mb-3"><h6>Access Permissions</h6>@include('users.partials.permissions-grid', ['prefix' => 'editPerm'])</div>
<div class="d-flex gap-2 justify-content-end"><button type="button" id="usersEditCancel" class="btn btn-secondary">Cancel</button><button type="submit" id="usersEditSave" class="btn btn-primary">Save Changes</button></div>
</form></dialog>
<dialog id="userDeleteDialog" class="user-edit-dialog" aria-labelledby="userDeleteHeading">
  <h5 id="userDeleteHeading">Delete User</h5>
  <p>Are you sure you want to permanently delete <strong id="userDeleteName"></strong>?</p>
  <p class="text-danger small">This cannot be undone.</p>
  <div id="userDeleteError" class="alert alert-danger d-none" role="alert"></div>
  <div class="d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-secondary" id="userDeleteCancel">Cancel</button>
    <button type="button" class="btn btn-danger" id="userDeleteConfirm">Delete User</button>
  </div>
</dialog>
@push('styles')<style>
.permission-grid th:first-child {min-width:125px}.permission-grid td {text-align:center}.user-edit-dialog {width:min(94vw,520px);max-height:90dvh;overflow-y:auto;border:0;border-radius:12px;padding:24px;box-shadow:0 20px 60px #0004}
.user-edit-dialog::backdrop{background:#11182799}.user-table-avatar{object-fit:cover;border-radius:50%}

.password-field {
    position: relative;
}
.password-field .form-control {
    padding-inline-end: 3rem;
}
.password-toggle {
    position: absolute;
    inset-inline-end: 0.25rem;
    top: 50%;
    transform: translateY(-50%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2rem;
    padding: 0;
    color: #475569;
}
.password-toggle .password-eye-slash {
    display: none;
}
.password-toggle[aria-pressed="true"] .password-eye-slash {
    display: inline;
}
</style>@endpush
@push('scripts')<script>
(()=>{
 const dialog=document.getElementById('usersEditDialog'), form=document.getElementById('usersEditForm');
 const feedback=document.getElementById('usersEditMessage'), save=document.getElementById('usersEditSave');
 let action='', row;
 document.querySelectorAll('.js-user-edit').forEach(btn=>btn.addEventListener('click',()=>{
   action=btn.dataset.url;row=btn.closest('tr');form.reset();feedback.classList.add('d-none');
   form.elements.namedItem('name').value=btn.dataset.name;
   form.elements.namedItem('email').value=btn.dataset.email;
   const allowed=new Set(JSON.parse(btn.dataset.permissions || '[]'));
   form.querySelectorAll('.editPerm-checkbox').forEach(input=>{input.checked=allowed.has(input.value)});
   dialog.showModal();
 }));
 const close=()=>dialog.close();
 document.getElementById('usersEditCancel').addEventListener('click',close);
 document.getElementById('usersEditClose').addEventListener('click',close);
 form.addEventListener('submit',async e=>{
  e.preventDefault();feedback.classList.add('d-none');save.disabled=true;
  try {
   const body=new FormData(form);body.append('_method','PUT');
   const res=await fetch(action,{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body});
   const data=await res.json().catch(()=>({}));
   if(!res.ok)throw new Error(data.errors?Object.values(data.errors).flat().join('\n'):(data.message||'Update failed'));
   row.cells[1].textContent=data.user.name;row.cells[2].textContent=data.user.email;
   const btn=row.querySelector('.js-user-edit');btn.dataset.name=data.user.name;btn.dataset.email=data.user.email;btn.dataset.permissions=JSON.stringify(data.user.permissions || []);
   if(data.user.avatar_url){row.cells[0].replaceChildren(Object.assign(document.createElement('img'),{src:data.user.avatar_url,alt:'',className:'user-table-avatar',width:40,height:40}));}
   close();
  }catch(error){feedback.textContent=error.message;feedback.classList.remove('d-none')}
  finally{save.disabled=false}
 });
})();
</script>@endpush
@push('scripts')
<script>
(() => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  const headers = {'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrf};
  async function send(url, method) {
    const response = await fetch(url,{method,headers,credentials:'same-origin'});
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.message || 'Operation failed.');
    return data;
  }
  for(const form of document.querySelectorAll('.js-user-action')) {
    form.addEventListener('submit',async event=>{
      event.preventDefault();
      const button=form.querySelector('button'); button.disabled=true;
      try {
        const data=await send(form.action,'PATCH');
        const row=form.closest('tr');
        const status=row.querySelector('.user-status');
        status.replaceChildren();
        const badge=document.createElement('span');
        badge.className='badge '+(data.is_active?'bg-success':'bg-secondary');
        badge.textContent=data.is_active?'Active':'Inactive';
        status.appendChild(badge);
        button.textContent=data.is_active?'Deactivate':'Activate';
      } catch(error){alert(error.message)} finally{button.disabled=false}
    });
  }
  const dialog=document.getElementById('userDeleteDialog');
  const feedback=document.getElementById('userDeleteError');
  const confirm=document.getElementById('userDeleteConfirm');
  let target=null;
  for(const button of document.querySelectorAll('.js-user-delete')) {
    button.addEventListener('click',()=>{
      target=button; feedback.classList.add('d-none');
      document.getElementById('userDeleteName').textContent=button.dataset.name;
      dialog.showModal();
    });
  }
  document.getElementById('userDeleteCancel').addEventListener('click',()=>dialog.close());
  confirm.addEventListener('click',async()=>{
    if(!target)return;
    confirm.disabled=true;
    try {
      await send(target.dataset.url,'DELETE');
      target.closest('tr').remove();dialog.close();
    } catch(error){feedback.textContent=error.message;feedback.classList.remove('d-none')}
    finally {confirm.disabled=false}
  });
})();
</script>
@endpush
@push('scripts')
<script>
document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    const input = document.getElementById(button.dataset.togglePassword);

    button.addEventListener('click', function () {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';

        button.setAttribute('aria-pressed', String(show));
    });
});
</script>
@endpush
@endsection
