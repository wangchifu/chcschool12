<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $active[1] }}" 
           {!! ($active[1] == 'active') ? 'aria-current="page"' : '' !!} 
           href="{{ route('users.index') }}">
           在職教職
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[2] }}" 
           {!! ($active[2] == 'active') ? 'aria-current="page"' : '' !!} 
           href="{{ route('users.leave') }}">
           離職教職
        </a>
    </li>
</ul>