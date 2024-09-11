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

<li class="nav-item">
    <a href="{{ route('users.attendance-index') }}"
       class="nav-link {{ Request::is('users.attendance-index*') ? 'active' : '' }}">
       <i class="fas fa-fingerprint nav-icon"></i>
        <p>Attendance</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.personal-info') }}"
       class="nav-link {{ Request::is('users.personal-info*') ? 'active' : '' }}">
       <i class="fas fa-info-circle nav-icon"></i>
        <p>Personal Info</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.staff-management') }}"
       class="nav-link {{ Request::is('users.staff-management*') ? 'active' : '' }}">
       <i class="fas fa-users nav-icon"></i>
        <p>Staff & Subordinates</p>
    </a>
</li>

{{-- <li class="nav-item">
    <a href="{{ route('taskHeads.kanban') }}"
       class="nav-link {{ Request::is('taskHeads.kanban*') ? 'active' : '' }}">
       <i class="fas fa-tasks nav-icon"></i>
        <p>Task Master</p>
    </a>
</li> --}}

{{-- APPROVALS --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-check-circle"></i>
        <p>
            My Approvals
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @canany('god permission') --}}
        <li class="nav-item">
            <a href="{{ route('leaveApplications.my-approvals') }}"
               class="nav-link {{ Request::is('leaveApplications.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-file-export nav-icon"></i><p>Leave Applications</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('travelOrders.my-approvals') }}"
               class="nav-link {{ Request::is('travelOrders.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-plane-departure nav-icon"></i><p>Travel Orders</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tripTickets.my-approvals') }}"
               class="nav-link {{ Request::is('tripTickets.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-car nav-icon"></i><p>Trip Tickets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('offsetApplications.my-approvals') }}"
               class="nav-link {{ Request::is('offsetApplications.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-calendar-minus nav-icon"></i><p>Offsets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('attendanceConfirmations.my-approvals') }}"
               class="nav-link {{ Request::is('attendanceConfirmations.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-fingerprint nav-icon"></i><p>Attendance Confirmation</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('overtimes.my-approvals') }}"
               class="nav-link {{ Request::is('overtimes.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-clock nav-icon"></i><p>Overtime</p>
            </a>
        </li>
    </ul>
</li>