<!DOCTYPE html>
@php $isRtl = app()->getLocale() === 'ary'; @endphp
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily - {{ __('Connexion') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: {{ $isRtl ? "'Tajawal'" : "'Inter'" }}, sans-serif; }
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .text-brand-orange { color: #FF6B00; }
        .border-brand-blue:focus { border-color: #0A4BB3; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased text-slate-800 min-h-screen flex flex-col justify-between">

    <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="/tracking" class="text-xs font-bold text-slate-400 hover:text-brand-blue flex items-center gap-2 transition group">
            <i class="fas fa-arrow-left text-[10px] transition rtl:rotate-180"></i>
            {{ __("Retour à l'accueil") }}
        </a>
        @include('partials.lang-switcher')
    </header>

    <main class="flex-1 flex flex-col items-center justify-center px-6 pb-24">

        <div class="flex flex-col items-center text-center mb-8 select-none">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-brand-blue rounded-xl flex items-center justify-center relative shadow-sm shadow-blue-900/20">
                    <i class="fas fa-arrow-up text-white text-lg font-black transform rotate-45" style="color: #FF6B00;"></i>
                </div>
                <span class="text-3xl font-black text-brand-blue tracking-tight">Ship<span class="text-brand-orange">ily</span></span>
            </div>
            <span class="text-[10px] font-black text-slate-400 tracking-[0.25em] uppercase mt-2 block">{{ __('LIVRAISON SIMPLIFIÉE') }}</span>
        </div>

        <div class="w-full max-w-sm">

            <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">{{ __('Connectez-vous') }}</h1>
            <p class="text-slate-400 text-sm mb-6">{{ __('Accédez à votre espace Shipily.') }}</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl font-bold text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Adresse email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                        class="w-full h-12 px-4 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue shadow-xs transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Mot de passe') }}</label>
                    <div class="relative flex items-center">
                        <input type="password" name="password" required placeholder=""
                            class="w-full h-12 ps-4 pe-20 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue shadow-xs transition">
                        <button type="button" class="absolute end-4 text-sm font-bold text-brand-orange hover:underline focus:outline-none">
                            {{ __('Afficher') }}
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-blue accent-brand-blue">
                        <span class="text-slate-500 font-medium">{{ __('Se souvenir de moi') }}</span>
                    </label>
                    <a href="#" class="text-brand-blue font-bold text-xs hover:underline">{{ __('Mot de passe oublié ?') }}</a>
                </div>

                <button type="submit" class="w-full bg-brand-blue hover:opacity-95 text-white h-12 rounded-full font-bold text-sm transition shadow-md shadow-blue-900/10 flex items-center justify-center gap-2 cursor-pointer mt-3">
                    {{ __('Se connecter') }} <i class="fas fa-arrow-right text-xs rtl:rotate-180"></i>
                </button>
            </form>

            <div class="mt-8 text-center text-sm">
                <p class="text-slate-400 font-medium">
                    {{ __('Pas encore de compte ?') }}
                    <a href="{{ route('register') }}" class="text-brand-orange font-bold hover:underline ms-0.5">{{ __('Créer un compte') }}</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="w-full max-w-7xl mx-auto px-6 py-4 flex justify-end">
        <div class="w-10 h-10 bg-white rounded-lg shadow-md border border-slate-100 flex items-center justify-center text-slate-400 select-none hover:text-brand-blue transition cursor-pointer">
            <i class="fas fa-shield-alt text-base"></i>
        </div>
    </footer>

</body>
</html>
