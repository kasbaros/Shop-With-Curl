@if($showEstimateShipping)
    <div class="tf-mini-cart-tool-openable estimate-shipping fixed inset-0 z-50">
        <div class="overplay tf-mini-cart-tool-close bg-black bg-opacity-50 absolute inset-0" wire:click="toggleEstimateShipping"></div>
        <div class="tf-mini-cart-tool-content bg-white rounded-lg p-6 w-full max-w-sm absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="tf-mini-cart-tool-text flex items-center space-x-2 mb-6">
                <div class="icon text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="15" viewBox="0 0 21 15" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.441406 1.13155C0.441406 0.782753 0.724159 0.5 1.07295 0.5H12.4408C12.7896 0.5 13.0724 0.782753 13.0724 1.13155V2.91575H16.7859C18.8157 2.91575 20.5581 4.43473 20.5581 6.42296V11.8878C20.5581 12.2366 20.2753 12.5193 19.9265 12.5193H18.7542C18.4967 13.6534 17.4823 14.5 16.2703 14.5C15.0582 14.5 14.0439 13.6534 13.7864 12.5193H7.20445C6.94692 13.6534 5.93259 14.5 4.72054 14.5C3.50849 14.5 2.49417 13.6534 2.23664 12.5193H1.07295C0.724159 12.5193 0.441406 12.2366 0.441406 11.8878V1.13155ZM2.26988 11.2562C2.57292 10.1881 3.55537 9.40578 4.72054 9.40578C5.88572 9.40578 6.86817 10.1881 7.17121 11.2562H11.8093V1.76309H1.7045V11.2562H2.26988ZM13.0724 4.17884V6.68916H19.295V6.42296C19.295 5.2348 18.2252 4.17884 16.7859 4.17884H13.0724ZM19.295 7.95226H13.0724V11.2562H13.8196C14.1227 10.1881 15.1051 9.40578 16.2703 9.40578C17.4355 9.40578 18.4179 10.1881 18.7209 11.2562H19.295V7.95226ZM4.72054 10.6689C4.0114 10.6689 3.43652 11.2437 3.43652 11.9529C3.43652 12.662 4.0114 13.2369 4.72054 13.2369C5.42969 13.2369 6.00456 12.662 6.00456 11.9529C6.00456 11.2437 5.42969 10.6689 4.72054 10.6689ZM16.2703 10.6689C15.5611 10.6689 14.9863 11.2437 14.9863 11.9529C14.9863 12.662 15.5611 13.2369 16.2703 13.2369C16.9794 13.2369 17.5543 12.662 17.5543 11.9529C17.5543 11.2437 16.9794 10.6689 16.2703 10.6689Z"></path>
                    </svg>
                </div>
                <span class="font-medium">Estimate Shipping</span>
            </div>
            <div class="field mb-4">
                <p class="mb-1 text-sm font-medium">Country</p>
                <select class="tf-select w-100 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" id="ShippingCountry_CartDrawer-Form" name="address[country]" data-default="">
                    <option value="---" data-provinces="[]">---</option>
                    <option value="Australia">Australia</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Tanzania">Tanzania</option>
                </select>
            </div>
            <div class="field mb-6">
                <p class="mb-1 text-sm font-medium">Zip code</p>
                <input type="text" name="text" placeholder="" class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="tf-cart-tool-btns space-y-3">
                <a href="#" class="tf-btn fw-6 justify-content-center btn-fill w-100 animate-hover-btn radius-3 w-full bg-black text-white py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-800 transition-colors">
                    <span>Estimate</span>
                </a>
                <div class="tf-mini-cart-tool-primary text-center fw-6 w-100 tf-mini-cart-tool-close py-2 font-medium text-gray-700 hover:text-gray-900 cursor-pointer"
                     wire:click="toggleEstimateShipping">
                    Cancel
                </div>
            </div>
        </div>
    </div>
@endif
