@extends('layouts.auth')
@section('content')
    <div id="formContent">
        <!-- Tabs Titles -->
        <h2 class="active"> Реєстрація </h2>

        <!-- Login Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="name" id="name" class="auth_input" name="name" placeholder="Введіть ваше ім'я" required autofocus>
            <input type="email" id="email" class="auth_input" name="email" placeholder="Введіть електронну пошту" required autofocus>
            <input type="password" id="password" class="auth_input" name="password" placeholder="Введіть пароль" required autocomplete="new-password" >
            <input type="password" id="password_confirmation" class="auth_input" name="password_confirmation" placeholder="Підтвердіть пароль" required autocomplete="new-password" >
            <input type="submit" class="fadeIn fourth" value="Зареєструватись">

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
        @endif

        <!-- Already register? -->
            <div id="formFooter">
                @if (Route::has('password.request'))
                    <a class="underlineHover" href="{{ route('login') }}">Вже зареєстровані?</a>
                @endif
            </div>
        </form>
    </div>
@endsection
