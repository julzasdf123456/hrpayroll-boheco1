<template>
    <div class="row" style="margin-left: 10px;">
        <!-- FORM -->
        <div class="col-lg-12">
            <div class="card shadow-none">
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
                                <option value="SUB-OFFICE">SUB-OFFICE</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <span class="text-muted">Action</span><br>
                            <button class="btn btn-default btn-sm ico-tab-mini" :disabled="isPreviewButtonDisabled" @click="getData()"><i class="fas fa-eye ico-tab-mini"></i>Preview</button>
                            <button class="btn btn-primary btn-sm" :disabled="isGenerateButtonDisabled" @click="submitBonus()"><i class="fas fa-check-circle ico-tab-mini"></i>Submit Bonus</button>
        
                            <div class="spinner-border text-success float-right" :class="loaderDisplay" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISIBILITY SETTINGS -->
        <!-- 14th Month Pay -->
        <div class="custom-control custom-switch" style="margin-left: 15px; margin-bottom: 20px;">
            <input type="checkbox" class="custom-control-input" :checked="is14MonthBreakdownShown" @change="toggle14thBreakdown()" id="show14th">
            <label class="custom-control-label text-muted" for="show14th" style="font-weight: normal">Show 14th-month Breakdown</label>
        </div>
        <!-- 13th Month Differential -->
        <div class="custom-control custom-switch" style="margin-left: 15px; margin-bottom: 20px;">
            <input type="checkbox" class="custom-control-input" :checked="is13MonthDiffBreakdownShown" @change="toggle13thBreakdown()" id="show13th">
            <label class="custom-control-label text-muted" for="show13th" style="font-weight: normal">Show 13th-month Diff. Breakdown</label>
        </div>

        <!-- SHOW IF EXISTS -->
        <div class="col-lg-12" v-if="incentiveExists">
            <div class="exists">
                <span style="color: aliceblue;"><i class="fas fa-exclamation-triangle ico-tab-mini"></i>{{ incentiveSelected }} already exists (Status: <strong>{{ dataStatus }}</strong>)</span>
            </div>
            <a target="_blank" :href="baseURL + '/incentives/view-incentives/' + existingDataSetId" class="btn btn-default btn-sm" style="margin-left: 10px;"><i class="fas fa-eye ico-tab-mini"></i>View {{ incentiveSelected }} Instead</a>
        </div>

         <!-- TABLE -->
         <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-xs table-bordered" id="response">
                    <thead>
                        <tr>
                            <th rowspan="2" class='text-center' style="width: 240px;">Employees</th>
                            <th rowspan="2" class='text-center' style="width: 240px;">Position</th>
                                <!-- 14th MONTH -->
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['0'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['1'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['2'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['3'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['4'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['5'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['6'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['7'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['8'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['9'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['10'] }}</th>
                                <th colspan="2" v-if="is14MonthBreakdownShown" class='text-center'>{{ monthHeaders['11'] }}</th>
                            <th rowspan="2" class="text-center">14th Month Pay</th>
                                <!-- 13th MONTH DIFFERENTIAL -->
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['0'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['1'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['2'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['3'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['4'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['5'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['6'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['7'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['8'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['9'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['10'] }}</th>
                                <th colspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>{{ monthHeaders['11'] }}</th>
                                <th rowspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>13th Month<br>Actual Total</th>
                                <th rowspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>13th Month<br>1st Term</th>
                                <th rowspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>13th Month<br>2nd Term</th>
                                <th rowspan="2" v-if="is13MonthDiffBreakdownShown" class='text-center'>13th Month<br>Total Received</th>
                            <th rowspan="2" class="text-center">13th Month<br>Differential</th>
                            <th rowspan="2" class="text-center">Cash Gift
                                <br>
                                <input type="number" step="any" :disabled="isFixedAmountCashGiftDisabled" v-model="fixedAmountCashGift" @keyup.enter="spreadCashGiftFixedAmount(fixedAmountCashGift)" @blur="spreadCashGiftFixedAmount(fixedAmountCashGift)" style="width: 150px; display: inline;" class="form-control form-control-sm text-right" placeholder="Fixed Amount">
                                or
                                <button :disabled="isSalaryBasedCashGiftButtonDisabled" class="btn btn-default btn-sm" @click="salaryBasedCashGift()">{{ salaryBasedCashGiftLabel }}</button>
                            </th>
                            <th colspan="2" class="text-center">Leave Cash Conversions</th>
                            <th rowspan="2" class="text-center">Loyalty Award</th>
                            <th rowspan="2" class="text-center">Total<br>Amount</th>
                            <th rowspan="2" class="text-center">AR-Others</th>
                            <th rowspan="2" class="text-center">BEMPC</th>
                            <th rowspan="2" class="text-center">NET PAY</th>
                        </tr>
                        <tr>
                            <!-- 14th MONTH -->
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is14MonthBreakdownShown" class='text-center'>2nd</th>
                            <!-- 13th MONTH DIFFERENTIAL -->
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>1st</th>
                            <th v-if="is13MonthDiffBreakdownShown" class='text-center'>2nd</th>
                            <th class="text-center">VL</th>
                            <th class="text-center">SL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees" :key="employee.id">
                            <td style="min-width: 250px;"><strong>{{ employee.name }}</strong></td>
                            <td style="min-width: 250px;">{{ employee.Position }}</td>
                            <!-- 14th MONTH -->
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JanuaryFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JanuarySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.FebruaryFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.FebruarySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MarchFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MarchSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AprilFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AprilSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MayFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.MaySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JuneFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JuneSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JulyFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.JulySecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AugustFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.AugustSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberSecond) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberFirst) }}</td>
                            <td v-if="is14MonthBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberSecond) }}</td>
                            <td class='text-right'>{{ employee.FourteenthMonth > 0 ? toMoney(employee.FourteenthMonth) : '-' }}</td>
                            <!-- 13th MONTH DIFFERENTIAL -->
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JanuaryFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JanuarySecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.FebruaryFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.FebruarySecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.MarchFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.MarchSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.AprilFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.AprilSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.MayFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.MaySecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JuneFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JuneSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JulyFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.JulySecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.AugustFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.AugustSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.SeptemberSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.OctoberSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.NovemberSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberFirst13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.DecemberSecond13thDiff) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.Actual13thTotal) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.First13thTerm) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.Second13thTerm) }}</td>
                            <td v-if="is13MonthDiffBreakdownShown" class='text-right'>{{ toMoney(employee.Total13thReceived) }}</td>

                            <td class='text-right'>{{ employee.ThirteenthMonthDifferential !== 0 ? toMoney(employee.ThirteenthMonthDifferential) : '-' }}</td>
                            <td class='text-right'>{{ employee.CashGift > 0 ? toMoney(employee.CashGift) : '-' }}</td>
                            <td class='text-right'>{{ employee.VL > 0 ? toMoney(employee.VL) : '-' }}</td>
                            <td class='text-right'>{{ employee.SL > 0 ? toMoney(employee.SL) : '-' }}</td>
                            <td class='text-right'>{{ employee.LoyaltyAward > 0 ? toMoney(employee.LoyaltyAward) : '-' }}</td>
                            <td class='text-right'>{{ employee.SubTotal > 0 ? toMoney(employee.SubTotal) : '-' }}</td>
                            <td>
                                <input style="min-width: 80px;" class="table-input-sm text-right" :disabled="isFixedAmountDisabled" :class="tableInputTextColor" v-model="employee.AROthers" @keyup.enter="inputEnter(employee.AROthers, employee.id)" @blur="inputEnter(employee.AROthers, employee.id)" type="number" step="any"/>
                            </td>
                            <td class='text-right'>{{ employee.BEMPC > 0 ? toMoney(employee.BEMPC) : '-' }}</td>
                            <td class='text-right'>{{ employee.NetPay > 0 ? toMoney(employee.NetPay) : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
    </div>
</template>

<style>
    td .table-input {
        padding: 0px !important;
    }

    .table-input {
        margin: 0px;
        background-color: transparent;
        width: 100%;
        border: 0px;
        height: 26px;
        font-weight: bold;
        font-size: 1.1em;
    }

    .table-input-sm {
        margin: 0px;
        background-color: transparent;
        width: 100%;
        border: 0px;
        height: 22px;
        font-weight: bold;
        font-size: 1em;
    }

    .table-input::-webkit-outer-spin-button,
    .table-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .table-input:focus,
    .table-input-sm:focus  {
        outline: none;
    }

    .table-xs {
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
    name : 'YearEndBonuses.year-end-bonuses',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            employeeType : 'Regular',
            department : 'OGM',
            year : moment().format("YYYY"),
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            isPreviewButtonDisabled : false,
            isGenerateButtonDisabled : true,
            loaderDisplay : 'gone',
            employees : [],
            incentiveExists : false,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            incentivesList : [], 
            incentiveSelected : 'Year-end Incentives',
            dataStatus : null,
            existingDataSetId : null,
            // 14th Month Pay
            monthHeaders : {
                0 : 'January',
                1 : 'February',
                2 : 'March',
                3 : 'April',
                4 : 'May',
                5 : 'June',
                6 : 'July',
                7 : 'August',
                8 : 'September',
                9 : 'October',
                10 : 'November',
                11 : 'December',
            },
            dataSetGuides : [
                {
                    name : 'JanuaryFirst',
                    value : moment(moment().format('YYYY') + "-01-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JanuarySecond',
                    value : moment(moment().format('YYYY') + "-01-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruaryFirst',
                    value : moment(moment().format('YYYY') + "-02-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruarySecond',
                    value : moment(moment().format('YYYY') + "-02-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchFirst',
                    value : moment(moment().format('YYYY') + "-03-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchSecond',
                    value : moment(moment().format('YYYY') + "-03-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilFirst',
                    value : moment(moment().format('YYYY') + "-04-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilSecond',
                    value : moment(moment().format('YYYY') + "-04-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MayFirst',
                    value : moment(moment().format('YYYY') + "-05-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MaySecond',
                    value : moment(moment().format('YYYY') + "-05-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneFirst',
                    value : moment(moment().format('YYYY') + "-06-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneSecond',
                    value : moment(moment().format('YYYY') + "-06-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JulyFirst',
                    value : moment(moment().format('YYYY') + "-07-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JulySecond',
                    value : moment(moment().format('YYYY') + "-07-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustFirst',
                    value : moment(moment().format('YYYY') + "-08-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustSecond',
                    value : moment(moment().format('YYYY') + "-08-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberFirst',
                    value : moment(moment().format('YYYY') + "-09-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberSecond',
                    value : moment(moment().format('YYYY') + "-09-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberFirst',
                    value : moment(moment().format('YYYY') + "-10-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberSecond',
                    value : moment(moment().format('YYYY') + "-10-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberFirst',
                    value : moment(moment().format('YYYY') + "-11-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberSecond',
                    value : moment(moment().format('YYYY') + "-11-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberFirst',
                    value : moment(moment().format('YYYY') + "-12-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberSecond',
                    value : moment(moment().format('YYYY') + "-12-15").endOf('month').format('YYYY-MM-DD'),
                },
            ],
            dataSetGuides13th : [
                {
                    name : 'JanuaryFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-01-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JanuarySecond13thDiff',
                    value : moment(moment().format('YYYY') + "-01-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruaryFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-02-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'FebruarySecond13thDiff',
                    value : moment(moment().format('YYYY') + "-02-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-03-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MarchSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-03-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-04-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AprilSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-04-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'MayFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-05-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'MaySecond13thDiff',
                    value : moment(moment().format('YYYY') + "-05-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-06-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JuneSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-06-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'JulyFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-07-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'JulySecond13thDiff',
                    value : moment(moment().format('YYYY') + "-07-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-08-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'AugustSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-08-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-09-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'SeptemberSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-09-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-10-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'OctoberSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-10-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-11-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'NovemberSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-11-15").endOf('month').format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberFirst13thDiff',
                    value : moment(moment().format('YYYY') + "-12-15").format('YYYY-MM-DD'),
                },
                {
                    name : 'DecemberSecond13thDiff',
                    value : moment(moment().format('YYYY') + "-12-15").endOf('month').format('YYYY-MM-DD'),
                },
            ],
            is14MonthBreakdownShown : false,
            // 13th Month Differential
            is13MonthDiffBreakdownShown : false,
            // CASH GIFT
            fixedAmountCashGift : '',
            isFixedAmountCashGiftDisabled : false,
            isSalaryBasedCashGiftButtonDisabled : false,
            salaryBasedCashGiftMultiplier : 0,
            isCashGiftSalaryBased : false,
            salaryBasedCashGiftLabel : 'Salary Based',
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
        toMoney(value) {
            if (this.isNumber(value)) {
                return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
            } else {
                return '-'
            }
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        generateRandomString(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
        },
        generateUniqueId() {
            return moment().valueOf() + "-" + this.generateRandomString(32);
        },
        inputEnter(value, employeeId) {
            axios.get(`${ axios.defaults.baseURL }/other_payroll_deductions/update-data`, {
                params: {
                    EmployeeId : employeeId,
                    Amount : value,
                    ScheduleDate : null,
                    Type : this.incentiveSelected,
                    Description : null,
                }
            }).then(response => {                
                // FIND EMPTY IDs TO BE REPLACED BY A NEW ID IF NEW ENTRY
                // UPDATE VALUE REAL TIME
                var newValue = 0
                const empArray = this.employees.filter(obj => obj.id === employeeId)

                if (!this.isNull(empArray)) {
                    var fourteenth = empArray[0].FourteenthMonth
                    var cashGift =  empArray[0].CashGift
                    var vl = empArray[0].VL
                    var sl = empArray[0].SL
                    var loyaltyAward = empArray[0].LoyaltyAward
                    var thirteenthDifferential = empArray[0].ThirteenthMonthDifferential
                    var bempc = empArray[0].BEMPC.length < 1 ? 0 : empArray[0].BEMPC

                    value = value.length < 1 ? 0 : parseFloat(value)
                    fourteenth = parseFloat(fourteenth)
                    cashGift = parseFloat(cashGift)
                    vl = parseFloat(vl)
                    sl = parseFloat(sl)
                    loyaltyAward = parseFloat(loyaltyAward)
                    thirteenthDifferential = parseFloat(thirteenthDifferential)
                    bempc = parseFloat(bempc)
                    newValue = (cashGift + fourteenth + loyaltyAward + vl + sl + thirteenthDifferential) - (parseFloat(value) + parseFloat(bempc))
                }
                this.employees = this.employees.map(obj => {
                    if (obj.id === employeeId) {
                        return { ...obj, NetPay: newValue }; // Update the name property
                    } else {
                        return obj;
                    }
                })
                this.showSaveFader()
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error adding AR-Others!',
                });
                console.log(error)
            });
        },
        isBeforeAbs(timeToCheck, baseTime) {
            baseTime = moment(baseTime).format('YYYY-MM-DD HH:mm:ss');

            timeToCheck = moment(timeToCheck).format('YYYY-MM-DD HH:mm:ss');

            return moment(timeToCheck).isBefore(baseTime);
        },
        getAROthers(arOthersData, employeeId) {
            var arObj = arOthersData.filter(obj => obj.EmployeeId === employeeId)

            if (this.isNull(arObj)) {
                return 0
            } else {
                var amount = arObj[0].Amount
                if (this.isNull(amount)) {
                    return 0
                } else {
                    return parseFloat(amount)
                }
            }
        },
        getBempcDeduction(data) {
            if (this.isNull(data)) {
                return 0
            } else {
                var amnt = data[0].Amount

                if (this.isNull(amnt)) {
                    return 0
                } else {
                    return parseFloat(amnt)
                }
            }
        },
        // 14th Month Pay
        getBiMonthlyWage(salaryData, basicSalary, month) {
            salaryData = salaryData[0]
            if (this.isNull(salaryData)) {
                if (moment(month).isBefore(moment().format('YYYY-MM-DD'))) {
                    // IF THERE ARE NO PAYROLL RECORDED BEFORE May or October TERMS
                    return 0
                } else {
                    // GET PROJECTION VIA SALARY DATA
                    if (basicSalary < 1) {
                        return 0
                    } else {
                        var bi = basicSalary / 2
                        if (bi < 1) {
                            return 0
                        } else {
                            return this.round(bi / 12)
                        }
                    }
                }
            } else {
                // GET ACTUAL
                var termWage = this.isNull(salaryData.TermWage) ? 0 : parseFloat(salaryData.TermWage)
                var deductions = this.isNull(salaryData.AbsentAmount) ? 0 : parseFloat(salaryData.AbsentAmount)
                var dif = termWage - deductions
                if (dif < 1) {
                    return 0
                } else {
                    return this.round(dif / 12)
                }
            }
        },
        toggle14thBreakdown() {
            return this.is14MonthBreakdownShown ? this.is14MonthBreakdownShown=false : this.is14MonthBreakdownShown=true
        },
        toggle13thBreakdown() {
            return this.is13MonthDiffBreakdownShown ? this.is13MonthDiffBreakdownShown=false : this.is13MonthDiffBreakdownShown=true
        },
        // CASH GIFT FUNCTIONS
        salaryBasedCashGift() {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'text',
                    html: `
                        <p style='text-align: left;'>Provide a salary <strong>Multiplier (Percentage)</strong> for this incentive/bonus. </p>
                        <div class='upload-jumbotron' style='display: inline-block;'>
                            <p style='text-align: left; font-size: .9em;'>This <strong>Multiplier</strong> will be multiplied on the employees base salary to produce the incentive/bonus.</p>
                            <p style='text-align: left; font-size: .9em;'><strong>Example: </strong></p>
                            <p style='text-align: left; font-size: .9em;'>
                                <span style='padding-left: 22px;'>Multiplier = <strong>1.5</strong> (150%)<span><br>
                                x<span style='border-bottom: 1px solid #888888; padding-left: 14px;'>Base Salary = <strong>36,750.00</strong><br></span>
                                <strong style='padding-left: 22px;'>Bonus = 55,125.00</strong>
                            </p>
                        </div>
                    `,
                    inputPlaceholder: 'Input Multiplier',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Salary-Based Cash Gift',
                    showCancelButton: true
                })

                if (text) {
                    this.fixedAmount14th = ''
                    if (this.isNull(text)) {
                        this.isCashGiftSalaryBased = false
                        this.salaryBasedCashGiftLabel = 'Salary Based'
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide multiplier if you opt for Salary-Based!',
                        })
                    } else { 
                        try {
                            if (this.isNumber(text) || !isNaN(text)) {
                                this.isCashGiftSalaryBased = true
                                this.salaryBasedCashGiftMultiplier = parseFloat(text)
                                this.salaryBasedCashGiftLabel = 'Salary Based (x ' + text + ')'
                                this.spreadCashGiftSalaryBasedAmount()
                                this.fixedAmountCashGift = ''
                            } else {
                                this.isCashGiftSalaryBased = false
                                this.salaryBasedCashGiftLabel = 'Salary Based'
                                this.toast.fire({
                                    icon : 'info',
                                    text : 'Please provide a valid number!',
                                })
                            }
                        } catch (err) {
                            this.salaryBasedCashGiftLabel = 'Salary Based'
                            this.isCashGiftSalaryBased = false
                            Swal.fire({
                                icon : 'info',
                                title : 'Oops!',
                                text : err.message,
                            })
                        }
                    }
                }
            })()
        },
        spreadCashGiftSalaryBasedAmount() {
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                var baseSalary = this.isNull(this.employees[i].SalaryAmount) ? 0 : parseFloat(this.employees[i].SalaryAmount)
                var bonusAmnt = baseSalary * this.salaryBasedCashGiftMultiplier
                this.employees[i].CashGift = bonusAmnt

                var arOthers = this.employees[i].AROthers
                var bempc = this.employees[i].BEMPC
                var fourteenth = this.employees[i].FourteenthMonth
                var vl = this.employees[i].VL
                var sl = this.employees[i].SL
                var thirteenthDifferential = this.employees[i].ThirteenthMonthDifferential
                var loyaltyAward = this.employees[i].LoyaltyAward
                var subTotal = (bonusAmnt + fourteenth + loyaltyAward + vl + sl + parseFloat(thirteenthDifferential))
                var netPay = (bonusAmnt + fourteenth + loyaltyAward + vl + sl + parseFloat(thirteenthDifferential)) - (arOthers + bempc)

                this.employees[i].SubTotal = subTotal
                this.employees[i].NetPay = netPay
            }
            this.showSaveFader()
        },
        spreadCashGiftFixedAmount(cashGiftAmnt) {
            this.salaryBasedCashGiftLabel = 'Salary Based'
            this.isCashGiftSalaryBased = false
            let size = this.employees.length
            for(let i=0; i<size; i++) {
                this.employees[i].CashGift = cashGiftAmnt

                var arOthers = this.employees[i].AROthers
                var bempc = this.employees[i].BEMPC
                var loyaltyAward = this.employees[i].LoyaltyAward
                var fourteenth = this.employees[i].FourteenthMonth
                var thirteenthDifferential = this.employees[i].ThirteenthMonthDifferential
                var vl = this.employees[i].VL
                var sl = this.employees[i].SL
                var subTotal = (cashGiftAmnt + fourteenth + loyaltyAward + vl + sl + parseFloat(thirteenthDifferential))
                var netPay = (cashGiftAmnt + fourteenth + loyaltyAward + vl + sl + parseFloat(thirteenthDifferential)) - (arOthers + bempc)

                this.employees[i].SubTotal = subTotal
                this.employees[i].NetPay = netPay
            }
            this.showSaveFader()
        },
        getExistingIncentive(data) {
            var bonusAmount = data.SubTotal
            if (this.isNull(bonusAmount)) {
                return 0;
            } else {
                return parseFloat(bonusAmount);
            }
        },
        getLoyaltyAward (dateHired) {
            var yearsTotal = isNull(dateHired) ? 0 : moment().diff(moment(dateHired).format('YYYY-MM-DD'), 'years')

            if (yearsTotal < 10) {
                return 0
            } else if (yearsTotal === 10) {
                return 2000
            } else {
                return 500
            }
        },
        getConversions(vlslArray, type) {
            if (type === 'Vacation') {
                var size = vlslArray.length
                var total = 0
                for(let i=0; i<size; i++) {
                    total += parseFloat(vlslArray[i].VacationAmount)
                }
                return total
            } else {
                var size = vlslArray.length
                var total = 0
                for(let i=0; i<size; i++) {
                    total += parseFloat(vlslArray[i].SickAmount)
                }
                return total
            }
        },
        get13thReceived(data, term) {
            var foundData = data.filter(obj => obj.IncentiveName === term)
            if (foundData.length > 0) {
                return parseFloat(foundData[0].NetPay)
            } else {
                return 0
            }
        },
        getData() {
            this.employees = []
            this.loaderDisplay = ''
            this.incentiveExists = false
            this.isGenerateButtonDisabled = true
            this.isPreviewButtonDisabled = true
            this.existingDataSetId = null
            this.dataStatus = null

            axios.get(`${ axios.defaults.baseURL }/incentives/get-year-end-incentives-data`, {
                params : {
                    Department : this.department, 
                    EmployeeType : this.employeeType,
                }
            })
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false

                // CHECK IF EXISTS
                if (this.isNull(response.data['IncentiveCheck'])) {
                    this.incentiveExists = false
                    this.dataStatus = null
                    this.isFixedAmountDisabled = false
                    this.isSalaryBased14thButtonDisabled = false
                } else {
                    this.existingDataSetId = response.data['IncentiveCheck'].id
                    if (this.isNull(response.data['IncentiveCheck'].Status)) {
                        this.dataStatus = 'Pending'
                        this.isFixedAmountDisabled = false
                        this.isSalaryBased14thButtonDisabled = false
                    } else {
                        if (response.data['IncentiveCheck'].Status === 'Locked') {
                            this.dataStatus = 'Locked'
                            this.isFixedAmountDisabled = true
                            this.isSalaryBased14thButtonDisabled = true
                        } else {
                            this.dataStatus = response.data['IncentiveCheck'].Status
                            this.isFixedAmountDisabled = false
                            this.isSalaryBased14thButtonDisabled = false
                        }
                    }
                    this.incentiveExists = true
                }
                
                let size = response.data['Employees'].length
                for(let i=0; i<size; i++) {
                    // GET EMPLOYEES
                    var datasets = {
                        name : response.data['Employees'][i]['LastName'] + ", " + response.data['Employees'][i]['FirstName'],
                        id : response.data['Employees'][i]['id'],
                        Position : response.data['Employees'][i]['Position'],
                        SalaryAmount : this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : response.data['Employees'][i]['SalaryAmount'],
                    }

                    /**
                     * ============================================================================
                     *  14th MONTH PAY
                     * ============================================================================
                     */
                    // GET PAYROLL DATA
                    var payrollData = response.data['Employees'][i]['SalaryData']
                    var basicSalary = this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : parseFloat(response.data['Employees'][i]['SalaryAmount'])
                    let dataGuideSize = this.term === moment().format('YYYY-05-01') ? 12 : this.dataSetGuides.length
                    var fourteenthMonthTotal = 0
                    var thirteenthMonthActualTotal = 0
                    for(let y=0; y<dataGuideSize; y++) {
                        var foundData14th = payrollData.filter(obj => obj.SalaryPeriod === this.dataSetGuides[y].value)
                        var foundData13th = payrollData.filter(obj => obj.SalaryPeriod === this.dataSetGuides13th[y].value)
                        // 14th Month
                        datasets[this.dataSetGuides[y].name] = this.getBiMonthlyWage(foundData14th, basicSalary, this.dataSetGuides[y].value)
                        fourteenthMonthTotal += this.getBiMonthlyWage(foundData14th, basicSalary, this.dataSetGuides[y].value)
                        // 13th Month
                        datasets[this.dataSetGuides13th[y].name] = this.getBiMonthlyWage(foundData13th, basicSalary, this.dataSetGuides13th[y].value)
                        thirteenthMonthActualTotal += this.getBiMonthlyWage(foundData13th, basicSalary, this.dataSetGuides13th[y].value)
                    }
                    datasets['FourteenthMonth'] = this.round(fourteenthMonthTotal)

                    /**
                     * ============================================================================
                     *  13th Month
                     * ============================================================================
                     */
                    datasets['Actual13thTotal'] = this.round(thirteenthMonthActualTotal)
                    var first13th = this.get13thReceived(response.data['Employees'][i]['Received13thMonths'], '13th Month Pay - 1st Half')
                    datasets['First13thTerm'] = first13th
                    var second13th = this.get13thReceived(response.data['Employees'][i]['Received13thMonths'], '13th Month Pay - 2nd Half')
                    datasets['Second13thTerm'] = second13th
                    var total13Received = first13th + second13th
                    datasets['Total13thReceived'] = total13Received
                    var diff13th = thirteenthMonthActualTotal - total13Received
                    datasets['ThirteenthMonthDifferential'] = this.round(parseFloat(diff13th))

                    /**
                     * ============================================================================
                     *  Cash Gift
                     * ============================================================================
                     */
                    var cashGiftAmnt = 0
                    if (this.isCashGiftSalaryBased) {
                        cashGiftAmnt = (this.isNull(response.data['Employees'][i]['SalaryAmount']) ? 0 : (parseFloat(response.data['Employees'][i]['SalaryAmount'])) * this.salaryBasedCashGiftMultiplier)
                    } else {
                        cashGiftAmnt = jquery.isEmptyObject(response.data['Employees'][i]['ExistingIncentive']) ? this.fixedAmountCashGift : this.getExistingIncentive(response.data['Employees'][i]['ExistingIncentive'])
                    }

                    cashGiftAmnt = cashGiftAmnt.length < 1 ? 0 : cashGiftAmnt
                    datasets['CashGift'] = this.round(cashGiftAmnt)

                    /**
                     * ============================================================================
                     *  LOYALTY AWARD
                     * ============================================================================
                     */
                    var loyaltyAward = this.getLoyaltyAward(response.data['Employees'][i]['DateHired'])
                    datasets['LoyaltyAward'] = loyaltyAward > 0 ? this.round(loyaltyAward) : 0

                    /**
                     * ============================================================================
                     *  VL
                     * ============================================================================
                     */
                    var vlTotal = this.getConversions(response.data['Employees'][i]['VLSL'], 'Vacation')
                    datasets['VL'] = vlTotal > 0 ? this.round(vlTotal) : 0

                    /**
                     * ============================================================================
                     *  SL
                     * ============================================================================
                     */
                     var slTotal = this.getConversions(response.data['Employees'][i]['VLSL'], 'Sick')
                    datasets['SL'] = slTotal > 0 ? this.round(slTotal) : 0

                    /**
                     * ============================================================================
                     *  SUB-TOTAL
                     * ============================================================================
                     */
                    var subTotal = (cashGiftAmnt + fourteenthMonthTotal + loyaltyAward + vlTotal + slTotal + diff13th)
                    datasets['SubTotal'] = subTotal > 0 ? this.round(parseFloat(subTotal)) : 0

                    /**
                     * ============================================================================
                     *  AROthers
                     * ============================================================================
                     */
                    var arOtherAmnt = this.getAROthers(response.data['Employees'][i]['AROthers'], response.data['Employees'][i]['id'])
                    datasets['AROthers'] = arOtherAmnt > 0 ? this.round(arOtherAmnt) : 0

                    /**
                     * ============================================================================
                     *  BEMPC
                     * ============================================================================
                     */
                    var bempcDeduction = this.isNumber(this.getBempcDeduction(response.data['Employees'][i]['BEMPC'])) ? this.getBempcDeduction(response.data['Employees'][i]['BEMPC']) : 0
                    datasets['BEMPC'] = bempcDeduction.length < 1 ? 0 : this.round(bempcDeduction)

                    var netPay = (cashGiftAmnt + fourteenthMonthTotal + loyaltyAward + vlTotal + slTotal + diff13th) - (arOtherAmnt + bempcDeduction)
                    datasets['NetPay'] = netPay > 0 ? this.round(parseFloat(netPay)) : 0

                    this.employees.push(datasets)
                }
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting incentives list!',
                });
                console.log(error)
                this.loaderDisplay = 'gone';
            });         
        },
        submitBonus() {
            if (this.isNull(this.dataStatus)) {
                Swal.fire({
                    title: "Submit for Audit?",
                    text : 'Submit this Year-end Incentives draft for audit? You can always regenerate this anytime as long as it has not yet been approved for finalization.',
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    confirmButtonColor: '#3a9971',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.saveBonus()
                    }
                })
            } else {
                if (this.dataStatus === 'Locked') {
                    Swal.fire({
                        icon : 'info',
                        title: "Data is Already Locked",
                        text : 'There is already an existing LOCKED dataset containing these data. Regenerating is no longer allowed.'
                    })
                } else {
                    Swal.fire({
                        icon : 'warning',
                        title: "Re-submit and Override Existing?",
                        text : 'There is already an existing dataset containing these data. Are you sure you want to override it?',
                        showCancelButton: true,
                        confirmButtonText: "Proceed Override",
                        confirmButtonColor: '#e03822',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.saveBonus()
                        }
                    })
                }
            }
        },
        saveBonus() {
            this.loaderDisplay = ''
            this.isGenerateButtonDisabled = true
            this.isPreviewButtonDisabled = true
            axios.post(`${ axios.defaults.baseURL }/leave-incentives_year_end_details/save-year-end`, {
                    Department : this.department,
                    EmployeeType : this.employeeType,
                    Data : this.employees,
                    IncentiveName : this.incentiveSelected
            })
            .then(response => {
                this.loaderDisplay = 'gone'
                this.isGenerateButtonDisabled = true
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false
                this.employees = []

                this.toast.fire({
                    text :  'Year-end data saved!',
                    icon : 'success'
                })
            })
            .catch(error => {
                this.isGenerateButtonDisabled = false
                this.isPreviewButtonDisabled = false
                this.incentiveExists = false

                Swal.fire({
                    icon : 'error',
                    title : 'Error submitting year-end data!',
                });
                console.log(error)
                this.loaderDisplay = 'gone'
            });
        },
        showSaveFader() {
            var message = document.getElementById('msg-display');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 1500);
        }
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>
