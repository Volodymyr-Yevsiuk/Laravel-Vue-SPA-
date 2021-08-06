@extends('layouts.auth')
@section('content')
    <div id="formContent">
        <!-- Tabs Titles -->
        <h2 class="active"> Вхід </h2>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" id="email" class="auth_input" placeholder="Введіть електронну пошту" name="email" value="{{old('email')}}" required autofocus>
            <input type="password" id="password" class="auth_input" name="password" placeholder="Введіть пароль" value="{{old('password')}}" required autocomplete="current-password" >
            <input type="submit" class="fadeIn fourth" value="Увійти">

            <!-- Already register? -->
            <div id="formFooter">
                <a class="underlineHover" href="{{ route('register') }}">
                   Ще не зареєструвались?
                </a>
                @if (Route::has('password.request'))
                        <a class="underlineHover" href="{{ route('password.request') }}">
                            Забули пароль?
                        </a>
                @endif
            </div>
        </form>
    </div>
@endsection
