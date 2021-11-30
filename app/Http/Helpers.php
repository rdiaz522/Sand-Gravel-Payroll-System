<?php

use App\Models\CashAdvanceDeduction;
use App\Models\CashAdvanceDescription;
use App\Models\Employees;
use App\Models\Location;

if (!function_exists('get_color')) {
    function get_color()
    {
        return 'blue';
    }
}

if (!function_exists('getDepartments')) {
    function getDepartments()
    {
        $departments = Location::all();
        return $departments;
    }
}

if (!function_exists('getEmployeeFullname')) {
    function getEmployeeFullname($id)
    {
        $employee = Employees::where('id', $id)->select(['firstname','middlename','lastname'])->first();
        $fullName = '';
        if($employee instanceof Employees && $employee->exists) {
            $fullName = $employee->firstname . ' ' . $employee->middlename . '. ' . $employee->lastname;
        }
        return $fullName;
    }
}

if (!function_exists('getDepartmentName')) {
    function getDepartmentName($id)
    {
        $department = Location::where('id', $id)->select(['id','name'])->first();
        $fullName = '';
        if($department instanceof Location && $department->exists) {
            $fullName = $department->name;
        }
        return $fullName;
    }
}

if (!function_exists('getDepartmentNameByName')) {
    function getDepartmentNameByName($name)
    {
        $department = Location::where('name', $name)->select(['id'])->first();
        $id = '';
        if($department instanceof Location && $department->exists) {
            $id = $department->id;
        }
        
        return $id;
    }
}


if (!function_exists('getCashAdvanceDescription')) {
    function getCashAdvanceDescription($id)
    {
        $cashAdvanceDesc = CashAdvanceDescription::where('id', $id)->select(['name'])->first();
        $name = '';
        if($cashAdvanceDesc instanceof CashAdvanceDescription && $cashAdvanceDesc->exists) {
            $name = $cashAdvanceDesc->name;
        }
        
        return $name;
    }
}

if (!function_exists('getCashAdvanceById')) {
    function getCashAdvanceById($id)
    {
        $cashAdvanceDesc = CashAdvanceDeduction::where('id', $id)->select(['cash_advance_description'])->first();
        $id = '';
        if($cashAdvanceDesc instanceof CashAdvanceDeduction && $cashAdvanceDesc->exists) {
            $id = $cashAdvanceDesc->cash_advance_description;

           return getCashAdvanceDescription($id);
        }
        
        return $id;
    }
}

if (!function_exists('getCashAdvanceAmountById')) {
    function getCashAdvanceAmountById($id)
    {
        $cashAdvanceDesc = CashAdvanceDeduction::where('id', $id)->select(['cash_advance'])->first();
        $cash = '';
        if($cashAdvanceDesc instanceof CashAdvanceDeduction && $cashAdvanceDesc->exists) {
            $cash = (float)$cashAdvanceDesc->cash_advance;
        }
        
        return $cash;
    }
}



if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        $cc = \Carbon\Carbon::parse($date)->format('Y-m-d');

        return $cc;
    }
}
