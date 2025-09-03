@if($showAddGift)
    <div class="tf-mini-cart-tool-openable add-gift fixed inset-0 z-50">
        <div class="overplay tf-mini-cart-tool-close bg-black bg-opacity-50 absolute inset-0" wire:click="toggleAddGift"></div>
        <form class="tf-product-form-addgift">
            <div class="tf-mini-cart-tool-content bg-white rounded-lg p-6 w-full max-w-sm absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="tf-mini-cart-tool-text flex items-start space-x-3 mb-6">
                    <div class="icon text-gray-600 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.99566 2.73409C2.99566 0.55401 5.42538 -0.746668 7.23916 0.463462L8.50073 1.30516L9.7623 0.463462C11.5761 -0.746668 14.0058 0.55401 14.0058 2.73409V3.24744H14.8225C15.9633 3.24744 16.8881 4.17233 16.8881 5.31312V6.82566C16.8881 7.21396 16.5734 7.52873 16.1851 7.52873H15.8905V15.1877C15.8905 15.1905 15.8905 15.1933 15.8905 15.196C15.886 16.7454 14.6286 18 13.0782 18H3.92323C2.37003 18 1.11091 16.7409 1.11091 15.1877V7.52877H0.81636C0.42806 7.52877 0.113281 7.21399 0.113281 6.82569V5.31316C0.113281 4.17228 1.03812 3.24744 2.179 3.24744H2.99566V2.73409ZM4.40181 3.24744H7.79765V2.52647L6.45874 1.63317C5.57987 1.0468 4.40181 1.67677 4.40181 2.73409V3.24744ZM9.20381 2.52647V3.24744H12.5996V2.73409C12.5996 1.67677 11.4216 1.0468 10.5427 1.63317L9.20381 2.52647ZM2.179 4.6536C1.81472 4.6536 1.51944 4.94888 1.51944 5.31316V6.12261H5.73398L5.734 4.6536H2.179ZM5.73401 7.52877V13.9306C5.73401 14.1806 5.86682 14.4119 6.08281 14.5379C6.29879 14.6639 6.56545 14.6657 6.78312 14.5426L8.50073 13.5715L10.2183 14.5426C10.436 14.6657 10.7027 14.6639 10.9187 14.5379C11.1346 14.4119 11.2674 14.1806 11.2674 13.9306V7.52873H14.4844V15.1603C14.4844 15.1627 14.4843 15.1651 14.4843 15.1675V15.1877C14.4843 15.9643 13.8548 16.5938 13.0782 16.5938H3.92323C3.14663 16.5938 2.51707 15.9643 2.51707 15.1877V7.52877H5.73401ZM15.482 6.12258V5.31312C15.482 4.94891 15.1867 4.6536 14.8225 4.6536H11.2674V6.12258H15.482ZM9.86129 4.6536H7.14017V12.7254L8.15469 12.1518C8.36941 12.0304 8.63204 12.0304 8.84676 12.1518L9.86129 12.7254V4.6536Z"></path>
                        </svg>
                    </div>
                    <div class="tf-gift-wrap-infos">
                        <p class="font-medium">Do you want a gift wrap?</p>
                        <span class="text-sm">Only <span class="price font-semibold">$5.00</span></span>
                    </div>
                </div>
                <div class="tf-cart-tool-btns space-y-3">
                    <button type="submit" class="tf-btn fw-6 w-100 justify-content-center btn-fill animate-hover-btn radius-3 w-full bg-black text-white py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-800 transition-colors">
                        <span>Add a gift wrap</span>
                    </button>
                    <div class="tf-mini-cart-tool-primary text-center w-100 fw-6 tf-mini-cart-tool-close py-2 font-medium text-gray-700 hover:text-gray-900 cursor-pointer"
                         wire:click="toggleAddGift">
                        Cancel
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif
