<div class="form-group">
    {!! Form::text('name', null, ['class' => "col-md-12 form-control", 'placeholder' => 'Name']) !!}
    <p class="help-block">{!! ($errors->has('name') ? $errors->first('name') : '') !!}</p>
</div>

<div class="form-group">
    {!! Form::text('email', null, ['class' => "col-md-12 form-control", 'placeholder' => 'Email']) !!}
    <p class="help-block">{!! ($errors->has('email') ? $errors->first('name') : '') !!}</p>
</div>

<div class="form-group">
    <select name="role" class="ui search fluid selection dropdown role-dropdown">
        <option value="">Select Role</option>
        @foreach($roles as $key => $role)
            <option {{ isset($user) && $key == $user->role_id  ? 'selected' : '' }} value="{{ $key }}">{{ $role }}</option>
        @endforeach
    </select>
    <p class="help-block"> {!! ($errors->has('role_id') ? $errors->first('role_id') : '') !!}</p>
</div>

<div class="form-group">
    {!! Form::textarea('description', null, ['class' => "col-md-12 form-control", 'placeholder' => 'Description']) !!}
    <p class="help-block">{!! ($errors->has('description') ? $errors->first('description') : '') !!}</p>
</div>

@section('script')
    <script>
        $(document).ready(function () {
            const roleDropdown  = $('.role-dropdown');
            roleDropdown.dropdown({forceSelection: false});
        });
    </script>
@endsection