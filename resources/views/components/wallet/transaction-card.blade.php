@props(['transaction'])

<div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl hover:bg-gray-50 transition-colors">
    <div class="flex items-center space-x-4">
        <div class="w-10 h-10 {{ $transaction->type === 'credit' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
            <i class="{{ $transaction->type === 'credit' ? 'fas fa-arrow-up text-green-600' : 'fas fa-arrow-down text-red-600' }}"></i>
        </div>
        <div>
            <p class="font-medium text-gray-800">{{ $transaction->description }}</p>
            <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
    <div class="text-right">
        <p class="font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
            {{ $transaction->formatted_amount }}
        </p>
        <p class="text-sm text-gray-500">Balance: {{ $transaction->formatted_balance_after }}</p>
    </div>
</div> 