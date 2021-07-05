<?php

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
            $fullName = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
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

if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        $cc = \Carbon\Carbon::parse($date)->format('Y-m-d');

        return $cc;
    }
}
