<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        </style>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                @foreach ($collections as $employeeModel)
                <table class="table" style="width: 100%"> 
                    <tr>
                        @php
                            
                            $fullname = $employeeModel->lastname . ' ' . $employeeModel->firstname . ' ' . $employeeModel->middlename;
                            $departmentAssigned = [];
                            $total_each_pay = [];
                            $SSS = 0;
                            $pagibig = 0;
                            $philhealth = 0;
                           
                            
                            $cashAdvanceModel = $employeeModel->cashAdvance()
                            ->whereBetween('cash_advance_date', [$startDate,$endDate]);
                            $totalCashDeductions = $employeeModel->cashDeduction()
                            ->whereBetween('cash_deduction_date', [$startDate,$endDate])->sum('cash_deduction');

                            $cashAdvanceCollection = $cashAdvanceModel->get();
                            $totalCashAdvances = (float)$cashAdvanceModel->sum('cash_advance');

                            $overTotalCashAdvance = (float)$totalCashAdvances - (float)$totalCashDeductions;
                            
                            $departments = getDepartments();
                            
                            $netPay = $employeeModel->timeLogs()->whereBetween('log_date', [$startDate,$endDate])->sum('total_pay');
                            $contributions = $employeeModel->contributions()
                            ->whereBetween('contribution_date', [$startDate,$endDate])
                            ->latest()->first();

                            if($contributions instanceof Contribution && $contributions->exists) {
                                $SSS = (float)$contributions->sss;
                                $pagibig = (float)$contributions->pagibig;
                                $philhealth = (float)$contributions->philhealth;
                            }
                            $totalContribution = (float)$SSS + (float)$pagibig + (float)$philhealth;
                            $gross = (float)$netPay - (float)$totalCashDeductions - (float)$totalContribution;
                        @endphp

                        <th class="slip-name">PAYROLL SLIP</th>
                        <th class="slip-name2">PAYSLIP DATE: {{$startDate}} to {{$endDate}}</th>
                    </tr>
                    <tr>
                        <th class="regular-name">Employee Name: {{$fullname}}</th>
                    </tr>
                    <tr>
                        <th class="regular-name">Employee Type: Regular</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">EARNINGS</th>
                    </tr>
                    <tr>
                        <th class="regular-name">Department</th>
                        <th class="regular-name">Net. Pay</th>
                    </tr>
                    @foreach ($departments as $item)
                        <tr>
                            <th class="regular-name">{{$item->name}}</th>
                            <th>@php
                                echo 'P' . (float)$employeeModel->timeLogs()
                                ->where('department_id',$item->id)->whereBetween('log_date', [$startDate,$endDate])
                                ->sum('total_pay');
                            @endphp</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="slip-name2">CASH ADVANCE</th>
                    </tr>
                    <tr>
                        <th class="regular-name">Total Cash Advance</th>
                        <th class="regular-name">{{'P' . $totalCashAdvances}}</th>
                    </tr>
                    <tr>
                        <th  class="regular-name">Total Cash Advance Balance</th>
                        <th  class="regular-name">P{{$overTotalCashAdvance}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">CASH DEDUCTION</th>
                        <th class="regular-name">P{{$totalCashDeductions}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">SSS</th>
                        <th class="regular-name">P{{$SSS}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">PAGIBIG</th>
                        <th class="regular-name">P{{$pagibig}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">PHILHEALTH</th>
                        <th class="regular-name">P{{$philhealth}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">TOTAL GROSS INCOME</th>
                        <th class="regular-name">P{{$gross}}</th>
                    </tr>
                </table>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
    <div class="page-break"></div>
</body>
</html>