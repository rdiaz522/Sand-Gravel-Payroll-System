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
                    $dateNow = (new \DateTime('Asia/Manila'))->format('Y-m-d h:i A');
                    $rows = 1;
                @endphp
                @foreach ($collections as $employeeModel)
                @php
                            $fullname = getEmployeeFullname($employeeModel->id);
                            $departmentAssigned = [];
                            $total_each_pay = [];
                            $SSS = 0;
                            $pagibig = 0;
                            $philhealth = 0;
                            $cashAdvanceModel = $employeeModel->cashAdvance();

                            $cashDeductions = collect($employeeModel->cashDeduction()
                            ->whereBetween('cash_deduction_date', [$startDate,$endDate])->get());
                            $overAllTotalCashDedcution = $cashDeductions->sum('cash_deduction');
                            $cashAdvanceCollection = $cashAdvanceModel->get();
                            $totalCashAdvances = (float)$cashAdvanceModel->sum('cash_advance');
 
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
                            $gross = (float)$netPay - (float)$overAllTotalCashDedcution - (float)$totalContribution;
                        @endphp
                <table class="table" style="width: 100%; border:1px solid black;"> 
                    <tr>
                        <th class="slip-name">DATE: {{$dateNow}}</th>
                        <th class="slip-name">PAYSLIP DATE: {{$startDate}} to {{$endDate}}</th>
                    </tr>
                    <tr>
                        <th class="slip-name">PAYROLL SLIP</th>
                        <th class="slip-name">-</th>
                    </tr>
                    <tr>
                        <th class="regular-name">Employee Name: {{$fullname}}</th>
                        <th class="slip-name">-</th>
                    </tr>
                    <tr>
                        <th class="regular-name">EARNINGS</th>
                        <th class="slip-name">-</th>
                    </tr>
                    <tr>
                        <th class="regular-name">DEPARTMENT</th>
                        <th class="regular-name">GROSS</th>
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
                        <th class="regular-name">CASH ADVANCE BALANCE</th>
                        <th class="regular-name">AMOUNT</th>
                    </tr>

                    @foreach ($cashAdvanceCollection as $item)
                        @php
                            $getName = getCashAdvanceDescription($item->cash_advance_description);
                            $amount = (float)$item->cash_advance;
                        @endphp
                        <tr>
                            <th  class="regular-name">{{$getName}}</th>
                            <th  class="regular-name">P{{number_format($amount, 2, '.', '')}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th  class="regular-name">Total</th>
                        <th  class="regular-name">P{{number_format($totalCashAdvances, 2, '.', '')}}</th>
                    </tr>

                    <tr style="border:1px solid black;">
                        <th class="regular-name">CASH DEDUCTION</th>
                        <th class="regular-name">AMOUNT</th>
                    </tr>
                    @foreach ($cashAdvanceCollection as $item)
                        @php
                            $getName = getCashAdvanceDescription($item->cash_advance_description);
                            $deduction = $cashDeductions->where('cash_advance_id', $item->id)->sum('cash_deduction');
                        @endphp
                         <tr>
                            <th class="regular-name">{{$getName}}</th>
                            <th class="regular-name">P{{number_format($deduction, 2, '.', '')}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="regular-name">SSS</th>
                        <th class="regular-name">P{{number_format($SSS, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="regular-name">PAGIBIG</th>
                        <th class="regular-name">P{{number_format($pagibig, 2, '.', '')}}</th>
                    </tr>
                    <tr class="separated">
                        <th class="regular-name">PHILHEALTH</th>
                        <th class="regular-name">P{{number_format($philhealth, 2, '.', '')}}</th>
                    </tr>
                    <tr>
                        <th class="regular-name">NET. PAY</th>
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