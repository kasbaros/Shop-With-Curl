@if($showAddNote)
    <div class="tf-mini-cart-tool-openable add-note fixed inset-0 z-50">
        <div class="overplay tf-mini-cart-tool-close bg-black bg-opacity-50 absolute inset-0" wire:click="toggleAddNote"></div>
        <div class="tf-mini-cart-tool-content bg-white rounded-lg p-6 w-full max-w-sm absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <label for="Cart-note" class="tf-mini-cart-tool-text flex items-center space-x-2 mb-4">
                <div class="icon text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="currentColor">
                        <path d="M5.12187 16.4582H2.78952C2.02045 16.4582 1.39476 15.8325 1.39476 15.0634V2.78952C1.39476 2.02045 2.02045 1.39476 2.78952 1.39476H11.3634C12.1325 1.39476 12.7582 2.02045 12.7582 2.78952V7.07841C12.7582 7.46357 13.0704 7.77579 13.4556 7.77579C13.8407 7.77579 14.1529 7.46357 14.1529 7.07841V2.78952C14.1529 1.25138 12.9016 0 11.3634 0H2.78952C1.25138 0 0 1.25138 0 2.78952V15.0634C0 16.6015 1.25138 17.8529 2.78952 17.8529H5.12187C5.50703 17.8529 5.81925 17.5407 5.81925 17.1555C5.81925 16.7704 5.50703 16.4582 5.12187 16.4582Z"></path>
                        <path d="M15.3882 10.0971C14.5724 9.28136 13.2452 9.28132 12.43 10.0965L8.60127 13.9168C8.51997 13.9979 8.45997 14.0979 8.42658 14.2078L7.59276 16.9528C7.55646 17.0723 7.55292 17.1993 7.58249 17.3207C7.61206 17.442 7.67367 17.5531 7.76087 17.6425C7.84807 17.7319 7.95768 17.7962 8.07823 17.8288C8.19879 17.8613 8.32587 17.8609 8.44621 17.8276L11.261 17.0479C11.3769 17.0158 11.4824 16.9543 11.5675 16.8694L15.3882 13.0559C16.2039 12.2401 16.2039 10.9129 15.3882 10.0971ZM10.712 15.7527L9.29586 16.145L9.71028 14.7806L12.2937 12.2029L13.2801 13.1893L10.712 15.7527Z"></path>
                    </svg>
                </div>
                <span class="font-medium">Add Order Note</span>
            </label>
            <textarea name="note" id="Cart-note" placeholder="How can we help you?" class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 min-h-[100px]"></textarea>
            <div class="tf-cart-tool-btns justify-content-center mt-4">
                <div class="tf-mini-cart-tool-primary text-center w-100 fw-6 tf-mini-cart-tool-close py-2 font-medium text-gray-700 hover:text-gray-900 cursor-pointer"
                     wire:click="toggleAddNote">
                    Close
                </div>
            </div>
        </div>
    </div>
@endif
