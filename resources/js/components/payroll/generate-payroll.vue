<template>
    <div class="card shadow-none" style="margin-top: 10px;">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    <span class="text-muted">Employee Type</span>
                    <select v-model="employeeType" class="form-control form-control-sm">
                        <option value="Regular">Regular</option>
                        <option value="Probationary">Probationary</option>
                        <option value="Contractual">Contractual</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <span class="text-muted">Department</span>
                    <select v-model="department" class="form-control form-control-sm">
                        <option value="ESD">ESD</option>
                        <option value="ISD">ISD</option>
                        <option value="OGM">OGM</option>
                        <option value="OSD">OSD</option>
                        <option value="PGD">PGD</option>
                        <option value="SEEAD">SEEAD</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <span class="text-muted">Salary Period</span>
                    <select v-model="salaryPeriod" class="form-control form-control-sm">
                        <option value="{{ fifteenth }}">{{ moment(fifteenth).format('MMMM DD, YYYY') }}</option>
                        <option value="{{ thirtieth }}">{{ moment(thirtieth).format('MMMM DD, YYYY') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <span class="text-muted">From</span>
                    <flat-pickr v-model="from" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                </div>
                <div class="col-lg-2">
                    <span class="text-muted">To</span>
                    <flat-pickr v-model="to" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                </div>
                <div class="col-lg-3">
                    <span class="text-muted">Action</span><br>
                    <button class="btn btn-primary btn-sm" @click="generate()"><i class="fas fa-check-circle ico-tab-mini"></i>Generate Payroll</button>
                </div>
            </div>
            <!-- <span class="text-muted"><strong>Ask Reeve about Something</strong></span>
            <input id="prompt" v-model="prompt" v-on:keyup.enter="ask()" class="form-control" autofocus placeholder="Type anything..."/>

            <div id="loader" class="spinner-border text-success gone" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <button id="go-btn" class="btn btn-primary btn-sm float-right" @click="ask()" style="margin-top: 5px;"><i class="fas fa-check-circle ico-tab-mini"></i>Go Ask Reeve</button> -->
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-sm table-bordered" id="response">
            <thead>
                <!-- <tr>
                    <th class='text-center' rowspan="2">Employees</th>
                    <th class='text-center' colspan="2" v-for="column in dateHeaders" :key="column.name">{{ column.label }}</th>
                </tr>
                <tr>
                    <th class='text-center' v-for="addCols in dateSubHeaders">{{ addCols }}</th>
                </tr>                 -->
                <th class='text-center' style="width: 240px;">Employees</th>
                <th class='text-center' v-for="column in dateHeaders" :key="column.name">{{ column.label }}</th>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in employees" :key="employee.id">
                    <td>{{ employee.name }}</td>
                    <td v-for="attendance in attendances[index]">{{ attendance }}</td> 
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'GeneratePayroll.generate-payroll',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            fifteenth : moment().format('YYYY-MM-15'),
            thirtieth : moment().format('YYYY-MM-30'),
            moment : moment,
            selectedDate: null,
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
                // You can configure more options here as per Flatpickr documentation
            },
            // PAYROLL DATA
            employeeType : '',
            department : '',
            salaryPeriod : '',
            from : '',
            to : '',
            // TABLE COLUMNS
            dateHeaders: [],
            // TABLE DATA
            employees : [],
            attendances : [],
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        getInBetweenDates(from, to) {
            let startDate = moment(from);
            const lastDate = moment(to);

            // ADD DUTY DATES COLUMN
            while (startDate <= lastDate) {
                this.dateHeaders.push({
                    name : moment(startDate).format('YYYY-MM-DD'),
                    label : moment(startDate).format('MMM-DD-YY dddd')
                }); 
                startDate = moment(startDate).add(1, 'days');
            }

            // ADD OT COlUMN
            this.dateHeaders.push({
                name : 'Overtime Hours',
                label : 'Overtime Hours'
            });

            // ADD TOTAL COLUMN
            this.dateHeaders.push({
                name : 'Total Hours Rendered',
                label : 'Total Hours Rendered'
            }); 
        },
        isIn(timeToCheck, scheduleMedianTimestamp) {
            var from = moment(scheduleMedianTimestamp).subtract(2, 'hours').format('YYYY-MM-DD HH:mm:ss');
            var to = moment(scheduleMedianTimestamp).add(2, 'hours').format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBetween(from, to);
        },
        isInMedianOut(timeToCheck, scheduleMedianTimestamp) {
            var from = moment(scheduleMedianTimestamp).subtract(2, 'hours').format('YYYY-MM-DD HH:mm:ss');
            var to = moment(scheduleMedianTimestamp).add(1, 'hour').format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBetween(from, to);
        },
        isInMedianIn(timeToCheck, scheduleMedianTimestamp) {
            var from = moment(scheduleMedianTimestamp).subtract(1, 'hours').format('YYYY-MM-DD HH:mm:ss');
            var to = moment(scheduleMedianTimestamp).add(2, 'hours').format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBetween(from, to);
        },
        isBetweenTimesIn(timeToCheck, scheduleMedianTimestamp) {
            var from = moment(scheduleMedianTimestamp).subtract(2, 'hours');
            var to = scheduleMedianTimestamp.add(5, 'minutes');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBetween(from.format('YYYY-MM-DD HH:mm:ss'), to.format('YYYY-MM-DD HH:mm:ss'));
        },
        isBefore(timeToCheck, scheduleMedianTimestamp) {
            scheduleMedianTimestamp = moment(scheduleMedianTimestamp).format('YYYY-MM-DD HH:mm:ss');
            scheduleMedianTimestamp = moment(scheduleMedianTimestamp).add(5, 'minutes').format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBefore(scheduleMedianTimestamp);
        },
        isBeforeAbs(timeToCheck, scheduleMedianTimestamp) {
            scheduleMedianTimestamp = moment(scheduleMedianTimestamp).format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBefore(scheduleMedianTimestamp);
        },
        isAfter(timeToCheck, scheduleMedianTimestamp) {
            scheduleMedianTimestamp = moment(scheduleMedianTimestamp).format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isAfter(scheduleMedianTimestamp);
        },
        getHoursFour(from, to) {
            from = moment(from).format('YYYY-MM-DD HH:mm:ss');
            to = moment(to).format('YYYY-MM-DD HH:mm:ss');
            const duration = moment.duration(moment(to).diff(from));

            var totalHrs = Math.round((duration.asHours() + Number.EPSILON) * 100) / 100;

            if (totalHrs >= 4) {
                return 4;
            } else {
                return totalHrs;
            }
        },
        getHours(from, to) {
            from = moment(from).format('YYYY-MM-DD HH:mm:ss');
            to = moment(to).format('YYYY-MM-DD HH:mm:ss');
            const duration = moment.duration(moment(to).diff(from));

            var totalHrs = Math.round((duration.asHours() + Number.EPSILON) * 100) / 100;

            if (totalHrs >= 8) {
                return 8;
            } else {
                return totalHrs;
            }
        },
        getHoursInHours(from, to) {
            from = moment(from, 'HH:mm:ss');
            to = moment(to, 'HH:mm:ss');
            const duration = moment.duration(moment(to).diff(from));

            return Math.round((duration.asHours() + Number.EPSILON) * 100) / 100;
        },
        isWeekend(dateToCheck) {
            dateToCheck = moment(dateToCheck);
            return (dateToCheck.day() === 6 | dateToCheck.day() === 0);
        },
        assessAttendanceHours(attendanceData, scheduleArray, date) {
            var attSize = attendanceData.length;
            var startIn = null;
            var breakOut = null;
            var breakIn = null;
            var endOut = null;
            var amHours = 0;
            var pmHours = 0;

            if (this.isNull(scheduleArray['BreakStart']) | this.isNull(scheduleArray['BreakEnd'])) {
                /**
                     *  =======================================================================================
                     *  IF scheduleArray has no breaks (schedule totals exactly 8 hours with no median break)
                     *  =======================================================================================
                     */
                var startCheck = false;
                var endCheck = false;

                // ANALYZE LOGS
                for(var i=0; i<attSize; i++) {
                    // FILTER TIME IN
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                        if (this.isNull(startIn)) {
                            startIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isBefore(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                                startCheck = true;
                            } else {
                                startCheck = false;
                            }
                        }
                    }   
                    
                    // FILTER TIME OUT
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                        if (this.isNull(endOut)) {
                            endOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isAfter(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                                endCheck = true;
                            } else {
                                endCheck = false;
                            }
                        }
                    } 
                } 

                if (startCheck && endCheck) {
                    // IF BOTH HAS COMPLETE TIME INS AND OUTS
                    return 4;
                } else if (startCheck && !endCheck) {
                    // IF TIMEOUT UNDERTIME, OR HALF DAY
                    // GET END OUT FIRST
                    if (attSize > 1) {
                        return this.getHoursFour(startIn, moment(attendanceData[attSize-1]['Timestamp']).format('YYYY-MM-DD HH:mm:ss'))
                    } else {
                        return 'xTI/xTO';
                    }                    
                } else if (!startCheck && endCheck) {
                    // IF TIME IN IS LATE, OR HALF DAY
                    // GET IN FIRST
                    if (attSize > 1) {
                        return this.getHoursFour(moment(attendanceData[0]['Timestamp']).format('YYYY-MM-DD HH:mm:ss'), endOut);
                    } else {
                        return 'xTI/xTO';
                    }
                } else {
                    return ''; //absent
                }
            } else {
                 /**
                 *  =======================================================================================
                 *  IF scheduleArray is complete with noon/median breaks
                 *  =======================================================================================
                 */
                var startCheck = false;
                var breakStartCheck = false;
                var breakEndCheck = false;
                var endCheck = false;

                // ANALYZE LOGS
                for(var i=0; i<attSize; i++) {
                    // FILTER TIME IN
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                        if (this.isNull(startIn)) {
                            startIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isBefore(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                                startCheck = true;
                            } else {
                                startCheck = false;
                            }
                        }
                    } 
                    
                    // FILTER TIME OUT MEDIAN/NOON BREAK
                    if (this.isInMedianOut(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                        if (this.isNull(breakOut)) {
                            breakOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isAfter(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                                breakStartCheck = true;
                            } else {
                                breakStartCheck = false;
                            }
                        }
                    } 

                    // FILTER TIME IN MEDIAN/NOON BREAK
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                        if (this.isNull(breakIn)) {
                            breakIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isBefore(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                                breakEndCheck = true;
                            } else {
                                breakEndCheck = false;
                            }
                        }
                    }
                    
                    // FILTER TIME OUT
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                        if (this.isNull(endOut)) {
                            endOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isAfter(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                                endCheck = true;
                            } else {
                                endCheck = false;
                            }
                        }
                    } 
                }

                // GET AM HOURS 
                if (startCheck && breakStartCheck) {
                    amHours = 4;
                } else if (startCheck && !breakStartCheck) {
                    // IF MORNING/FIRST SET UNDERTIME, OR HALF DAY
                    // GET END OUT FIRST
                    var bOut = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT MEDIAN/NOON BREAK
                        if (this.isInMedianOut(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                            if (this.isNull(bOut)) {
                                bOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(bOut)) {
                        amHours = this.getHoursFour(startIn, moment(bOut).format('YYYY-MM-DD HH:mm:ss'));
                    } else {
                        amHours = 0;
                    }
                } else if (!startCheck && breakStartCheck) {
                    // IF MORNING/FIRST LATE, OR HALF DAY
                    // GET START IN FIRST
                    var tIn = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT MEDIAN/NOON BREAK
                        if (this.isBeforeAbs(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                            if (this.isNull(tIn)) {
                                tIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(tIn)) {
                        amHours = this.getHoursFour(moment(tIn).format('YYYY-MM-DD HH:mm:ss'), (date + " " + scheduleArray['BreakStart']));
                    } else {
                        amHours = 0;
                    }
                } else {
                    amHours = 0;
                }

                // GET PM HOURS
                if (breakEndCheck && endCheck) {
                    pmHours = 4;
                } else if (!breakEndCheck && endCheck) {
                    // IF AFTERNOON/SECOND SET LATE, OR HALF DAY
                    // GET SECOND IN FIRST
                    var bIn = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME IN MEDIAN/NOON BREAK
                        if (this.isInMedianIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                            if (this.isNull(bIn)) {
                                bIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(bIn)) {
                        pmHours = this.getHoursFour(moment(bIn).format('YYYY-MM-DD HH:mm:ss'), endOut);
                    } else {
                        pmHours = 0;
                    }
                } else if (breakEndCheck && !endCheck) {
                    // IF AFTERNOON/SECOND SET UNDERTIME, OR HALF DAY
                    // GET END OUT FIRST
                    var tOut = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT
                        if (this.isAfter(attendanceData[i]['Timestamp'], moment((date + " " + scheduleArray['BreakEnd'])).subtract(2, 'hours').format('YYYY-MM-DD HH:mm:ss'))) {
                            if (this.isNull(tOut)) {
                                tOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(tOut)) {
                        pmHours = this.getHoursFour(breakIn, moment(tOut).format('YYYY-MM-DD HH:mm:ss'));
                    } else {
                        pmHours = 0;
                    }
                } else {
                    pmHours = 0;
                }

                return amHours + pmHours;
            }
        },
        isHalfDay(attendanceData, scheduleArray, date) {
            var attSize = attendanceData.length;
            var startIn = null;
            var breakOut = null;
            var breakIn = null;
            var endOut = null;
            var am = false;
            var pm = false;

            if (this.isNull(scheduleArray['BreakStart']) | this.isNull(scheduleArray['BreakEnd'])) {
                /**
                     *  =======================================================================================
                     *  IF scheduleArray has no breaks (schedule totals exactly 8 hours with no median break)
                     *  =======================================================================================
                     */
                return false;
            } else {
                 /**
                 *  =======================================================================================
                 *  IF scheduleArray is complete with noon/median breaks
                 *  =======================================================================================
                 */
                var startCheck = false;
                var breakStartCheck = false;
                var breakEndCheck = false;
                var endCheck = false;

                // ANALYZE LOGS
                for(var i=0; i<attSize; i++) {
                    // FILTER TIME IN
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                        if (this.isNull(startIn)) {
                            startIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isBefore(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['Start']))) {
                                startCheck = true;
                            } else {
                                startCheck = false;
                            }
                        }
                    } 
                    
                    // FILTER TIME OUT MEDIAN/NOON BREAK
                    if (this.isInMedianOut(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                        if (this.isNull(breakOut)) {
                            breakOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isAfter(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                                breakStartCheck = true;
                            } else {
                                breakStartCheck = false;
                            }
                        }
                    } 

                    // FILTER TIME IN MEDIAN/NOON BREAK
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                        if (this.isNull(breakIn)) {
                            breakIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isBefore(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                                breakEndCheck = true;
                            } else {
                                breakEndCheck = false;
                            }
                        }
                    }
                    
                    // FILTER TIME OUT
                    if (this.isIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                        if (this.isNull(endOut)) {
                            endOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss')
                            if (this.isAfter(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['End']))) {
                                endCheck = true;
                            } else {
                                endCheck = false;
                            }
                        }
                    } 
                }

                // GET AM HOURS 
                if (startCheck && breakStartCheck) {
                    am = true;
                } else if (startCheck && !breakStartCheck) {
                    // IF MORNING/FIRST SET UNDERTIME, OR HALF DAY
                    // GET END OUT FIRST
                    var bOut = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT MEDIAN/NOON BREAK
                        if (this.isInMedianOut(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                            if (this.isNull(bOut)) {
                                bOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(bOut)) {
                        am = true;
                    } else {
                        am = false;
                    }
                } else if (!startCheck && breakStartCheck) {
                    // IF MORNING/FIRST LATE, OR HALF DAY
                    // GET START IN FIRST
                    var tIn = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT MEDIAN/NOON BREAK
                        if (this.isBeforeAbs(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakStart']))) {
                            if (this.isNull(tIn)) {
                                tIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(tIn)) {
                        am = true;
                    } else {
                        am = false;
                    }
                } else {
                    am = false;
                }

                // GET PM HOURS
                if (breakEndCheck && endCheck) {
                    pm = true;
                } else if (!breakEndCheck && endCheck) {
                    // IF AFTERNOON/SECOND SET LATE, OR HALF DAY
                    // GET SECOND IN FIRST
                    var bIn = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME IN MEDIAN/NOON BREAK
                        if (this.isInMedianIn(attendanceData[i]['Timestamp'], (date + " " + scheduleArray['BreakEnd']))) {
                            if (this.isNull(bIn)) {
                                bIn = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(bIn)) {
                        pm = true;
                    } else {
                        pm = 0;
                    }
                } else if (breakEndCheck && !endCheck) {
                    // IF AFTERNOON/SECOND SET UNDERTIME, OR HALF DAY
                    // GET END OUT FIRST
                    var tOut = null;
                    for(var i=0; i<attSize; i++) {                            
                        // FILTER TIME OUT
                        if (this.isAfter(attendanceData[i]['Timestamp'], moment((date + " " + scheduleArray['End'])).subtract(2, 'hours').format('YYYY-MM-DD HH:mm:ss'))) {
                            if (this.isNull(tOut)) {
                                tOut = moment(attendanceData[i]['Timestamp']).format('YYYY-MM-DD HH:mm:ss');
                            }
                            break;
                        } 
                    }
                    if (!this.isNull(tOut)) {
                        pm = true;
                    } else {
                        pm = false;
                    }
                } else {
                    pm = false;
                }

                if ((am && pm) | (!am && !pm)) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        deductHours(timeToDeduct, hours) {
            return moment(timeToDeduct).subtract(hours, 'hours').format('YYYY-MM-DD HH:mm:ss');
        },
        getHoursAttended(attendanceData, scheduleArray, noAttendanceAllowed, date) {
            if (noAttendanceAllowed) {
                return 8;
            } else {
                var attSize = attendanceData.length;
                
                var startIn = null;
                var endOut = null;
                if (attSize > 1) {
                    startIn = attendanceData[0]['Timestamp'];
                    endOut = attendanceData[attSize-1]['Timestamp'];

                    // CHECK IF ATTENDANCE HAS NO LUNCH/BREAK IN AND OUT
                    if (this.isBefore(startIn, (date + " " + scheduleArray['Start']))) {
                        startIn = (date + " " + scheduleArray['Start'].split('.')[0]);
                    }

                    if (this.isAfter(endOut, (date + " " + scheduleArray['End']))) {
                        endOut = (date + " " + scheduleArray['End'].split('.')[0]);
                    }
                    
                    // DEDUCT HOURS IF HAS BREAK
                    var hoursBreak = 0;
                    if (!this.isNull(scheduleArray['BreakStart']) && !this.isNull(scheduleArray['BreakEnd'])) {
                        hoursBreak = this.getHoursInHours(scheduleArray['BreakStart'].split('.')[0], scheduleArray['BreakEnd'].split('.')[0]);
                    } else {
                        hoursBreak = 0;
                    }

                    // CHECK IF ATTENDANCE IS HALF DAY ONLY, Do not reduce break if half day
                    var isHalfDay = false;
                    if (this.isHalfDay(attendanceData, scheduleArray, date)) {
                        hoursBreak = 0;
                        isHalfDay = true;
                    } 

                    // CHECK IF SATURDAY, DO no reduce break if saturday or sunday
                    // if (this.isWeekend(date)) {
                    //     hoursBreak = 0;
                    //     isHalfDay = true;
                    // }
                    
                    endOut = this.deductHours(endOut, hoursBreak);

                    var hours = this.getHours(startIn, endOut);
                    
                    // if (hours >= 8) {
                    //     return hours;
                    // } else {
                    //     // CHECK IF UNDERTIMED OR HALF DAY
                    //     return this.assessAttendanceHours(attendanceData, scheduleArray, date);
                    // }
                    if (hours < 0) {
                        return 0;
                    } else {
                        if (!isHalfDay) {
                            return hours;
                        } else {
                            if (hours >= 4) {
                                return 4;
                            } else {
                                return hours;
                            }
                        }                        
                    }
                    
                } else if (attSize == 1) {
                    return 'xTI/xTO';
                } else {
                    return ''; // absent
                }
            }
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        generate() {
            if (this.isNull(this.employeeType) | this.isNull(this.department) | this.isNull(this.salaryPeriod) | this.isNull(this.from) | this.isNull(this.to)) {
                Swal.fire({
                    icon : 'warning',
                    text : 'Please fill in all fields!',
                });
            } else {
                // SETUP HEADERS
                this.dateHeaders = [];
                this.dateSubHeaders = [];
                this.getInBetweenDates(this.from, this.to);

                // GET EMPLOYEES DATA
                axios.get(`${ axios.defaults.baseURL }/payroll_indices/get-payroll-data`, {
                    params: {
                        EmployeeType : this.employeeType,
                        Department : this.department,
                        SalaryPeriod : this.salaryPeriod,
                        From : this.from,
                        To : this.to,
                    }
                })
                .then(response => {
                    this.employees = [];
                    this.attendances = [];
                    var size = response.data.length;
                    for (let i=0; i<size; i++) {
                        this.employees.push({
                            name : response.data[i]['LastName'] + ", " + response.data[i]['FirstName'],
                            id : response.data[i]['id']
                        });

                        // FILTER ATTENDANCE DATA
                        var attData = response.data[i]['AttendanceData'];
                        var attendanceChunks = [];
                        var totalHoursRendered = 0.0;
                        // LOOP DATES
                        for (var j=0; j<this.dateHeaders.length-2; j++) {
                            // GET DATES SUB ARRAY
                            // filters the attendance data that belongs to the current day in the loop
                            const subDatesArray = attData.filter(x => x.DateLogged==this.dateHeaders[j].name);
                            const schedule = {
                                'Start' : response.data[i]['StartTime'],
                                'BreakStart' : response.data[i]['BreakStart'],
                                'BreakEnd' : response.data[i]['BreakEnd'],
                                'End' : response.data[i]['EndTime'],
                            };
                            // VALIDATE LOGGINS
                            var hours = this.getHoursAttended(subDatesArray, schedule, this.isNull(response.data[i]['NoAttendanceAllowed']) ? false : true, this.dateHeaders[j].name);

                            attendanceChunks.push(hours);

                            if (this.isNumber(hours)) {
                                totalHoursRendered += parseFloat(hours);
                            }
                        } 

                        attendanceChunks.push(''); // UPDATE OTs
                        attendanceChunks.push(this.round(totalHoursRendered));

                        this.attendances.push(attendanceChunks);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error getting employee data!',
                    });
                    console.log(error)
                });
            }
        },
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>