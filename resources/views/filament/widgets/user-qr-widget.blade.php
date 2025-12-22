<div class="rounded-xl border border-gray-200/10 bg-gray-800 p-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold">QR Code do seu cartão</h3>
        @if ($url)
            <a href="{{ $url }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg bg-primary-500 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-600">
                Visualizar cartão
            </a>
        @endif
    </div>

    @if (!$url)
        <p class="mt-4 text-sm text-gray-300">Você precisa estar autenticado e ter um slug válido para gerar o QR Code.</p>
    @else
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="flex flex-col items-center">
                <div class="rounded-xl border border-white/10 bg-white p-3">
                    <img src="{{ $qrApi }}" alt="QR Code para {{ $url }}" class="h-60 w-60 object-contain">
                </div>
                <p class="mt-2 text-xs text-gray-300">{{ $url }}</p>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ $qrApi }}"
                   download="qrcode-{{ $user->slug }}.png"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Baixar QR Code (PNG)
                </a>

                <button type="button"
                        onclick="navigator.clipboard.writeText('{{ $url }}')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Copiar link
                </button>

                <div class="text-xs text-gray-400">
                    Dica: Imprima o QR em flyers, embalagens ou vitrine para facilitar o acesso ao seu cartão.
                </div>
            </div>
        </div>
    @endif
</div>