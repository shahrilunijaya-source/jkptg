@if(filament()->auth()->check())
@php
    $user = filament()->auth()->user();
    $initials = strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', trim($user->name ?? 'U')))), 0, 2));
    $roleName = $user->roles?->first()?->name ?? 'Admin';
@endphp
<div class="fi-sidebar-user-footer">
    <div class="fi-sf-user-link" style="cursor:default">
        <div class="fi-sf-avatar">{{ $initials }}</div>
        <div class="fi-sf-info">
            <div class="fi-sf-name">{{ $user->name }}</div>
            <div class="fi-sf-role">{{ $roleName }}</div>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;flex-shrink:0">
        @csrf
        <button type="submit" class="fi-sf-logout" title="Log Keluar">
            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
</div>
@endif
