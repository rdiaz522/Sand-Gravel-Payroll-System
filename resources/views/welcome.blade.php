@extends('layouts.app')

@section('content')
    <div class="container"> 
       <div class="row">
           <div class="col-sm-2 col-lg-2">

           </div>
           <div class="col-sm-8 col-lg-8">
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Payroll System</h5>
                    @foreach($errors->all() as $error)
                    <li class="text-danger">{{$error}}</li>
                    @endforeach
                    <form action="authenticate" method="POST">
                        @csrf
                        @method('POST')
                     <div class="form-group">
                         <label for="Username">Username</label>
                         <input type="text" class="form-control" name="username" placeholder="Enter your username..">
                     </div>
                     <div class="form-group">
                         <label for="Password">Password</label>
                         <input type="password" class="form-control" name="password" placeholder="Enter your password..">
                     </div>
                     <button type="submit" class="btn btn-primary float-right">Login</button>
                    </form>
                 </div>
            </div>
        </div>
        <div class="col-sm-2 col-lg-2">

        </div>
       </div>
    </div>
@endsection
