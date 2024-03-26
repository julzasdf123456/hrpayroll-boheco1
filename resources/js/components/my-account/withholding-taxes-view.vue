<template>
    <div class="section mt-2">
        <div class="row">
            <div class="col-10 relative">
                <div class="botom-left-contents px-3">
                    <p class="no-pads text-md">Your withholding taxes</p>
                    <p class="no-pads text-muted">Computation of your year-round withholding taxes. Withholding taxes that are no being deducted from your
                        monthly salary are based on the annual projection of your incentives. This is to reduce the year-end withholding tax that is being deducted from 
                        your year-end incentives. 
                    </p>
                </div>
            </div>
            <div class="col-2 center-contents">
                <img style="width: 100% !important;" class="img-fluid" :src="imgsPath + 'tax.png'" alt="User profile picture">
            </div>
        </div>

        <div class="card shadow-none mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-md">Current Taxes Computation</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-2">
                        <span class="text-muted">Choose Year</span>
                        <select v-model="yearSelect" class="form-control">
                            <option v-for="year in years" :value="year">{{ year }}</option>
                        </select>
                    </div>
                    <div class="col-lg-9 col-md-6">
                        <div id="wt-loader" class="spinner-border text-primary float-right gone" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
    
                    <div class="col-lg-12 mt-3 table-responsive">
                        <table class="table table-hover table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="v-align text-muted">Salary</td>
                                    <td class="v-align text-muted text-right">Total Taxable</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Annual Base Salary</td>
                                    <td class="v-align text-muted text-right">{{ currentTaxesData.BaseSalary }} x 12</td>
                                    <td class="v-align text-right">{{ currentTaxesData.AnnualSalary }}</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Overtimes (Actual)</td>
                                    <td class="v-align text-muted text-right">{{ currentTaxesData.OvertimeHours }} hrs</td>
                                    <td class="v-align text-right">{{ currentTaxesData.OvertimeAmount }}</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Absences/Late/UT (Actual)</td>
                                    <td class="v-align text-muted text-right">{{ currentTaxesData.AbsentHours }} hrs</td>
                                    <td class="v-align text-right text-danger">- {{ currentTaxesData.AbsentAmount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Total Taxable</strong></td>
                                    <td class="v-align text-right"><strong>{{ currentTaxesData.SalaryTotalTaxable }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="v-align text-muted">Leave Conversions</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Vacation</td>
                                    <td class="v-align text-muted text-right"></td>
                                    <td class="v-align text-right"></td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Sick</td>
                                    <td class="v-align text-muted text-right"></td>
                                    <td class="v-align text-right"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Total Taxable</strong></td>
                                    <td class="v-align text-right"><strong>-</strong></td>
                                </tr>
                                <tr>
                                    <td class="v-align text-muted">Incentives & Allowances (Projection)</td>
                                    <td class="v-align text-muted text-right">Projected Amount</td>
                                    <td class="v-align text-muted text-right">Taxable Amount</td>
                                </tr>
                                <tr v-for="incentive in currentTaxesData.Incentives">
                                    <td class="v-align indent-1">{{ incentive.Incentive }}</td>
                                    <td class="v-align text-muted text-right">{{ incentive.Amount }}</td>
                                    <td class="v-align text-right">{{ incentive.TaxableAmount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="v-align indent-1"><strong>Sub-Total</strong></td>
                                    <td class="v-align text-right"><strong>{{ currentTaxesData.SubTotalIncentives }}</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="v-align indent-1"><strong>Non-taxable Amount</strong></td>
                                    <td class="v-align text-right text-danger">-90,000.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Taxable Amount</strong></td>
                                    <td class="v-align text-right"><strong>{{ currentTaxesData.IncentivesTotalTaxable }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align text-muted">Deductions & Contributions</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">SSS Contibution</td>
                                    <td class="v-align text-right text-muted">{{ currentTaxesData.SSS }} x 12</td>
                                    <td class="v-align text-right text-danger">- {{ currentTaxesData.SSSAnual }}</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">Pag-Ibig Contibution</td>
                                    <td class="v-align text-right text-muted">{{ currentTaxesData.PagIbig }} x 12</td>
                                    <td class="v-align text-right text-danger">-{{ currentTaxesData.PagIbigAnual }}</td>
                                </tr>
                                <tr>
                                    <td class="v-align indent-1">PhilHealth Contibution</td>
                                    <td class="v-align text-right text-muted">{{ currentTaxesData.PhilHealth }} x 12</td>
                                    <td class="v-align text-right text-danger">-{{ currentTaxesData.PhilHealthAnual }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="v-align indent-1">Loans & Deductions (Actual)</td>
                                    <td class="v-align text-right text-danger">-{{ currentTaxesData.LoansAndDeductions }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Total Deductions & Contributions</strong></td>
                                    <td class="v-align text-right text-danger"><strong>-{{ currentTaxesData.TotalDeductions }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Overall Total Taxable</strong></td>
                                    <td class="v-align text-right" style="border-top: 1px solid #989898;"><strong>{{ currentTaxesData.OverallTotalTaxable }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Total Anual Withholding Taxes (Based from bracket)</strong></td>
                                    <td class="v-align text-right text-danger"><strong>{{ currentTaxesData.AnnualCurrentWithholdingTax }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="v-align indent-1"><strong>Average Monthly Withholding Tax</strong></td>
                                    <td class="v-align text-right text-danger"><strong>{{ currentTaxesData.MonthlyCurrentWithholdingTax }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'WithholdingTaxesVuew.withholding-taxes-view',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            pickerOptions: {
                enableTime: false, // Enable time selection
                dateFormat: 'Y-m-d', // Date format
            },
            years : [],
            yearSelect : moment().format("YYYY"),
            currentTaxesData : {},
            imgsPath : axios.defaults.imgsPath,
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
        getTaxByBracket(amount) {
            if (amount <= 250000) {
                return 0;
            } else if (amount > 250000 && amount <= 400000) {
                // 15% of the excess of 250,000
                var excess = amount - 250000
                return this.round(excess * .15)
            } else if (amount > 400000 && amount <= 800000) {
                // 22,500 + 20% of the excess of 400,000
                var excess = amount - 400000
                return this.round(22500 + (excess * .2))
            } else if (amount > 800000 && amount <= 2000000) {
                // 102,500 + 25% of the excess of 800,000
                var excess = amount - 800000
                return this.round(102500 + (excess * .25))
            } else if (amount > 2000000 && amount <= 8000000) {
                // 402,500 + 30% of the excess of 2,000,000
                var excess = amount - 2000000
                return this.round(402500 + (excess * .30))
            } else {
                // 2,202,500 + 35% of the excess of 8,000,000
                var excess = amount - 8000000
                return this.round(2202500 + (excess * .35))
            }
        },
        getTotalAbsences(data) {
            var size = data.length
            var totalHours = 0
            var totalAmount = 0
            for (let i=0; i<size; i++) {
                totalHours += parseFloat(data[i].AbsentHours)
                totalAmount += parseFloat(data[i].AbsentAmount)
            }

            return { AbsentHours : totalHours, AbsentAmount : totalAmount }
        },
        getTotalOvertimes(data) {
            var size = data.length
            var totalHours = 0
            var totalAmount = 0
            for (let i=0; i<size; i++) {
                totalHours += parseFloat(data[i].OvertimeHours)
                totalAmount += parseFloat(data[i].OvertimeAmount)
            }

            return { OvertimeHours : totalHours, OvertimeAmount : totalAmount }
        },
        getLoans(data) {
            var size = data.length
            var totalAmount = 0
            for (let i=0; i<size; i++) {
                totalAmount += (parseFloat(data[i].MotorycleLoan) + parseFloat(data[i].PagIbigLoan) + parseFloat(data[i].SSSLoan) + parseFloat(data[i].OtherDeductions))
            }

            return totalAmount
        },
        getData() {
            this.currentTaxesData = {}
            axios.get(`${ axios.defaults.baseURL }/payroll_indices/get-withholding-tax-data`, {
                params: {
                    EmployeeId : this.employeeId,
                    Year : this.yearSelect,
                }
            })
            .then(response => {
                var employee = response.data.Employee
                var payroll = response.data.PayrollActual
                var contributions = response.data.Contributions
                if (!this.isNull(employee)) {
                    this.currentTaxesData.BaseSalary = this.toMoney(parseFloat(employee.SalaryAmount))

                    var annualSalary = parseFloat(employee.SalaryAmount) * 12
                    this.currentTaxesData.AnnualSalary = this.toMoney(annualSalary)

                    // absences
                    var absences = this.getTotalAbsences(payroll)
                    this.currentTaxesData.AbsentHours = this.toMoney(parseFloat(absences.AbsentHours))
                    this.currentTaxesData.AbsentAmount = this.toMoney(parseFloat(absences.AbsentAmount))
                    
                    // OT
                    var overtimes = this.getTotalOvertimes(payroll)
                    this.currentTaxesData.OvertimeHours = this.toMoney(parseFloat(overtimes.OvertimeHours))
                    this.currentTaxesData.OvertimeAmount = this.toMoney(parseFloat(overtimes.OvertimeAmount))

                    var subTotalSalary = (annualSalary + overtimes.OvertimeAmount) - absences.AbsentAmount
                    this.currentTaxesData.SalaryTotalTaxable = this.toMoney(parseFloat(subTotalSalary))

                    // incentives
                    var incentives = response.data.ProjectedIncentives
                    var size = incentives.length
                    var incentivesData = []
                    var subTotalIncentives = 0
                    for(let i=0; i<size; i++) {
                        var taxableAmount = parseFloat(incentives[i].Amount) - parseFloat(incentives[i].MaxUntaxableAmount)
                        incentivesData.push({
                            Incentive : incentives[i].Incentive,
                            Amount : this.toMoney(parseFloat(incentives[i].Amount)),
                            TaxableAmount : toMoney(taxableAmount)
                        })
                        subTotalIncentives += taxableAmount
                    }
                    this.currentTaxesData.Incentives = incentivesData
                    this.currentTaxesData.SubTotalIncentives = this.toMoney(subTotalIncentives)
                    var incentivesTotalTaxable = subTotalIncentives - 90000
                    this.currentTaxesData.IncentivesTotalTaxable = this.toMoney(incentivesTotalTaxable)

                    // contributions
                    var sss = parseFloat(contributions.SSSContribution)
                    var sssAnual = sss * 12
                    this.currentTaxesData.SSS = this.toMoney(sss)
                    this.currentTaxesData.SSSAnual = this.toMoney(sssAnual)

                    var pagIbig = parseFloat(contributions.PagIbigContribution)
                    var pagIbigAnual = pagIbig * 12
                    this.currentTaxesData.PagIbig = this.toMoney(pagIbig)
                    this.currentTaxesData.PagIbigAnual = this.toMoney(pagIbigAnual)

                    var philHealth = parseFloat(contributions.PhilHealth)
                    var philHealthAnual = philHealth * 12
                    this.currentTaxesData.PhilHealth = this.toMoney(philHealth)
                    this.currentTaxesData.PhilHealthAnual = this.toMoney(philHealthAnual)

                    var loansAndDeductions = parseFloat(this.getLoans(payroll))
                    this.currentTaxesData.LoansAndDeductions = this.toMoney(loansAndDeductions)

                    var deductionsTotal = sssAnual + pagIbigAnual + philHealthAnual + loansAndDeductions
                    this.currentTaxesData.TotalDeductions = this.toMoney(deductionsTotal)

                    var overAllTotalTaxable = (subTotalSalary + incentivesTotalTaxable) - deductionsTotal
                    this.currentTaxesData.OverallTotalTaxable = this.toMoney(overAllTotalTaxable)

                    var wt = this.getTaxByBracket(overAllTotalTaxable)
                    this.currentTaxesData.AnnualCurrentWithholdingTax = this.toMoney(wt)

                    this.currentTaxesData.MonthlyCurrentWithholdingTax = this.toMoney(wt/12)

                    console.log(this.currentTaxesData)
                } else {
                    this.toast.fire({
                        icon : 'info',
                        text : 'No data found!'
                    })
                }
                
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
        for(let i=0; i<10; i++) {
            this.years.push(moment().subtract(i, 'year').format("YYYY"))
        }
        this.getData()
    }
}

</script>