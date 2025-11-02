<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() === 'ar') dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <title>{{ __('Admin Login') }} - {{ config('app.name') }}</title>
    <x-dashboard.styles />
    <x-dashboard.scripts-header />
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:slnt,wght@-11..11,200..1000&display=swap" rel="stylesheet">
        <style>
            body * {
                font-family: 'Cairo', sans-serif !important;
            }
        </style>
    @endif
</head>

<body style="background-color: rgb(19,41,70)">
    <div class="container v-center">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-5 col-md-5">
                <div class="my-5 border-0 shadow-lg card o-hidden">
                    <div class="p-0 card-body">
                        <div class="row">
                            <div class="col-lg-12" style="background-color: #ccad75">
                                <div class="p-5">

                                    <div class="text-center">
                                        <img src="{{ asset(config('app.logo')) }}" alt="Logo" class="mb-4"
                                            style="width: 300px; border-radius: 10px;">
                                        <h1 class="mb-4 text-gray-900 h4">{{ __('Admin Login') }}</h1>
                                    </div>

                                    <form action="{{ route('login') }}" class="user" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input id="email" type="email" class="form-control form-control-user"
                                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                                autofocus placeholder="{{ __('Email') }}">
                                        </div>
                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control form-control-user"
                                                name="password" placeholder="{{ __('Password') }}" required>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-user btn-block" style="background-color: #e8e7e6">{{ __('Login') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-dashboard.scripts-footer />
</body>

</html>
