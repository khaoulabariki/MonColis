<div class="flex items-center gap-1 bg-white border border-slate-200 rounded-xl p-1 shadow-sm select-none">
    @foreach(['fr' => 'FR', 'en' => 'EN', 'ary' => 'الدارجة'] as $localeCode => $localeLabel)
        <a href="{{ route('lang.switch', $localeCode) }}"
           class="text-[11px] font-black px-2.5 py-1.5 rounded-lg transition whitespace-nowrap {{ app()->getLocale() === $localeCode ? 'bg-brand-blue text-white' : 'text-slate-500 hover:bg-slate-100' }}">
            {{ $localeLabel }}
        </a>
    @endforeach
</div>
