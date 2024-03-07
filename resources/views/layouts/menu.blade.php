@canany('god permission')
{{-- EMPLOYEES --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-user-circle"></i>
        <p>
            Employees
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('employees.index') }}"
               class="nav-link {{ Request::is('employees.index*') ? 'active' : '' }}">
               <i class="fas fa-list nav-icon"></i>
                <p>Browse</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('employees.create') }}"
               class="nav-link {{ Request::is('employees.create*') ? 'active' : '' }}">
               <i class="fas fa-user-plus nav-icon"></i>
                <p>Add Employee</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('employeePayrollSchedules.index') }}"
               class="nav-link {{ Request::is('employeePayrollSchedules.index*') ? 'active' : '' }}">
               <i class="fas fa-cogs nav-icon"></i>
                <p>Employee Configurations</p>
            </a>
        </li>  
        
        <li class="nav-item">
            <a href="{{ route('leaveBalances.batch-edit') }}"
               class="nav-link {{ Request::is('leaveBalances.batch-edit*') ? 'active' : '' }}">
               <i class="fas fa-sort-amount-down nav-icon"></i>
                <p>Leave Balance Conf.</p>
            </a>
        </li>  
    </ul>
</li>
@endcanany


{{-- LEAVE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>
            Manual Entries
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('leaveApplications.manual-entries') }}"
               class="nav-link {{ Request::is('leaveApplications.manual-entries*') ? 'active' : '' }}">
                <i class="fas fa-file-export nav-icon"></i><p>Leave</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tripTickets.manual-entry') }}"
               class="nav-link {{ Request::is('tripTickets.manual-entry*') ? 'active' : '' }}">
                <i class="fas fa-plane-departure nav-icon"></i><p>Trip Tickets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('offsetApplications.manual-entry') }}"
               class="nav-link {{ Request::is('offsetApplications.manual-entry*') ? 'active' : '' }}">
                <i class="fas fa-random nav-icon"></i><p>Offsets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('overtimes.manual-entry') }}"
               class="nav-link {{ Request::is('overtimes.manual-entry*') ? 'active' : '' }}">
                <i class="fas fa-user-clock nav-icon"></i><p>Overtimes</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('attendanceConfirmations.manual-entry') }}"
               class="nav-link {{ Request::is('attendanceConfirmations.manual-entry*') ? 'active' : '' }}">
                <i class="fas fa-fingerprint nav-icon"></i><p>Attendance Confirmation</p>
            </a>
        </li>
    </ul>
</li>

{{-- TRIP TICKETS --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-car"></i>
        <p>
            Trip Tickets
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('tripTickets.create') }}" class="nav-link {{ Request::is('tripTickets.create') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plus-circle"></i>
                <p>File Trip Ticket</p>
            </a>
        </li>
        @canany(['create grs', 'god permission'])
            <li class="nav-item">
                <a href="{{ route('tripTicketGRS.grs-requests') }}" class="nav-link {{ Request::is('tripTicketGRS.grs-requests') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-gas-pump"></i>
                    <p>GRS Requests</p>
                </a>
            </li>
        @endcanany
        @canany(['log vehicle departures', 'god permission'])
            <li class="nav-item">
                <a href="{{ route('tripTickets.log-vehicle-trips') }}" class="nav-link {{ Request::is('tripTickets.log-vehicle-trips') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plane-departure"></i>
                    <p>Vehicle Departures</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tripTickets.log-vehicle-arrivals') }}" class="nav-link {{ Request::is('tripTickets.log-vehicle-arrivals') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plane-arrival"></i>
                    <p>Vehicle Arrivals</p>
                </a>
            </li>
        @endcanany
    </ul>
</li>

{{-- APPROVALS --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-check-circle"></i>
        <p>
            Admin Approvals
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @canany('god permission') --}}
        @canany('approve leave conversion', 'god permission')
        <li class="nav-item">
            <a href="{{ route('leaveConversions.my-approvals') }}"
               class="nav-link {{ Request::is('leaveConversions.my-approvals*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt nav-icon"></i><p>Leave Conversions</p>
            </a>
        </li>
        @endcanany
        {{-- @endcanany --}}
    </ul>
</li>

@canany('god permission')
{{-- PAYROLL --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-receipt"></i>
        <p>
            Payroll
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('payrollIndices.index') }}"
               class="nav-link {{ Request::is('payrollIndices*') ? 'active' : '' }}">
               <i class="fas fa-money-check-alt nav-icon"></i>
                <p>All Payroll</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('payrollIndices.payroll') }}"
               class="nav-link {{ Request::is('payrollIndices.payroll*') ? 'active' : '' }}">
               <i class="fas fa-wallet nav-icon"></i>
                <p>Generate Payroll</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payrollIndices.payroll-audit') }}"
               class="nav-link {{ Request::is('payrollIndices.payroll-audit*') ? 'active' : '' }}">
               <i class="fas fa-search-dollar nav-icon"></i>
                <p>Audit Payroll</p>
            </a>
        </li>
        <div class="divider"></div>
        <li class="nav-item" title="Contributions and allowances">
            <a href="{{ route('employeePayrollSundries.contributions') }}" class="nav-link {{ Request::is('employeePayrollSundries.contributions*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tags"></i>
                <p>Contributions & Allws.</p>
            </a>
        </li>
    </ul>
