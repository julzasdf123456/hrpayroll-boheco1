<template>
    <div class="row" style="padding-top: 15px; margin-left: 10px;">
        <div class="col-lg-8">
            <h4>Payroll Withholding Taxes Report</h4>
        </div>
        <div class="col-lg-4">
            <select v-model="years" @change="getData()" class="form-control form-control-sm float-right" style="width: 120px;">
                <option v-for="year in yearsData" :value="year">{{ year }}</option>
            </select>

            <select v-model="department" @change="getData()" class="form-control form-control-sm float-right" style="width: 140px; margin-right: 10px;">
                <option value="All">All</option>
                <option value="ESD">ESD</option>
                <option value="ISD">ISD</option>
                <option value="OGM">OGM</option>
                <option value="OSD">OSD</option>
                <option value="PGD">PGD</option>
                <option value="SEEAD">SEEAD</option>
                <option value="SUB-OFFICE">SUB-OFFICE</option>
            </select>

            
            <div class="spinner-border text-success float-right" :class="isDisplayed" role="status" style="margin-right: 10px;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="col-lg-12" style="margin-top: 15px;">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">Monthly withholding taxes deducted from each employees for year <strong>{{ years }}</strong></span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-xs table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">Employees</th>
                                <th class="text-center" rowspan="2">Position</th>
                                <th class="text-center" colspan="2">January</th>
                                <th class="text-center" colspan="2">February</th>
                                <th class="text-center" colspan="2">March</th>
                                <th class="text-center" colspan="2">April</th>
                                <th class="text-center" colspan="2">May</th>
                                <th class="text-center" colspan="2">June</th>
                                <th class="text-center" colspan="2">July</th>
                                <th class="text-center" colspan="2">August</th>
                                <th class="text-center" colspan="2">September</th>
                                <th class="text-center" colspan="2">October</th>
                                <th class="text-center" colspan="2">November</th>
                                <th class="text-center" colspan="2">December</th>
                                <th class="text-center" rowspan="2">TOTAL</th>
                            </tr>
                            <tr>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                                <th class="text-center">1st</th>
                                <th class="text-center">2nd</th>
                            </tr>                            
                        </thead>
                        <tbody>
                            <tr v-for="reportDatum in reportData" :key="reportDatum.id">
                                <td>{{ reportDatum.Name }}</td>
                                <td>{{ reportDatum.Position }}</td>
                                <td class="text-right">{{ reportDatum['0'] }}</td>
                                <td class="text-right">{{ reportDatum['1'] }}</td>
                                <td class="text-right">{{ reportDatum['2'] }}</td>
                                <td class="text-right">{{ reportDatum['3'] }}</td>
                                <td class="text-right">{{ reportDatum['4'] }}</td>
                                <td class="text-right">{{ reportDatum['5'] }}</td>
                                <td class="text-right">{{ reportDatum['6'] }}</td>
                                <td class="text-right">{{ reportDatum['7'] }}</td>
                                <td class="text-right">{{ reportDatum['8'] }}</td>
                                <td class="text-right">{{ reportDatum['9'] }}</td>
                                <td class="text-right">{{ reportDatum['10'] }}</td>
                                <td class="text-right">{{ reportDatum['11'] }}</td>
                                <td class="text-right">{{ reportDatum['12'] }}</td>
                                <td class="text-right">{{ reportDatum['13'] }}</td>
                                <td class="text-right">{{ reportDatum['14'] }}</td>
                                <td class="text-right">{{ reportDatum['15'] }}</td>
                                <td class="text-right">{{ reportDatum['16'] }}</td>
                                <td class="text-right">{{ reportDatum['17'] }}</td>
                                <td class="text-right">{{ reportDatum['18'] }}</td>
                                <td class="text-right">{{ reportDatum['19'] }}</td>
                                <td class="text-right">{{ reportDatum['20'] }}</td>
                                <td class="text-right">{{ reportDatum['21'] }}</td>
                                <td class="text-right">{{ reportDatum['22'] }}</td>
                                <td class="text-right">{{ reportDatum['23'] }}</td>
                                <td class="text-right"><strong>{{ reportDatum['Total'] }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>

</template>

<style>
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
    name : 'MultiplePayrollDeductions.multiple-payroll-deductions',
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            moment : moment,
            yearsData : [],
            years : moment().format("YYYY"), /* moment().format("YYYY") */
            department : 'All',
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            reportData : [],
            monthlyPayrollDataHeads : [],
            isDisplayed : 'gone',
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
            return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        getData(department, year) {
            this.reportData = []            
            this.monthlyPayrollDataHeads = [
                moment(this.years + "-01-15").format('YYYY-MM-DD'), //JanuaryFirst 
                moment(this.years + "-01-15").endOf('month').format('YYYY-MM-DD'), //JanuarySecond 
                moment(this.years + "-02-15").format('YYYY-MM-DD'), //FebruaryFirst 
                moment(this.years + "-02-15").endOf('month').format('YYYY-MM-DD'), //FebruarySecond 
                moment(this.years + "-03-15").format('YYYY-MM-DD'), //MarchFirst 
                moment(this.years + "-03-15").endOf('month').format('YYYY-MM-DD'), //MarchSecond 
                moment(this.years + "-04-15").format('YYYY-MM-DD'), //AprilFirst 
                moment(this.years + "-04-15").endOf('month').format('YYYY-MM-DD'), //AprilSecond 
                moment(this.years + "-05-15").format('YYYY-MM-DD'), //MayFirst 
                moment(this.years + "-05-15").endOf('month').format('YYYY-MM-DD'), //MaySecond 
                moment(this.years + "-06-15").format('YYYY-MM-DD'), //JuneFirst 
                moment(this.years + "-06-15").endOf('month').format('YYYY-MM-DD'), //JuneSecond 
                moment(this.years + "-07-15").format('YYYY-MM-DD'), //JulyFirst 
                moment(this.years + "-07-15").endOf('month').format('YYYY-MM-DD'), //JulySecond 
                moment(this.years + "-08-15").format('YYYY-MM-DD'), //AugustFirst 
                moment(this.years + "-08-15").endOf('month').format('YYYY-MM-DD'), //AugustSecond 
                moment(this.years + "-09-15").format('YYYY-MM-DD'), //SeptemberFirst 
                moment(this.years + "-09-15").endOf('month').format('YYYY-MM-DD'), //SeptemberSecond 
                moment(this.years + "-10-15").format('YYYY-MM-DD'), //OctoberFirst 
                moment(this.years + "-10-15").endOf('month').format('YYYY-MM-DD'), //OctoberSecond 
                moment(this.years + "-11-15").format('YYYY-MM-DD'), //NovemberFirst 
                moment(this.years + "-11-15").endOf('month').format('YYYY-MM-DD'), //NovemberSecond 
                moment(this.years + "-12-15").format('YYYY-MM-DD'), //DecemberFirst 
                moment(this.years + "-12-15").endOf('month').format('YYYY-MM-DD'), //DecemberSecond 
            ]
            this.isDisplayed = ''

            // GET EMPLOYEES DATA
            axios.get(`${ axios.defaults.baseURL }/payroll_indices/get-withholding-taxes-report-data`, {
                params: {
                    Department : this.department,
                    Year : this.years,
                }
            })
            .then(response => {
                let size = response.data.length
                for (let i=0; i<size; i++) {
                    var objectData = response.data[i]
                    var salaryData = objectData.ReportData

                    var arrayData = {
                        Name : objectData.LastName + ", " + objectData.FirstName,
                        Position : objectData.Position,
                    }

                    let salDataLength = this.monthlyPayrollDataHeads.length
                    let totalVatMonthly = 0
                    for (let x=0; x<salDataLength; x++) {
                        var obj = salaryData.filter(item => item.SalaryPeriod === this.monthlyPayrollDataHeads[x])
                        var tax = 0
                        if (!this.isNull(obj)) {
                            tax = this.isNull(obj[0].TotalWithholdingTax) ? 0 : (parseFloat(obj[0].TotalWithholdingTax) > 0 ? parseFloat(obj[0].TotalWithholdingTax) : 0)
                            arrayData[x] = tax > 0 ? this.toMoney(tax) : '-'
                        } else {
                            arrayData[x] = '-'
                        }
                        totalVatMonthly += tax
                    }
                    arrayData['Total'] = totalVatMonthly > 0 ? this.toMoney(totalVatMonthly) : '-'

                    this.reportData.push(arrayData)
                }
                this.isDisplayed = 'gone'
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting report data!',
                });
                console.log(error)
                this.isDisplayed = 'gone'
            });
        }
    },
    created() {
        
    },
    mounted() {
        for(let i=0; i<10; i++) {
            this.yearsData.push(moment().subtract(i, 'year').format("YYYY"))
        }
        this.getData()
    }
}

</script>