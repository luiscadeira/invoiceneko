@extends("layouts.default", ['page_title' => 'Company | Users | Create'])

@section("head")
    <link href="{{ asset(mix('/assets/css/intlTelInput.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Add User</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <form id="company-create-user" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col s12">
                                <h6>Company: {{ $company->name }}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('username') }}" placeholder="Username">
                                <label for="username" class="label-validation">Username</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Email">
                                <label for="email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="full_name" name="full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('full_name') }}" placeholder="Name">
                                <label for="full_name" class="label-validation">Full Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Country">
                                    <option disabled="" selected="selected" value="">Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if(old('country_code') == $country['iso_3166_1_alpha2']) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country_code" class="label-validation">Country</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="timezone" name="timezone" data-parsley-required="true" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" @if(old('timezone') == $timezone) selected @endif> {{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <label for="timezone" class="label-validation">Timezone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ old('phone') }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 left">
                                <label id="rbtn-label" class="rbtn-label" for="gender">Gender</label>
                                <p class="rbtn">
                                    <label for="gender-male">
                                        <input id="gender-male" name="gender" type="radio" value="male" data-parsley-required="true" data-parsley-trigger="change" @if(old('gender') == "male") checked @endif>
                                        <span>Male</span>
                                    </label>
                                </p>
                                <p class="rbtn">
                                    <label for="gender-female">
                                        <input id="gender-female" name="gender" type="radio" value="female" @if(old('gender') == "female") checked @endif>
                                        <span>Female</span>
                                    </label>
                                </p>
                                <span class="helper-text manual-validation"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="roles" name="roles[]" data-parsley-required="true" data-parsley-trigger="change" placeholder="Roles" multiple>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" @if(old('roles') == $role->name) selected @endif>{{ $role->title }}</option>
                                    @endforeach
                                </select>
                                <label for="roles" class="label-validation">Roles</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript" src="{{ asset(mix('/assets/js/intlTelInput.js')) }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initSelectize('#country_code');
            Unicorn.initSelectize('#timezone');
            Unicorn.initSelectize('#roles');
            Unicorn.initPhoneInput('#phone');
            Unicorn.initParsleyValidation('#company-create-user');
        });
    </script>
@stop