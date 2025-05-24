<x-guest-layout>
    {{-- Background Image with Blur Effect --}}
    <div class="absolute inset-0" style="background-image: url('https://www.mdn.dz/site_em_anp/sommaire/formation/images/enpei1.jpg'); background-size: 120%; background-position: center;">
        <div class="absolute inset-0" style="backdrop-filter: blur(8px); background-color: rgba(0,0,0,0.4);"></div>
    </div>
    
   
    
    <style>
        .form-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .marquee-container {
            width: 100%;
            overflow: hidden;
            background-color: #1e40af !important;
        }
        .marquee-text {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 20s linear infinite;
            font-size: 1.5rem;
        }
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
    </style>
    
    <div class="bg-white p-8 rounded-lg form-shadow w-full max-w-md relative z-10">
        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        {{-- Logo Area --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-gray-500 mt-2">Please sign in to your account</p>
        </div>
        
        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            {{-- Email Input --}}
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input 
                    id="email" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="Enter your email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            
            {{-- Password Input --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-1">
                    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700" />
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>
                <x-text-input
                    id="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            
            {{-- Remember Me --}}
            <div class="flex items-center mb-6">
                <input 
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    name="remember"
                >
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    {{ __('Remember me') }}
                </label>
            </div>
            
            {{-- Login Button --}}
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                {{ __('Sign In') }}
            </button>
        </form>
        
        {{-- Sign Up Link --}}
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Create Account</a>
            </p>
        </div>
    </div>
</x-guest-layout>