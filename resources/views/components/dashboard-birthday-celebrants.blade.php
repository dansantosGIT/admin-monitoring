@props(['celebrants', 'date'])

<section class="card birthday-card" aria-labelledby="birthdayCelebrantsTitle">
    <div class="card__header">
        <div class="birthday-card__heading">
            <span class="birthday-card__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h16M5 20v-7h14v7M3 13h18M7 10V7.5a2.5 2.5 0 0 1 5 0V10M12 10V6a2 2 0 1 1 4 0v4M5 13c0-1.7 1.1-3 2.5-3s2.5 1.3 2.5 3M14 13c0-1.7 1.1-3 2.5-3s2.5 1.3 2.5 3"/><path d="M8 4.5h.01M18 5.5h.01" stroke-linecap="round"/></svg>
            </span>
            <div>
                <h2 class="card__title" id="birthdayCelebrantsTitle">Birthday Celebrants</h2>
                <p class="card__subtitle">{{ $date->format('F j, Y') }}</p>
            </div>
        </div>
    </div>

    @if($celebrants->isEmpty())
        <div class="birthday-empty">No birthdays today.</div>
    @else
        <div class="birthday-list">
            @foreach($celebrants->take(5) as $celebrant)
                <div class="birthday-person">
                    <div class="birthday-avatar">
                        @if($celebrant->photo_path)
                            <img src="{{ asset('storage/' . $celebrant->photo_path) }}" alt="{{ $celebrant->full_name }}">
                        @else
                            {{ collect(explode(' ', $celebrant->full_name))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}
                        @endif
                    </div>
                    <div class="birthday-person__details">
                        <strong>{{ $celebrant->full_name }}</strong>
                        <span>{{ $celebrant->department ?: 'No department' }} · {{ $celebrant->position ?: 'Position not recorded' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        @if($celebrants->count() > 5)
            <div class="birthday-more">+{{ $celebrants->count() - 5 }} more celebrant{{ $celebrants->count() - 5 === 1 ? '' : 's' }}</div>
        @endif
    @endif
</section>

@once
    @push('styles')
    <style>
        .birthday-card{border-left:3px solid #f59e0b}.birthday-card__heading{display:flex;align-items:center;gap:10px}.birthday-card__icon{display:grid;place-items:center;width:34px;height:34px;border-radius:10px;background:#fef3c7;color:#b45309}.birthday-card__icon svg{width:20px;height:20px}.birthday-list{padding:0 1.25rem 1rem}.birthday-person{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #f9fafb}.birthday-person:last-child{border-bottom:0}.birthday-avatar{display:grid;place-items:center;width:36px;height:36px;flex:0 0 36px;overflow:hidden;border-radius:50%;background:#fff3cd;color:#92400e;font-size:12px;font-weight:800}.birthday-avatar img{width:100%;height:100%;object-fit:cover}.birthday-person__details{min-width:0}.birthday-person__details strong,.birthday-person__details span{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.birthday-person__details strong{color:#111827;font-size:.82rem}.birthday-person__details span{margin-top:2px;color:#6b7280;font-size:.7rem}.birthday-empty{padding:0 1.25rem 1.15rem;color:#9ca3af;font-size:.82rem}.birthday-more{padding:8px 1.25rem 12px;border-top:1px solid #f3f4f6;color:#b45309;font-size:.75rem;font-weight:700}
    </style>
@endpush
@endonce
