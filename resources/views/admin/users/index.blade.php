@extends('layouts.dashboard')

@section('title', 'Admin - Manage Users')

@section('content')
<div class="min-h-screen bg-slate-50/50 p-4 sm:p-8 pt-20 md:pt-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-users"></i>
                    </div>
                    Manage Users
                </h1>
                <p class="text-sm text-slate-500 mt-1">View and modify user roles or manually add new accounts to the platform.</p>
            </div>
            <button onclick="openAddUserModal()" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Add New User
            </button>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="p-4 pl-6">User</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Joined</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex flex-shrink-0 items-center justify-center overflow-hidden">
                                        @if($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs font-bold text-slate-500">{{ substr($user->first_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="font-bold text-sm text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="p-4 text-sm text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border
                                    @if($user->role === 'Admin') bg-purple-50 text-purple-600 border-purple-100
                                    @elseif($user->role === 'Funder') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @else bg-blue-50 text-blue-600 border-blue-100 @endif">
                                    {{ $user->role ?? 'Citer' }}
                                </span>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <select onchange="openRoleConfirmModal({{ $user->id }}, this.value, this)" data-original-role="{{ $user->role ?? 'Citer' }}" class="text-xs border border-slate-200 rounded-lg px-2 py-1 bg-white text-slate-600 font-medium focus:outline-none focus:border-blue-500 cursor-pointer" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="Citer" {{ ($user->role ?? 'Citer') === 'Citer' ? 'selected' : '' }}>Citer</option>
                                    <option value="Funder" {{ $user->role === 'Funder' ? 'selected' : '' }}>Funder</option>
                                    <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->isEmpty())
                <div class="text-center py-10">
                    <p class="text-slate-400 font-medium">No users found.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="flex justify-center">
            <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0 overflow-hidden" id="addUserModalContent">
        <form id="addUserForm" onsubmit="submitNewUser(event)">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Add New User</h3>
                    <button type="button" onclick="closeAddUserModal()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 block">First Name</label>
                            <input type="text" id="new_first_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 block">Last Name</label>
                            <input type="text" id="new_last_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 block">Email Address</label>
                        <input type="email" id="new_email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 block">Password</label>
                        <input type="password" id="new_password" required minlength="8" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 block">Assign Role</label>
                        <select id="new_role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium appearance-none cursor-pointer">
                            <option value="Citer">Citer</option>
                            <option value="Funder">Funder</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" id="submitUserBtn" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Create User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Confirm Role Change Modal -->
<div id="confirmRoleModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0 overflow-hidden" id="confirmRoleModalContent">
        <div class="p-8 text-center text-slate-800">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Change User Role?</h3>
            <p class="text-sm text-slate-500 mb-8 font-medium">Are you sure you want to change this user's role to <strong id="confirmRoleName" class="text-slate-900"></strong>?</p>
            
            <div class="flex gap-4">
                <button onclick="closeRoleConfirmModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button onclick="executeRoleChange()" id="confirmRoleBtn" class="flex-1 py-3 px-4 bg-amber-500 text-white rounded-xl font-bold shadow-lg shadow-amber-100 hover:bg-amber-600 active:scale-95 transition-all">
                    Yes, Change It
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show/Hide Add User Modal
    function openAddUserModal() {
        const modal = document.getElementById('addUserModal');
        const content = document.getElementById('addUserModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAddUserModal() {
        const modal = document.getElementById('addUserModal');
        const content = document.getElementById('addUserModalContent');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('addUserForm').reset();
        }, 300);
    }

    // Role Change Confirmation Logic
    let pendingUserId = null;
    let pendingRole = null;
    let pendingSelectElement = null;

    function openRoleConfirmModal(userId, newRole, selectElement) {
        pendingUserId = userId;
        pendingRole = newRole;
        pendingSelectElement = selectElement;
        
        document.getElementById('confirmRoleName').textContent = newRole;
        
        const modal = document.getElementById('confirmRoleModal');
        const content = document.getElementById('confirmRoleModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRoleConfirmModal() {
        const modal = document.getElementById('confirmRoleModal');
        const content = document.getElementById('confirmRoleModalContent');
        
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        // Revert the select element to its original value if cancelled
        if (pendingSelectElement) {
            pendingSelectElement.value = pendingSelectElement.getAttribute('data-original-role');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            pendingUserId = null;
            pendingRole = null;
            pendingSelectElement = null;
        }, 300);
    }

    async function executeRoleChange() {
        if (!pendingUserId || !pendingRole) return;
        
        const btn = document.getElementById('confirmRoleBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        btn.disabled = true;

        try {
            const response = await fetch(`/admin/users/${pendingUserId}/role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role: newRole })
            });
            const data = await response.json();
            
            if (data.success) {
                if(typeof window.showToast === 'function') {
                    window.showToast(data.message, false);
                } else {
                    alert(data.message);
                }
                setTimeout(() => location.reload(), 1000);
            } else {
                if(typeof window.showToast === 'function') {
                    window.showToast(data.message || 'Failed to update role.', 'error');
                } else {
                    alert(data.message || 'Failed to update role.');
                }
                closeRoleConfirmModal();
            }
        } catch (error) {
            console.error(error);
            if(typeof window.showToast === 'function') {
                window.showToast('An error occurred.', 'error');
            } else {
                alert('An error occurred.');
            }
            closeRoleConfirmModal();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // Handle New User Creation
    async function submitNewUser(event) {
        event.preventDefault();
        
        const btn = document.getElementById('submitUserBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        btn.disabled = true;

        const payload = {
            first_name: document.getElementById('new_first_name').value.trim(),
            last_name: document.getElementById('new_last_name').value.trim(),
            email: document.getElementById('new_email').value.trim(),
            password: document.getElementById('new_password').value,
            role: document.getElementById('new_role').value
        };

        try {
            const response = await fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            const data = await response.json();
            
            if (data.success) {
                if(typeof window.showToast === 'function') {
                    window.showToast(data.message, false);
                } else {
                    alert(data.message);
                }
                closeAddUserModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                // Formatting validation errors
                let errorMsg = data.message || 'Registration failed.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).map(e => e.join(' ')).join('\n');
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error(error);
            alert('An error occurred.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>
@endpush
