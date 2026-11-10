@extends('layouts.app')
@section('title', 'Users')
@section('content')
@error('role')<div class="alert error-alert">{{ $message }}</div>@enderror
<x-data-table title="Users" :paginator="$users" :columns="5" search="Search users..." empty="No users found.">
    <x-slot:head>
        <tr><th>Name</th><th>Email</th><th>Role</th><th>Registered</th><th class="r">Actions</th></tr>
    </x-slot:head>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }} @if ($user->is(auth()->user()))<span class="badge">You</span>@endif</td>
            <td>{{ $user->email }}</td>
            <td>
                @if ($user->is(auth()->user()))
                    <span class="badge completed">{{ $user->role }}</span>
                @else
                    <form method="POST" action="{{ route('users.update', $user) }}" class="inline-form">
                        @csrf @method('PUT')
                        <select name="role" onchange="this.form.submit()" title="Change role" aria-label="Change role">
                            @foreach ([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_STAFF] as $role)
                                <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </td>
            <td>{{ $user->created_at?->format('M d, Y') }}</td>
            <td class="actions">
                @unless ($user->is(auth()->user()))
                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<x-action-button type="delete" title="Delete user" /></form>
                @endunless
            </td>
        </tr>
    @endforeach
</x-data-table>
@endsection