</li>
@endcanany

@canany('god permission')
{{-- INCENTIVES SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-coins"></i>
        <p>
            Incentives & Bonuses
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('incentives.index') }}" class="nav-link {{ Request::is('incentives.index*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-eye"></i>
                <p>View All Incentives</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('incentives.thirteenth-month-pay') }}" class="nav-link {{ Request::is('incentives.thirteenth-month-pay*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-comments-dollar"></i>
                <p>New 13th Month</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('incentives.other-bonuses') }}" class="nav-link {{ Request::is('incentives.other-bonuses*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-usd"></i>
                <p>New Other Bonuses</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('incentives.year-end-bonuses') }}" class="nav-link {{ Request::is('incentives.year-end-bonuses*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-gift"></i>
                <p>New Year-end Bonuses</p>
            </a>
        </li>
        <div class="divider"></div>
        <li class="nav-item">
            <a href="{{ route('leaveConversions.approved-sl-and-vl') }}" class="nav-link {{ Request::is('leaveConversions.approved-sl-and-vl*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-exchange-alt"></i>
                <p>VL & SL Conversions</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('incentivesAnnualProjections.index') }}" class="nav-link {{ Request::is('incentivesAnnualProjections*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-funnel-dollar"></i>
                <p>Incntvs Annual Projection</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('employeeIncentiveAnnualProjections.incentive-withholding-taxes') }}" class="nav-link {{ Request::is('employeeIncentiveAnnualProjections.incentive-withholding-taxes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Employees Incntv WTs</p>
            </a>
        </li>
    </ul>
</li>
@endcanany

{{-- DEDUCTIONS AND LOANS --}}
@canany('god permission')
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-minus-circle"></i>
        <p>
            Loans & Deductions
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('loans.pag-ibig') }}" class="nav-link {{ Request::is('loans.pag-ibig*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Pag-Ibig Loans</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('loans.sss') }}" class="nav-link {{ Request::is('loans.sss*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-weight"></i>
                <p>SSS Loans</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('loans.motorcycle') }}" class="nav-link {{ Request::is('loans.motorcycle*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-motorcycle"></i>
                <p>Motorcycle Loans</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('loans.other-loans') }}" class="nav-link {{ Request::is('loans.other-loans*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list-ul"></i>
                <p>Other Loans/Ammort.</p>
            </a>
        </li>
        <div class="divider"></div>
        <li class="nav-item">
            <a href="{{ route('otherPayrollDeductions.index') }}" class="nav-link {{ Request::is('otherPayrollDeductions*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-minus"></i>
                <p>Payroll AR-Others</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('otherPayrollDeductions.addons-and-deductions') }}" class="nav-link {{ Request::is('otherPayrollDeductions.addons-and-deductions*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-minus-circle"></i>
                <p>Other Adds./Deducts.</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bempcs.index') }}" class="nav-link {{ Request::is('bempcs*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-handshake"></i>
                <p>BEMPC</p>
            </a>
        </li>
        <div class="divider"></div>
        <li class="nav-item" title="Contributions and allowances">
            <a href="{{ route('payrollIndices.withholding-taxes') }}" class="nav-link {{ Request::is('payrollIndices.withholding-taxes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Withholding Taxes</p>
            </a>
        </li>
    </ul>
</li>
@endcanany

@canany('god permission')
{{-- SETTINGS SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-cogs"></i>
        <p>
            Settings
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('towns.index') }}"
               class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
               <i class="fas fa-map-marker-alt nav-icon"></i>
                <p>Towns</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('barangays.index') }}"
               class="nav-link {{ Request::is('barangays*') ? 'active' : '' }}">
               <i class="fas fa-map-marker-alt nav-icon"></i>
                <p>Barangays</p>
            </a>
        </li>

        <div class="divider"></div>

        <li class="nav-item">
            <a href="{{ route('holidaysLists.index') }}" class="nav-link {{ Request::is('holidaysLists*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-circle"></i>
                <p>Holidays Lists</p>
            </a>
        </li> 
        <li class="nav-item">
            <a href="{{ route('dayOffSchedules.index') }}" class="nav-link {{ Request::is('dayOffSchedules*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-times"></i>
                <p>Day Off Schedules</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('payrollSchedules.index') }}"
               class="nav-link {{ Request::is('payrollSchedules*') ? 'active' : '' }}">
               <i class="fas fa-circle nav-icon"></i>
                <p>Payroll Schedules</p>
            </a>
        </li>  
        <li class="nav-item">
            <a href="{{ route('specialDutyDays.index') }}" class="nav-link {{ Request::is('specialDutyDays*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-day"></i>
                <p>Special Duty Days</p>
            </a>
        </li>

        <div class="divider"></div>

        <li class="nav-item">
            <a href="{{ route('positions.index') }}"
               class="nav-link {{ Request::is('positions*') ? 'active' : '' }}">
               <i class="fas fa-circle nav-icon"></i>
                <p>Positions</p>
            </a>
        </li> 

        <li class="nav-item">
            <a href="{{ route('biometricUsers.index') }}"
               class="nav-link {{ Request::is('biometricUsers.index*') ? 'active' : '' }}">
               <i class="fas fa-fingerprint nav-icon"></i>
                <p>Biometric Users</p>
            </a>
        </li> 

        <li class="nav-item">
            <a href="{{ route('vehicles.index') }}" class="nav-link {{ Request::is('vehicles*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-car"></i>
                <p>Vehicles</p>
            </a>
        </li>
    </ul>
</li>
@endcanany

@canany('god permission')
{{-- INFO MAKER SIDE --}}
<li class="nav-item">
    <a href="{{ route('notifications.create') }}"
       class="nav-link {{ Request::is('notifications.create*') ? 'active' : '' }}">
       <i class="fas fa-info-circle nav-icon"></i>
        <p>Info Maker</p>
    </a>
</li>
@endcanany

@canany('god permission')
{{-- ADMIN SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-shield-alt"></i>
        <p>
            Administrative
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
               <i class="fas fa-user-secret nav-icon"></i>
                <p>Users</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}"
               class="nav-link {{ Request::is('permissions*') ? 'active' : '' }}">
               <i class="fas fa-key nav-icon"></i>
                <p>Permissions</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('roles.index') }}"
               class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
               <i class="fas fa-key nav-icon"></i>
                <p>Roles</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('biometricDevices.index') }}"
               class="nav-link {{ Request::is('biometricDevices*') ? 'active' : '' }}">
               <i class="fas fa-fingerprint nav-icon"></i>
                <p>Biometric Devices</p>
            </a>
        </li>
    </ul>
</li>
@endcanany
