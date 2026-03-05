<x-app-layout>
    <x-slot name="title">About Us - {{ config('app.name') }}</x-slot>

    <div id="wrapper">
        <!-- Page Title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center">About Us</div>
                <p class="text-center text-secondary mt-2">Learn more about {{ config('app.name') }}</p>
            </div>
        </div>

        <section class="flat-spacing-9">
            <div class="container">
                <div class="tf-grid-layout md-col-2 gap-30">
                    <div>
                        <h5 class="fw-bold mb-4">Our Story</h5>
                        <p class="text-secondary mb-3">
                            {{ setting('about_story', config('app.name') . ' is your trusted online destination for quality products. We are committed to bringing you the best selection at competitive prices, with a focus on customer satisfaction and reliability.') }}
                        </p>
                        <p class="text-secondary mb-3">
                            {{ setting('about_mission', 'Our mission is to make shopping easy, enjoyable, and accessible for everyone. We carefully curate our product range to ensure quality and value for our customers.') }}
                        </p>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-4">Why Shop With Us</h5>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Customer First</h6>
                                <p class="text-secondary">Your satisfaction is our top priority. We go above and beyond to ensure a great shopping experience.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Quality Guaranteed</h6>
                                <p class="text-secondary">Every product is carefully selected and verified to meet our high quality standards.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="3" width="15" height="13"/>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                    <circle cx="5.5" cy="18.5" r="2.5"/>
                                    <circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Fast Delivery</h6>
                                <p class="text-secondary">We partner with reliable delivery services to get your orders to you as quickly as possible.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                    <polyline points="17 6 23 6 23 12"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Competitive Prices</h6>
                                <p class="text-secondary">We work hard to offer you the best value for your money across all our products.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
