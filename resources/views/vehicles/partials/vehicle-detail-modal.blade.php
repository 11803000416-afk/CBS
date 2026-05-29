<!-- Vehicle Detail Modal -->
<div id="vehicleDetailModal" class="hidden fixed inset-0 z-50 flex">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeVehicleModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b flex items-center justify-between p-6 rounded-t-2xl z-10">
                <h3 class="text-2xl font-bold text-gray-900">Vehicle Details</h3>
                <button onclick="closeVehicleModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <input type="hidden" id="vehicleDetailId">
                <input type="hidden" id="detailSellerId">

                <!-- Loading State -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600 text-lg mb-6">Loading vehicle details...</p>
                </div>
            </div>
        </div>
    </div>
</div>
