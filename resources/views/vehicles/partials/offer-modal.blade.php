<!-- Offer Modal -->
<div id="offerModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeOfferModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Make an Offer</h3>
                    <button onclick="closeOfferModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form id="offerForm" method="POST" action="{{ route('offers.store') }}">
                    @csrf
                    <input type="hidden" name="vehicle_id" id="offerVehicleId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle</label>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p id="offerVehicleInfo" class="font-medium text-gray-900"></p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Offer Amount (Nu.)</label>
                        <input type="number" name="offer_amount" required min="0" step="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message to Seller (Optional)</label>
                        <textarea name="message" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Add any questions or special requests..."></textarea>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                            Submit Offer
                        </button>
                        <button type="button" onclick="closeOfferModal()" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
