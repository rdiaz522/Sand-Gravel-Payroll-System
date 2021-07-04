@extends('layouts.app')

@section('content')
    
<div id="wrapper">
     <!-- Sidebar -->
     <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-text mx-3">ADMINISTRATOR</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="#">
                <span>Payroll System</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Menu
        </div>
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <router-link class="nav-link" to="/users"> <span>Employee Info Management</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/payroll"> <span>Employee Daily Time Record</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/cashadvance"> <span>Cash Advance</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/cashdeduction"> <span>Cash Deduction</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/cashcontribution"> <span>Contributions</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/report"> <span>Report</span></router-link>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/expense"> <span>Expenses</span></router-link>
        </li>
        
        <li class="nav-item">
            <router-link class="nav-link" to="/settings"> <span>Settings</span></router-link>
        </li>

    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
                    <a href="{{route('logout')}}" class="btn btn-primary shadow-sm mt-2">Logout</a>
                </div>
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
    
@endsection