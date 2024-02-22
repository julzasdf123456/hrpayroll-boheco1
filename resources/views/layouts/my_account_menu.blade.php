@php
    $empId = Auth::user()->employee_id;
@endphp
<li class="nav-item">
    <a href="{{ route('users.my-account-index', [$empId]) }}"
       class="nav-link {{ Request::is('users.my-account-index*') ? 'active' : '' }}">
       <i class="fas fa-user-circle nav-icon"></i>
        <p>Account Home</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.leave-credits', [$empId]) }}"
       class="nav-link {{ Request::is('users.leave-credits*') ? 'active' : '' }}">
       <i class="fas fa-leaf nav-icon"></i>
        <p>Leave Credits</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.payroll-dashboard') }}"
       class="nav-link {{ Request::is('users.payroll-dashboard*') ? 'active' : '' }}">
       <i class="fas fa-coins nav-icon"></i>
        <p>Payroll</p>
    </a>
</li>