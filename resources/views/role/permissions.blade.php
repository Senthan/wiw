@extends('layouts.app')
@section('content')
    {!! breadcrumb($breadcrumb) !!}
    <div class="html ui top attached segment">
        <div class="ui grid">
            <div class="twelve wide stretched column">
                <div class="ui segment">
                    <div>
                        <div class="ui secondary pointing menu">
                            @foreach($policyCategories as $category)
                                <a href="{{ route('role.show', ['role' => $role->id, 'policyCategory' => $category->id]) }}" class="item {{ $policyCategory->id == $category->id ? 'active' : '' }}" data-tab="policy-{{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <h4 class="ui dividing header">{{ title_case(str_replace('_', ' ', snake_case($policy->name)) . ' module permissions') }}</h4>

                    <div class="ui form">
                        <div class="fields">
                            @foreach($policyMethods as $policyMethod)
                                <div class="field">
                                    <div class="ui checkbox">
                                        <input type="checkbox" value="{{ $policyMethod->id }}" tabindex="0" class="hidden policy-method-auth" {{ $policyMethod->authorized ? 'checked' : '' }}>
                                        <label>{{ studly_case($policyMethod->name) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <h4 class="ui dividing header">Decryptable fields</h4>
                        <div class="fields">
                            @forelse ($policyEncryptions as $policyEncryption)
                                <div class="field">
                                    <div class="ui checkbox">
                                        <input type="checkbox" value="{{ $policyEncryption->id }}" tabindex="0" class="hidden policy-encryption-auth" {{ $policyEncryption->authorized ? 'checked' : '' }}>
                                        <label>{{ studly_case($policyEncryption->name) }}</label>
                                    </div>
                                </div>
                            @empty
                                <div class="field">
                                    <p>This policy does not have any encrypted fields.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="ui yellow piled segment">
                            <div class="fields">
                                <div class="ui green buttons">
                                    <a href="" class="ui button">Change Category</a>
                                    <div class="ui floating dropdown icon button">
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            @foreach($policyCategories as $category)
                                                <a href="{{ route('category.policy.update', ['role' => $role->id, 'policyCategory' => $category->id, 'policy' => $policy->id]) }}" class="item">{{ $category->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="four wide column">
                <div class="ui vertical fluid right tabular menu">
                    @foreach($policies as $policyObj)
                        <a class="item {{ $policy->id == $policyObj->id ? 'active' : '' }}" href="{{ route('role.show', ['role' => $role->id, 'policyCategory' => $policyObj->policyCategory->id, 'policy' => $policyObj->id]) }}">{{ $policyObj->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var errorCallBack = false;
            
            $('input.policy-method-auth').parent('.ui.checkbox').checkbox({
                onChecked: function () {
                    if(errorCallBack) {
                        errorCallBack = false;
                        return;
                    }
                    updatePolicyMethod($(this), 1);
                },
                onUnchecked: function () {
                    if(errorCallBack) {
                        errorCallBack = false;
                        return;
                    }
                    updatePolicyMethod($(this), 0);
                }
            });
            $('input.policy-encryption-auth').parent('.ui.checkbox').checkbox({
                onChecked: function () {
                    if(errorCallBack) {
                        errorCallBack = false;
                        return;
                    }
                    updatePolicyEncryption($(this), 1);
                },
                onUnchecked: function () {
                    if(errorCallBack) {
                        errorCallBack = false;
                        return;
                    }
                    updatePolicyEncryption($(this), 0);
                }
            });
            function updatePolicyMethod(method, authorized) {
                var data = {};
                data._token = '{{ csrf_token() }}';
                data.method = method.val();
                data.authorized = authorized;
                $.ajax({
                    url : '{{ route('role.update.method', ['role' => $role->id, 'policy' => $policy->id]) }}',
                    method : 'PATCH',
                    data:data,
                    success: function () {
                        showSuccess();
                    },
                    error: function () {
                        errorCallBack = true;
                        method.parent('.ui.checkbox').checkbox('toggle');
                        sweetAlert('This action is unauthorized.', '', 'error');
                        console.clear();
                    }
                });
            }
            function updatePolicyEncryption(encryption, authorized) {
                var data = {};
                data._token = '{{ csrf_token() }}';
                data.encryption = encryption.val();
                data.authorized = authorized;
                $.ajax({
                    url : '{{ route('role.update.encryption', ['role' => $role->id, 'policy' => $policy->id]) }}',
                    method : 'PATCH',
                    data:data,
                    success: function () {
                        showSuccess();
                    },
                    error: function () {
                        errorCallBack = true;
                        encryption.parent('.ui.checkbox').checkbox('toggle');
                        sweetAlert('This action is unauthorized.', '', 'error');
                        console.clear();
                    }
                });
            }
            function showSuccess() {
                toastr.options = {
                    "positionClass": "toast-bottom-right"
                };
                toastr.success('Permission updated!')
            }
            $('.ui.dropdown').dropdown();
        });
    </script>
@endsection