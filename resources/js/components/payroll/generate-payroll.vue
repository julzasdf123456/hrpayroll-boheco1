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
                        <option value="OGM" selected>OGM</option>
                        <option value="OSD">OSD</option>
                        <option value="PGD">PGD</option>
                        <option value="SEEAD">SEEAD</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <span class="text-muted">Salary Period</span>
                    <select v-model="salaryPeriod" class="form-control form-control-sm">
                        <option :value="fifteenth">{{ moment(fifteenth).format('MMMM DD, YYYY') }}</option>
                        <option :value="thirtieth">{{ moment(thirtieth).format('MMMM DD, YYYY') }}</option>
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
                    <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isButtonDisabled" @click="generate()"><i class="fas fa-eye ico-tab-mini"></i>Preview</button>
                    <button class="btn btn-primary btn-sm" ><i class="fas fa-check-circle ico-tab-mini"></i>Generate Payroll</button>

                    <div class="spinner-border text-primary float-right" :class="isDisplayed" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
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

    
    <div class="legend" style="margin-bottom: 15px; margin-left: 20px;">
        <span style="cursor: pointer;"><i @click="toggleView()" :class="legendIcon" class="fas ico-tab" title="Show/hide attendance columns"></i></span>

        <span :class="isLegendDisplayed">
            <span class="text-muted" style="margin-right: 15px;"><strong>Legend : </strong></span>
            <span style="width: 10px !important; height: 10px !important; background-color: #f51836; display: inline-block; border-radius: 50%; margin-right: 5px;"></span> AWOL
            <span style="width: 30px !important; height: 10px !important; background-color: #1d8fcc; display: inline-block; border-radius: 5px; margin-left: 20px; margin-right: 5px;"></span> Off Days
            <span style="width: 30px !important; height: 10px !important; background-color: #f7076f; display: inline-block; border-radius: 5px; margin-left: 20px; margin-right: 5px;"></span> Holidays
            <span style="width: 10px !important; height: 10px !important; background-color: #debe07; display: inline-block; border-radius: 50%; margin-left: 20px; margin-right: 5px;"></span> No Time In/Time Out
            <span style="width: 10px !important; height: 10px !important; background-color: #05b05d; display: inline-block; border-radius: 50%; margin-left: 20px; margin-right: 5px;"></span> Okiesss
            <span style="width: 10px !important; height: 10px !important; background-color: #e823ba; display: inline-block; border-radius: 50%; margin-left: 20px; margin-right: 5px;"></span> Trip Ticket
            <span style="width: 10px !important; height: 10px !important; background-color: #f2780c; display: inline-block; border-radius: 50%; margin-left: 20px; margin-right: 5px;"></span> Offset
            <span style="width: 10px !important; height: 10px !important; background-color: #0cf2c4; display: inline-block; border-radius: 50%; margin-left: 20px; margin-right: 5px;"></span> Leave
        </span>
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
                <th class='text-center' v-for="column in dateHeaders" :key="column.name" v-if="legendView">{{ column.label }}</th>
                <th class='text-center' v-for="columnSummary in summaryHeaders" :key="columnSummary.name">{{ columnSummary.label }}</th>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in employees" :key="employee.id">
                    <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                    <td :class="{widthClass}" v-for="(attendance, colIndex) in attendances[index]" v-if="legendView" @click="showInfo(dateHeaders[colIndex].name, employee.id, employee.name)" v-html="attendance"></td> 
                    <td class="text-right" :class="{widthClass}" v-for="(summary, colIndex) in summaries[index]" v-html="summary"></td> 
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style>
    .min-width-sm {
        min-width: 50px;
    }

    .min-width-md {
        min-width: 65px;
    }

    .min-width-xl {
        min-width: 250px;
    }

    table {
        font-size: .82em;
    }
