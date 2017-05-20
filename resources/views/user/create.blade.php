@extends('layouts.app')
@section('content')
    {!! breadcrumb($breadcrumb) !!}
    {!! Form::open(['url' => route('user.store'), 'user' => 'form', 'class' => 'form-horizontal ui form']) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
        <a class="ui small button" href="{{ route('user.index') }}">User</a>
        </div>
        <div class="panel-body">
            @include('user._form')
        </div>
        <div class="panel-footer">
            <button class="ui small button green" type="submit">Create</button>
            <a class="ui small button" href="{{ route('user.index') }}">Cancel</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection