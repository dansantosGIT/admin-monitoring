@props(['email' => null])

@php
    $emailValue = trim((string) $email);
    $atPosition = strrpos($emailValue, '@');
    $localPart = $atPosition === false ? $emailValue : substr($emailValue, 0, $atPosition);
    $domainPart = $atPosition === false ? '' : substr($emailValue, $atPosition);
    $maskedEmail = $localPart === ''
        ? 'Hidden email'
        : substr($localPart, 0, min(2, strlen($localPart))) . str_repeat('*', max(3, strlen($localPart) - 2)) . $domainPart;
@endphp

<span class="masked-email" data-masked-email data-reveal-url="{{ route('account.email.reveal') }}" data-masked-value="{{ $maskedEmail }}">
    <span data-email-value>{{ $maskedEmail }}</span>
    @if($emailValue)
        <button type="button" class="masked-email-toggle" data-email-toggle aria-label="Reveal email temporarily" title="Reveal email temporarily">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
        </button>
    @endif
</span>

@once
    @push('styles')
    <style>
        .masked-email{display:inline-flex;align-items:center;gap:5px;max-width:100%}.masked-email [data-email-value]{overflow-wrap:anywhere}.masked-email-toggle{display:inline-grid;place-items:center;width:22px;height:22px;padding:0;border:0;border-radius:5px;background:transparent;color:currentColor;cursor:pointer}.masked-email-toggle:hover{background:rgba(15,98,254,.1)}.masked-email-toggle:focus-visible{outline:2px solid #0f62fe;outline-offset:2px}.masked-email-toggle svg{width:14px;height:14px}
    </style>
    @endpush

    @push('scripts')
    <script>
        document.querySelectorAll('[data-masked-email]').forEach((container) => {
            const value = container.querySelector('[data-email-value]');
            const toggle = container.querySelector('[data-email-toggle]');
            const masked = container.dataset.maskedValue;
            let timeout;
            const remask = () => { window.clearTimeout(timeout); value.textContent = masked; toggle?.setAttribute('aria-label', 'Reveal email temporarily'); toggle?.setAttribute('title', 'Reveal email temporarily'); };
            const reveal = async () => {
                if (value.textContent !== masked) { remask(); return; }
                try {
                    const response = await fetch(container.dataset.revealUrl, {headers: {'Accept': 'application/json'}});
                    if (!response.ok) return;
                    const payload = await response.json();
                    value.textContent = payload.email || masked;
                    toggle?.setAttribute('aria-label', 'Mask email');
                    toggle?.setAttribute('title', 'Mask email');
                    timeout = window.setTimeout(remask, 5000);
                } catch (error) {
                    remask();
                }
            };
            const sidebarUser = container.closest('.sidebar-user');
            if (sidebarUser) sidebarUser.setAttribute('title', masked);
            toggle?.addEventListener('click', reveal);
        });
    </script>
    @endpush
@endonce