</style>

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
            thirtieth : moment().month()==1 ? moment().format('YYYY-MM-28') : moment().format('YYYY-MM-30'),
            moment : moment,
            selectedDate: null,
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
                // You can configure more options here as per Flatpickr documentation
            },
            // PAYROLL DATA
            employeeType : 'Regular',
            department : 'OGM',
            salaryPeriod : '',
            from : '2024-01-03',
            to : '2024-01-18',
            // TABLE COLUMNS
            dateHeaders : [],
            summaryHeaders : [],
            datesOnly : [],
            // TABLE DATA
            employees : [],
            attendances : [],
            summaries : [],
            isDisplayed : 'gone',
            isButtonDisabled : false,
            widthClass : '',
            legendView : true,
            legendIcon : 'fa-eye',
            isLegendDisplayed : '',
            totalDateColumns : 0,
            areDateColumnsDisplayed : true,
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
            this.totalDateColumns = 0;

            // ADD DUTY DATES COLUMN
            while (startDate <= lastDate) {
                this.datesOnly.push({
                    date : moment(startDate).format('YYYY-MM-DD'),
                })
                this.dateHeaders.push({
                    name : moment(startDate).format('YYYY-MM-DD'),
                    label : moment(startDate).format('MMM-DD-YY ddd')
                }); 
                startDate = moment(startDate).add(1, 'days');
                this.totalDateColumns++;
            }

            // ADD TOTAL HOURS RENDERED COLUMN
            this.summaryHeaders.push({
                name : 'Total Hours Rendered',
                label : 'Total Hours Rendered'
            }); 

            // ADD TOTAL HOURS WORKABLE
            this.summaryHeaders.push({
                name : 'Total Working Hours',
                label : 'Total Working Hours'
            }); 
            
            // BASIC SALARY
            this.summaryHeaders.push({
                name : 'Monthly Wage',
                label : 'Monthly Wage'
            });

            // REGULAR WAGE
            this.summaryHeaders.push({
                name : 'Term Wage',
                label : 'Term Wage'
            });

            // ADD OT HOURS COlUMN
            this.summaryHeaders.push({
                name : 'Overtime Hours',
                label : 'Overtime Hours'
            });

            // ADD OT AMOUNT COlUMN
            this.summaryHeaders.push({
                name : 'Overtime Amount',
                label : 'Overtime Amount'
            });
            
            // ADD TOTAL HOURS ABSENT LATE
            this.summaryHeaders.push({
                name : 'Abs/Late/UT Hours',
                label : 'Abs/Late/UT Hours'
            });

            // ADD TOTAL AMOUNT ABSENT LATE
            this.summaryHeaders.push({
                name : 'Abs/Late/UT Amount',
                label : 'Abs/Late/UT Amount'
            });

            // LONGEVITY
            this.summaryHeaders.push({
                name : 'Longevity',
                label : 'Longevity'
            });

            this.summaryHeaders.push({
                name : 'Rice/Laundry',
                label : 'Rice/Laundry'
            });

            this.summaryHeaders.push({
                name : 'Others Adds.',
                label : 'Others Adds.'
            });

            this.summaryHeaders.push({
                name : 'Others Deducts.',
                label : 'Others Deducts.'
            });

            this.summaryHeaders.push({
                name : 'TOTAL AMOUNT',
                label : 'TOTAL AMOUNT'
            });

            this.summaryHeaders.push({
                name : 'MC Loan',
                label : 'MC Loan'
            });

            this.summaryHeaders.push({
                name : 'Pag-Ibig Cont.',
                label : 'Pag-Ibig Cont.'
            });

            this.summaryHeaders.push({
                name : 'Pag-Ibig Loan',
                label : 'Pag-Ibig Loan'
            });

            this.summaryHeaders.push({
                name : 'SSS Cont.',
                label : 'SSS Cont.'
            });

            this.summaryHeaders.push({
                name : 'SSS Loan',
                label : 'SSS Loan'
            });

            this.summaryHeaders.push({
                name : 'Phil Health',
                label : 'Phil Health'
            });

            this.summaryHeaders.push({
                name : 'Other Deducts.',
                label : 'Other Deducts.'
            });

            this.summaryHeaders.push({
                name : 'Tax Withheld',
                label : 'Tax Withheld'
            });

            this.summaryHeaders.push({
                name : 'Total Deducts.',
                label : 'Total Deducts.'
            });

            this.summaryHeaders.push({
                name : 'NET PAY',
                label : 'NET PAY'
            });
        },
        toggleView() {
            if (this.legendView) {
                this.legendView = false;
                this.legendIcon = 'fa-eye-slash'
                this.isLegendDisplayed = 'gone'
            } else {
                this.legendView = true;
                this.legendIcon = 'fa-eye'
                this.isLegendDisplayed = ''
            }
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
        isDateInSpecialDuty(date, specialDutyDays) {
            return specialDutyDays.some(obj => obj.Date === date);
        },
        getSpecialDuty(date, specialDutyDays) {
            return specialDutyDays.filter(obj => obj.Date === date);
        },
        isDateInLeave(date, leaveDays) {
            return leaveDays.some(obj => obj.LeaveDate === date);
        },
        getLeaveDate(date, leaveDays) {
            return leaveDays.filter(obj => obj.LeaveDate === date);
        },
        isDateInOffset(date, offsets) {
            return offsets.some(obj => obj.DateOfOffset === date);
        },
        getOffsetDate(date, offsets) {
            return offsets.filter(obj => obj.DateOfOffset === date);
        },
        isDateInTripTicket(date, tripTickets) {
            return tripTickets.some(obj => obj.DateOfTravel === date);
        },
        isDateInHoliday(date, holidays) {
            return holidays.some(obj => obj.HolidayDate === date);
        },
        getTripTicketDate(date, tripTickets) {
            return tripTickets.filter(obj => obj.DateOfTravel === date);
        },
        validateHours(maxHours, hoursTotal) {
            if (maxHours >= hoursTotal) {
                return hoursTotal;
            } else {
                return maxHours;
            }
        },
        getHoursAttended(attendanceData, scheduleArray, noAttendanceAllowed, date, dayOffDays, specialDutyDays, leaveDays, offsets, tripTickets, holidays) {
            var daySpelled = moment(date).format('dddd');
            var leaveTotalHours = 0;
            var offsetTotalHours = 0;
            var tripTicketTotalHours = 0;

            /**
             * ===========================================================
             * CHECK IF HAS LEAVE
             * ===========================================================
             */
            if (this.isDateInLeave(date, leaveDays)) {
                var leaveDay = this.getLeaveDate(date, leaveDays);
                if (this.isNull(leaveDay)) {
                    leaveTotalHours = 0;
                } else {
                    if (leaveDay[0].Duration == 'WHOLE') {
                        leaveTotalHours = 8;
                    } else {
                        leaveTotalHours = 4;
                    }
                }
            } else {
                leaveTotalHours = 0;
            }

            /**
             * ===========================================================
             * CHECK IF HAS OFFSET
             * ===========================================================
             */
             if (this.isDateInOffset(date, offsets)) {
                var offsetDay = this.getOffsetDate(date, offsets);
                if (this.isNull(offsetDay)) {
                    offsetTotalHours = 0;
                } else {
                    offsetTotalHours = 8;
                }
            } else {
                offsetTotalHours = 0;
            }

            /**
             * ===========================================================
             * CHECK IF HAS TRIP TICKET
             * ===========================================================
             */
             if (this.isDateInTripTicket(date, tripTickets)) {
                var tripTicket = this.getTripTicketDate(date, tripTickets);
                if (this.isNull(tripTicket)) {
                    tripTicketTotalHours = 0;
                } else {
                    tripTicketTotalHours = 8;
                }
            } else {
                tripTicketTotalHours = 0;
            }

            /**
             * ===========================================================
             * START ATTENDANCE CHECKING
             * ===========================================================
             */
            // CHECK FIRST IF ATTENDANCE IS HALF DAY ONLY, Do not reduce break if half day
            var isHalfDay = false;
            if (this.isHalfDay(attendanceData, scheduleArray, date)) {
                isHalfDay = true;
            }

            if (this.isDateInHoliday(date, holidays)) {
                /**
                 * ===========================================================
                 * CHECK IF HOLIDAY
                 * ===========================================================
                 */
                return 'holiday';
            } else {
                /**
                 * ===========================================================
                 * REGULAR WORKING DAYS
                 * ===========================================================
                 */
                if (noAttendanceAllowed) {
                    if (!this.isNull(dayOffDays) && dayOffDays.includes(daySpelled) && !this.isDateInSpecialDuty(date, specialDutyDays)) {
                        return 'off';
                    } else {
                        if (this.isDateInSpecialDuty(date, specialDutyDays) ) {
                            // CHECK IF SPECIAL DUTY DAY IS WHOLE DAY OR HALF DAY
                            var specialDuty = this.getSpecialDuty(date, specialDutyDays);

                            if (this.isNull(specialDuty)) {
                                return 8;
                            } else {
                                if (specialDuty[0].Term == 'Morning Only' | specialDuty[0].Term == 'Afternoon Only') {
                                    return 4;
                                } else {
                                    return 8;
                                }
                            }
                        } else {
                            return 8;
                        }                    
                    }                
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
                        if (isHalfDay) {
                            hoursBreak = 0;
                        }

                        // CHECK IF SATURDAY, DO no reduce break if saturday or sunday
                        // if (this.isWeekend(date)) {
                        //     hoursBreak = 0;
                        //     isHalfDay = true;
                        // }
                        
                        endOut = this.deductHours(endOut, hoursBreak);

                        var hours = this.getHours(startIn, endOut);
                        
                        if (!this.isNull(dayOffDays) && dayOffDays.includes(daySpelled) && !this.isDateInSpecialDuty(date, specialDutyDays)) {
                            return 'off';
                        } else {
                            if (hours < 0) {
                                if (leaveTotalHours > 0 | offsetTotalHours > 0 | tripTicketTotalHours > 0) {
                                    return this.validateHours(8, leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                } else {
                                    return 'xTI/xTO';
                                }
                            } else {
                                if (!isHalfDay) {
                                    return this.validateHours(8, hours + leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                } else {
                                    if (hours >= 4) {
                                        if (leaveTotalHours > 0 | offsetTotalHours > 0 | tripTicketTotalHours > 0) {
                                            return this.validateHours(8, hours + leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                        } else {
                                            return 4;
                                        }                                    
                                    } else {
                                        return this.validateHours(4, hours + leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                    }
                                }                        
                            }
                        }
                    } else if (attSize == 1) {
                        if (!this.isNull(dayOffDays) && dayOffDays.includes(daySpelled) && !this.isDateInSpecialDuty(date, specialDutyDays)) {
                            return 'off';
                        } else {
                            if (leaveTotalHours > 0 | offsetTotalHours > 0 | tripTicketTotalHours > 0) {
                                if (!isHalfDay) {
                                    return this.validateHours(8, leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                } else {
                                    return this.validateHours(4, leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                }
                            } else {
                                return 'xTI/xTO';
                            }
                        }
                        
                    } else {
                        if (!this.isNull(dayOffDays) && dayOffDays.includes(daySpelled) && !this.isDateInSpecialDuty(date, specialDutyDays)) {
                            return 'off';
                        } else {
                            if (leaveTotalHours > 0 | offsetTotalHours > 0 | tripTicketTotalHours > 0) {
                                if (!isHalfDay) {
                                    return this.validateHours(8, leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                } else {
                                    return this.validateHours(4, leaveTotalHours + offsetTotalHours + tripTicketTotalHours);
                                }
                            } else {
                                return 'awol'; // absent
                            }                            
                        }
                    }
                }
            }
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        beautifyDisplay(data, date, leaveDays, offsets, tripTickets) {
            if (data == 'awol') {
                return `<div><span style="width: 10px !important; height: 10px !important; background-color: #f51836; display: inline-block; border-radius: 50%;"></span><div>`;
            } else if (data == 'off') {
                return `<div><span style="width: 100%; height: 10px !important; background-color: #1d8fcc; display: inline-block; border-radius: 5px;"></span><div>`;
            } else if (data == 'xTI/xTO') {
                return `<div><span style="width: 10px !important; height: 10px !important; background-color: #debe07; display: inline-block; border-radius: 50%;"></span><div>`;
            } else if (data == 'holiday') {
                return `<div><span style="width: 100% !important; height: 10px !important; background-color: #f7076f; display: inline-block; border-radius: 5px;"></span><div>`;
            } else {
                if (this.isDateInLeave(date, leaveDays)) {
                    return `<div><span style="width: 10px !important; height: 10px !important; background-color: #0cf2c4; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>${data}<div>`;
                } else if (this.isDateInOffset(date, offsets)) {
                    return `<div><span style="width: 10px !important; height: 10px !important; background-color: #f2780c; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>${data}<div>`;
                } else  if (this.isDateInTripTicket(date, tripTickets)) {
                    return `<div><span style="width: 10px !important; height: 10px !important; background-color: #e823ba; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>${data}<div>`;
                } else {
                    // IF PRESENT
                    return `<div><span style="width: 10px !important; height: 10px !important; background-color: #05b05d; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>${data}<div>`;
                }                
            }
        },
        getOvertimes(overtimes, employeeData) {
            var totalHours = 0;
            var totalAmount = 0;
            var baseSalary = employeeData['SalaryAmount'];
            for (let i=0; i<overtimes.length; i++) {
                totalHours += parseFloat(overtimes[i]['TotalHours']);

                var multiplier = parseFloat(overtimes[i]['Multiplier']);
                totalAmount += this.isNull(baseSalary) ? 0 : (this.getSalaryPerHour(baseSalary) * multiplier * parseFloat(overtimes[i]['TotalHours']));
            }
            
            return {
                'TotalHours' : totalHours,  
                'TotalAmount' : totalAmount,
            };
        },
        getSalaryPerHour(baseSalary) {
            baseSalary = parseFloat(baseSalary);

            return this.round(((baseSalary * 12) / 302) / 8);
        },
        isFifteenth(term) {
            if (term.includes('15')) {
                return true;
            } else {
                return false;
            }
        },
        toMoney(value) {
            return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
        },
        getTotalWorkingHours(date, dayOffDays, specialDutyDays, holidays) {
            var workingHours = 0;
            var daySpelled = moment(date).format('dddd');

            if (this.isDateInHoliday(date, holidays)) {
                workingHours = 0;
            } else {
                if (!this.isNull(dayOffDays) && dayOffDays.includes(daySpelled) && !this.isDateInSpecialDuty(date, specialDutyDays)) {
                    workingHours = 0;
                } else {
                    if (this.isDateInSpecialDuty(date, specialDutyDays)) {
                        var specialDuty = this.getSpecialDuty(date, specialDutyDays);
                        if (this.isNull(specialDuty)) {
                            workingHours = 8;
                        } else {
                            if (specialDuty[0].Term == 'Morning Only' | specialDuty[0].Term == 'Afternoon Only') {
                                workingHours = 4;
                            } else {
                                workingHours = 8;
                            }
                        }
                    } else {
                        workingHours = 8;
                    }
                }
            }

            return workingHours;
        },
        generate() {
            if (this.isNull(this.employeeType) | this.isNull(this.department) | this.isNull(this.salaryPeriod) | this.isNull(this.from) | this.isNull(this.to)) {
                Swal.fire({
                    icon : 'warning',
                    text : 'Please fill in all fields!',
                });
            } else {
                this.isDisplayed = null;
                this.isButtonDisabled = true;
                // SETUP HEADERS
                this.dateHeaders = [];
                this.summaryHeaders = [];
                this.dateSubHeaders = [];
                this.employees = [];
                this.attendances = [];
                this.summaries = [];
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
                    var size = response.data['Employees'].length;
                    for (let i=0; i<size; i++) {
                        this.employees.push({
                            name : response.data['Employees'][i]['LastName'] + ", " + response.data['Employees'][i]['FirstName'],
                            id : response.data['Employees'][i]['id']
                        });

                        // FILTER ATTENDANCE DATA
                        var attData = response.data['Employees'][i]['AttendanceData'];
                        var attendanceChunks = [];
                        var summaryChunks = [];
                        var totalHoursRendered = 0.0;
                        var totalWorkingHours = 0;
                        // LOOP DATES
                        for (var j=0; j<this.dateHeaders.length; j++) {
                            this.widthClass = 'min-width-sm';
                            // GET DATES SUB ARRAY
                            // filters the attendance data that belongs to the current day in the loop
                            const subDatesArray = attData.filter(x => x.DateLogged==this.dateHeaders[j].name);
                            const schedule = {
                                'Start' : response.data['Employees'][i]['StartTime'],
                                'BreakStart' : response.data['Employees'][i]['BreakStart'],
                                'BreakEnd' : response.data['Employees'][i]['BreakEnd'],
                                'End' : response.data['Employees'][i]['EndTime'],
                            };
                            // VALIDATE LOGGINS
                            var hours = this.getHoursAttended(
                                subDatesArray, 
                                schedule, 
                                this.isNull(response.data['Employees'][i]['NoAttendanceAllowed']) ? false : true, 
                                this.dateHeaders[j].name, 
                                response.data['Employees'][i]['DayOffDates'],
                                response.data['Employees'][i]['SpecialDutyDays'],
                                response.data['Employees'][i]['LeaveDays'],
                                response.data['Employees'][i]['Offsets'],
                                response.data['Employees'][i]['TripTickets'],
                                response.data['Holidays'],
                            );

                            attendanceChunks.push(this.beautifyDisplay(hours, 
                                this.dateHeaders[j].name,
                                response.data['Employees'][i]['LeaveDays'],
                                response.data['Employees'][i]['Offsets'],
                                response.data['Employees'][i]['TripTickets']));

                            if (this.isNumber(hours)) {
                                totalHoursRendered += parseFloat(hours);
                            }

                            totalWorkingHours += this.getTotalWorkingHours(this.dateHeaders[j].name, 
                                response.data['Employees'][i]['DayOffDates'],
                                response.data['Employees'][i]['SpecialDutyDays'], 
                                response.data['Holidays']);
                        } 

                        this.attendances.push(attendanceChunks);

                        var overtimesData = this.getOvertimes(response.data['Employees'][i]['Overtimes'], response.data['Employees'][i]);
                        summaryChunks.push(totalHoursRendered > 0 ? this.round(totalHoursRendered) : '-');
                        // INSERT TOTAL WORKING HOURS
                        summaryChunks.push(totalWorkingHours > 0 ? this.round(totalWorkingHours) : '-'); 
                        // BASIC SALARY
                        summaryChunks.push(this.isNull(response.data['Employees'][i]['SalaryAmount']) ? (`₱` + 0) : (`₱` + this.toMoney(this.round(parseFloat(response.data['Employees'][i]['SalaryAmount'])))));
                        // TERM SALARY
                        var termWage = this.round(parseFloat(response.data['Employees'][i]['SalaryAmount'])/2)
                        summaryChunks.push(this.isNull(response.data['Employees'][i]['SalaryAmount']) ? (`₱` + 0) : (`₱` + this.toMoney(termWage)));
                        
                        // UPDATE OT HOURS
                        summaryChunks.push(overtimesData['TotalHours'] > 0 ? overtimesData['TotalHours'] : '-'); 
                        // UPDATE OT AMOUNT
                        var otAmount = this.round(overtimesData['TotalAmount']);
                        summaryChunks.push(overtimesData['TotalAmount'] > 0 ? (`₱` + this.toMoney(otAmount)) : '-'); 

                        // TOTAL HOURS ABSENT/LATE
                        var lateHours = this.round(totalWorkingHours - totalHoursRendered);
                        var lateAmount = this.round(parseFloat(lateHours) * this.getSalaryPerHour(parseFloat(response.data['Employees'][i]['SalaryAmount'])));
                        summaryChunks.push(lateHours > 0 ? lateHours : '-'); 
                        // TOTAL AMOUNT ABSENT/LATE
                        summaryChunks.push(lateAmount > 0 ? `₱` + this.toMoney(lateAmount) : '-'); 
                        // LONGEVITY
                        var longevity = this.isNull(response.data['Employees'][i]['Longevity']) ? 0 : parseFloat(response.data['Employees'][i]['Longevity']);
                        console.log(this.salaryPeriod)
                        longevity = this.isFifteenth(this.salaryPeriod) ? 0 : longevity;
                        summaryChunks.push(longevity > 0 ? `₱` + this.toMoney(longevity) : '-'); 

                        // RICE ALLOWANCE/LAUNDRY
                        var riceAllowance = this.isFifteenth(this.salaryPeriod) ? 2500 : 0;                        
                        summaryChunks.push(riceAllowance > 0 ? `₱` + this.toMoney(riceAllowance) : '-'); 

                        // OTHERS - ADDONS                     
                        summaryChunks.push('-'); 

                        // OTHERS - DEDUCTIONS                     
                        summaryChunks.push('-'); 

                        // TOTAL AMOUNT
                        var totalAmountPartial = (termWage - lateAmount) + otAmount + longevity + riceAllowance;
                        summaryChunks.push(totalAmountPartial > 0 ? `₱` + this.toMoney(totalAmountPartial) : '-'); 

                        // MC LOAN                 
                        summaryChunks.push('-'); 

                        // PAG IBIG CONT              
                        summaryChunks.push('-');
                        
                        // PAG IBIG LOAN            
                        summaryChunks.push('-');

                        // SSS CONT         
                        summaryChunks.push('-');

                        // SSS LOAN         
                        summaryChunks.push('-');

                        // PHIL HEALTH         
                        summaryChunks.push('-');

                        // OTHER DEDUCTIONS        
                        summaryChunks.push('-');

                        // TAX WITHHELD        
                        summaryChunks.push('-');

                        // TOTAL DEDUCTIONS       
                        summaryChunks.push('-');

                        // NET PAY        
                        summaryChunks.push('-');

                        this.summaries.push(summaryChunks)

                        this.isDisplayed = 'gone';
                        this.isButtonDisabled = false;
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error getting employee data!',
                    });
                    console.log(error)
                    this.isDisplayed = 'gone';
                    this.isButtonDisabled = false;
                });
            }
        },
        showInfo(date, employeeId, employeeName) {
            axios.get(`${ axios.defaults.baseURL }/payroll_indices/get-payroll-date-information`, {
                params: {
                    EmployeeId : employeeId,
                    Date : date
                }
            })
            .then(response => {
                var attendanceTable = `<table class='table table-sm table-hover table-bordered'>
                                            <thead>
                                                <th>Timestamp</th>
                                                <th>Type</th>
                                            </thead>
                                            <tbody>`;
                var attendanceData = response.data.AttendanceData;
                for (let i=0; i<attendanceData.length; i++) {
                    attendanceTable += `<tr>
                                            <td>` + moment(attendanceData[i]['Timestamp']).format('MMMM DD, YYYY hh:mm A') + `</td>
                                            <td>` + attendanceData[i]['Type'] + `</td>
                                        </tr>`;
                }
                attendanceTable += `</tbody></table>`;

                Swal.fire({
                    title: employeeName,
                    html: moment(date).format('MMMM DD, YYYY') + ' Attendance Data' + 
                        `<br>` +
                        attendanceTable,
                    showCloseButton: true,
                });
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting employee data!',
                });
                console.log(error)
            });
            
        },
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>