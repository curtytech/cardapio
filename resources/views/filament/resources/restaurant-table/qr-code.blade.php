<div class="flex flex-col items-center justify-center space-y-4">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($url) }}" 
             alt="QR Code Mesa {{ $record->number }}" 
             class="w-64 h-64"
             loading="lazy"
        >
    </div>
    
    <div class="text-center space-y-1">
        <p class="text-sm font-medium text-gray-500">Link direto:</p>
        <a href="{{ $url }}" target="_blank" class="text-primary-600 hover:text-primary-500 hover:underline break-all text-sm">
            {{ $url }}
        </a>
    </div>

    <div class="text-xs text-gray-400">
        Escaneie e imprima este QR Code para acessar o cardápio desta mesa.
    </div>
</div>