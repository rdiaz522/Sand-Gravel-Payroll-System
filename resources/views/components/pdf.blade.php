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
                @php
                    $dateNow = (new \DateTime('Asia/Manila'))->format('Y-m-d h:i: A');
                    $rows = 1;
                @endphp
                @foreach ($collections as $employeeModel)

                <table class="table" style="width: 100%; border:1px solid black;"> 
                    <tr> <th class="slip-name">DATE: {{$dateNow}}</th></tr>
                    <tr>
                        @php
                            $fullname = getEmployeeFullname($employeeModel->id);
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
                        <th class="regular-name">DEPARTMENT</th>
                        <th class="regular-name">NET.PAY</th>
                    </tr>
                    @foreach ($departments as $item)
                        @php
                          $total_pay = (float)$employeeModel->timeLogs()
                                ->where('department_id',$item->id)->whereBetween('log_date', [$startDate,$endDate])
                                ->sum('total_pay');
                        @endphp
                        @if ($total_pay != 0)
                        <tr>
                            <th class="regular-name">{{$item->name}}</th>
                            <th class="regular-name">P{{number_format($total_pay, 2, '.', '')}}</th>
                        </tr>
                        @endif
                    @endforeach
                    <tr>
                        <th class="slip-name2">CASH ADVANCE</th>
                        <th class="regular-name">{{'P' . number_format($totalCashAdvances, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th  class="regular-name">Total Cash Advance Balance</th>
                        <th  class="regular-name">P{{number_format($overTotalCashAdvance, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">CASH DEDUCTION</th>
                        <th class="regular-name">P{{number_format($totalCashDeductions, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">SSS</th>
                        <th class="regular-name">P{{number_format($SSS, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">PAGIBIG</th>
                        <th class="regular-name">P{{number_format($pagibig, 2, '.', '')}}</th>
                    </tr>
                    <tr class="separated">
                        <th class="slip-name2">PHILHEALTH</th>
                        <th class="regular-name">P{{number_format($philhealth, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name2">TOTAL GROSS INCOME</th>
                        <th class="regular-name">P{{number_format($gross, 2, '.', '')}}</th>
                    </tr>
                </table>
                <hr>
                @if ($rows == 2)
                    <div class="page-break"></div>
                   @php
                       $rows = 0;
                   @endphp
                @endif
                @php
                    $rows++;
                @endphp
                @endforeach

            </div>
        </div>
    </div>
</body>
</html>