<x-app-layout :isAuthPage="false" :cartCount="$cartCount ?? 0" :wishlistCount="$wishlistCount ?? 0"
              :languages="$languages ?? ['English']" :selectedLanguage="$selectedLanguage ?? 'English'">

    <div class="tf-page-title style-2">
        <div class="container-full">
            <div class="heading text-center">Contact Us</div>
        </div>
    </div>

    <div class="w-100">
        <iframe
            src="https://www.google.com/maps/place/Wandegeya+Market/@0.3396182,32.5902833,15z/data=!4m6!3m5!1s0x177dbb72fcbc6a51:0xb4c619f0e3175357!8m2!3d0.3305576!4d32.5733405!16s%2Fg%2F1yg56gw33?entry=ttu&g_ep=EgoyMDI1MDgzMC4wIKXMDSoASAFQAw%3D%3D"
            width="100%" height="646" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="flat-spacing-21">
        <div class="container">
            <div class="tf-grid-layout gap30 lg-col-2">
                <div class="tf-content-left">
                    <h5 class="mb_20">Visit Our Store</h5>
                    <div class="mb_20">
                        <p class="mb_15"><strong>Address</strong></p>
                        <p>66 Mott St, New York, New York, Zip Code: 10006, AS</p>
                    </div>
                    <div class="mb_20">
                        <p class="mb_15"><strong>Phone</strong></p>
                        <p>(623) 934-2400</p>
                    </div>
                    <div class="mb_20">
                        <p class="mb_15"><strong>Email</strong></p>
                        <p>EComposer@example.com</p>
                    </div>
                    <div class="mb_36">
                        <p class="mb_15"><strong>Open Time</strong></p>
                        <p class="mb_15">Our store has re-opened for shopping, </p>
                        <p>exchange Every day 11am to 7pm</p>
                    </div>
                    <div>
                        <ul class="tf-social-icon d-flex gap-20 style-default">
                            <li><a href="#" class="box-icon link round social-facebook border-line-black"><i
                                        class="icon fs-14 icon-fb"></i></a></li>
                            <li><a href="#" class="box-icon link round social-twiter border-line-black"><i
                                        class="icon fs-12 icon-Icon-x"></i></a></li>
                            <li><a href="#" class="box-icon link round social-instagram border-line-black"><i
                                        class="icon fs-14 icon-instagram"></i></a></li>
                            <li><a href="#" class="box-icon link round social-tiktok border-line-black"><i
                                        class="icon fs-14 icon-tiktok"></i></a></li>
                            <li><a href="#" class="box-icon link round social-pinterest border-line-black"><i
                                        class="icon fs-14 icon-pinterest-1"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tf-content-right">
                    <h5 class="mb_20">Get in Touch</h5>
                    <p class="mb_24">If you’ve got great products your making or looking to work with us then drop
                        us a line.</p>
                    <div>
                        @if(session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger mb-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form class="form-contact" id="contactform" action="{{ route('pages.contact.submit') }}" method="post"
                              novalidate="novalidate">
                            @csrf
                            <div class="d-flex gap-15 mb_15">
                                <fieldset class="w-100">
                                    <input type="text" name="name" id="name" required="" placeholder="Name *"
                                           value="{{ old('name') }}" class="@error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <fieldset class="w-100">
                                    <input type="email" name="email" id="email" required="" placeholder="Email *"
                                           value="{{ old('email') }}" class="@error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                            </div>
                            <div class="mb_15">
                                <textarea placeholder="Message" name="message" id="message" required="" cols="30"
                                          rows="10" class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="send-wrap">
                                <button type="submit"
                                        class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
