@extends('admin.layouts.app')

@section('title', 'SEO Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">SEO Settings</h1>
            <p class="text-muted">Optimize your store for search engines and social media</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="group" value="seo">

        <div class="row">
            <!-- Basic SEO -->
            <div class="col-lg-8">
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-search me-2 text-primary"></i>Basic SEO Settings
                    </h5>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Site Title Template</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                               value="{{ setting('meta_title', config('app.name') . ' - {page_title}') }}"
                               placeholder="{page_title} | Your Store Name">
                        <div class="form-text">Use <code>{page_title}</code> as placeholder for page-specific titles</div>
                        <div class="character-count">
                            <small class="text-muted"><span id="titleCount">0</span>/60 characters (optimal)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Default Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                                  placeholder="Brief description of your store and products">{{ setting('meta_description') }}</textarea>
                        <div class="character-count">
                            <small class="text-muted"><span id="descCount">0</span>/160 characters (optimal)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                               value="{{ setting('meta_keywords') }}"
                               placeholder="online store, shopping, uganda, products">
                        <div class="form-text">Comma-separated keywords (less important for modern SEO)</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="canonical_url" class="form-label">Canonical URL</label>
                            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                                   value="{{ setting('canonical_url', request()->getSchemeAndHttpHost()) }}"
                                   placeholder="https://yourstore.com">
                            <div class="form-text">Your primary domain URL</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="robots_txt" class="form-label">Robots.txt Instructions</label>
                            <select class="form-select" id="robots_txt" name="robots_txt">
                                <option value="index,follow" {{ setting('robots_txt', 'index,follow') === 'index,follow' ? 'selected' : '' }}>Index & Follow (Default)</option>
                                <option value="noindex,nofollow" {{ setting('robots_txt') === 'noindex,nofollow' ? 'selected' : '' }}>No Index & No Follow</option>
                                <option value="index,nofollow" {{ setting('robots_txt') === 'index,nofollow' ? 'selected' : '' }}>Index Only</option>
                                <option value="noindex,follow" {{ setting('robots_txt') === 'noindex,follow' ? 'selected' : '' }}>Follow Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Open Graph & Social Media -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-share me-2 text-info"></i>Social Media & Open Graph
                    </h5>

                    <div class="mb-3">
                        <label for="og_title" class="form-label">Open Graph Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title"
                               value="{{ setting('og_title', setting('site_name', config('app.name'))) }}"
                               placeholder="Title when shared on Facebook, LinkedIn, etc.">
                    </div>

                    <div class="mb-3">
                        <label for="og_description" class="form-label">Open Graph Description</label>
                        <textarea class="form-control" id="og_description" name="og_description" rows="3"
                                  placeholder="Description when shared on social media">{{ setting('og_description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="og_image" class="form-label">Open Graph Image</label>
                            <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                            <div class="form-text">Recommended: 1200x630px (Facebook, LinkedIn)</div>
                            @if(setting('og_image'))
                                <div class="mt-2">
                                    <img src="{{ Storage::url(setting('og_image')) }}" alt="Current OG Image" class="img-thumbnail" style="max-width: 200px;">
                                    <div class="small text-muted">Current image</div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="twitter_image" class="form-label">Twitter Card Image</label>
                            <input type="file" class="form-control" id="twitter_image" name="twitter_image" accept="image/*">
                            <div class="form-text">Recommended: 1200x600px (Twitter)</div>
                            @if(setting('twitter_image'))
                                <div class="mt-2">
                                    <img src="{{ Storage::url(setting('twitter_image')) }}" alt="Current Twitter Image" class="img-thumbnail" style="max-width: 200px;">
                                    <div class="small text-muted">Current image</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="twitter_handle" class="form-label">Twitter Handle</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" id="twitter_handle" name="twitter_handle"
                                       value="{{ setting('twitter_handle') }}" placeholder="yourhandle">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="facebook_page" class="form-label">Facebook Page URL</label>
                            <input type="url" class="form-control" id="facebook_page" name="facebook_page"
                                   value="{{ setting('facebook_page') }}" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>
                </div>

                <!-- Local SEO (Important for Uganda) -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-geo-alt me-2 text-success"></i>Local SEO (Uganda)
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                   value="{{ setting('business_name', setting('store_name')) }}"
                                   placeholder="Your registered business name">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_type" class="form-label">Business Type</label>
                            <select class="form-select" id="business_type" name="business_type">
                                <option value="">Select Business Type</option>
                                <option value="Store" {{ setting('business_type') === 'Store' ? 'selected' : '' }}>Retail Store</option>
                                <option value="OnlineStore" {{ setting('business_type') === 'OnlineStore' ? 'selected' : '' }}>Online Store</option>
                                <option value="Department Store" {{ setting('business_type') === 'Department Store' ? 'selected' : '' }}>Department Store</option>
                                <option value="Electronics Store" {{ setting('business_type') === 'Electronics Store' ? 'selected' : '' }}>Electronics Store</option>
                                <option value="Clothing Store" {{ setting('business_type') === 'Clothing Store' ? 'selected' : '' }}>Clothing Store</option>
                                <option value="Grocery Store" {{ setting('business_type') === 'Grocery Store' ? 'selected' : '' }}>Grocery Store</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="local_address" class="form-label">Full Business Address</label>
                        <textarea class="form-control" id="local_address" name="local_address" rows="2"
                                  placeholder="Full address including city, district, Uganda">{{ setting('local_address') }}</textarea>
                        <div class="form-text">Include full address for Google My Business and local search</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="local_phone" class="form-label">Business Phone</label>
                            <input type="tel" class="form-control" id="local_phone" name="local_phone"
                                   value="{{ setting('local_phone') }}" placeholder="+256 XXX XXX XXX">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                            <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number"
                                   value="{{ setting('whatsapp_number') }}" placeholder="+256 XXX XXX XXX">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="business_email" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="business_email" name="business_email"
                                   value="{{ setting('business_email') }}" placeholder="info@yourstore.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="google_maps_embed" class="form-label">Google Maps Embed Code</label>
                            <textarea class="form-control" id="google_maps_embed" name="google_maps_embed" rows="3"
                                      placeholder="<iframe src='https://www.google.com/maps/embed...'></iframe>">{{ setting('google_maps_embed') }}</textarea>
                            <div class="form-text">Get embed code from Google Maps</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_areas" class="form-label">Service Areas</label>
                            <textarea class="form-control" id="service_areas" name="service_areas" rows="3"
                                      placeholder="Kampala, Entebbe, Wakiso, Mukono">{{ setting('service_areas') }}</textarea>
                            <div class="form-text">Areas where you deliver/provide services</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics & Tools -->
            <div class="col-lg-4">
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-graph-up me-2 text-warning"></i>Analytics & Tracking
                    </h5>

                    <div class="mb-3">
                        <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
                        <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id"
                               value="{{ setting('google_analytics_id') }}" placeholder="G-XXXXXXXXXX">
                        <div class="form-text">GA4 Measurement ID</div>
                    </div>

                    <div class="mb-3">
                        <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                        <input type="text" class="form-control" id="google_tag_manager_id" name="google_tag_manager_id"
                               value="{{ setting('google_tag_manager_id') }}" placeholder="GTM-XXXXXXX">
                    </div>

                    <div class="mb-3">
                        <label for="facebook_pixel_id" class="form-label">Facebook Pixel ID</label>
                        <input type="text" class="form-control" id="facebook_pixel_id" name="facebook_pixel_id"
                               value="{{ setting('facebook_pixel_id') }}" placeholder="123456789012345">
                    </div>

                    <div class="mb-3">
                        <label for="google_search_console" class="form-label">Search Console Verification</label>
                        <input type="text" class="form-control" id="google_search_console" name="google_search_console"
                               value="{{ setting('google_search_console') }}" placeholder="google-site-verification=...">
                        <div class="form-text">HTML tag verification code</div>
                    </div>
                </div>

                <!-- Schema Markup -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-code-slash me-2 text-info"></i>Schema Markup
                    </h5>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_schema" name="enable_schema" value="1"
                            {{ setting('enable_schema', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_schema">
                            <strong>Enable Schema Markup</strong>
                            <div class="small text-muted">Helps search engines understand your content</div>
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_breadcrumbs" name="enable_breadcrumbs" value="1"
                            {{ setting('enable_breadcrumbs', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_breadcrumbs">
                            <strong>Enable Breadcrumbs</strong>
                            <div class="small text-muted">Navigation breadcrumbs for better UX and SEO</div>
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_product_schema" name="enable_product_schema" value="1"
                            {{ setting('enable_product_schema', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_product_schema">
                            <strong>Product Schema</strong>
                            <div class="small text-muted">Rich snippets for products in search results</div>
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_review_schema" name="enable_review_schema" value="1"
                            {{ setting('enable_review_schema', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_review_schema">
                            <strong>Review Schema</strong>
                            <div class="small text-muted">Star ratings in search results</div>
                        </label>
                    </div>
                </div>

                <!-- Sitemap Settings -->
                <div class="table-admin p-4">
                    <h5 class="mb-3">
                        <i class="bi bi-diagram-3 me-2 text-secondary"></i>Sitemap Settings
                    </h5>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_sitemap" name="enable_sitemap" value="1"
                            {{ setting('enable_sitemap', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_sitemap">
                            <strong>Auto-generate Sitemap</strong>
                        </label>
                    </div>

                    <div class="mb-3">
                        <label for="sitemap_priority_products" class="form-label">Products Priority</label>
                        <select class="form-select form-select-sm" id="sitemap_priority_products" name="sitemap_priority_products">
                            <option value="0.8" {{ setting('sitemap_priority_products', '0.8') === '0.8' ? 'selected' : '' }}>0.8 (High)</option>
                            <option value="0.6" {{ setting('sitemap_priority_products') === '0.6' ? 'selected' : '' }}>0.6 (Medium)</option>
                            <option value="0.4" {{ setting('sitemap_priority_products') === '0.4' ? 'selected' : '' }}>0.4 (Low)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sitemap_changefreq" class="form-label">Change Frequency</label>
                        <select class="form-select form-select-sm" id="sitemap_changefreq" name="sitemap_changefreq">
                            <option value="weekly" {{ setting('sitemap_changefreq', 'weekly') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="daily" {{ setting('sitemap_changefreq') === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="monthly" {{ setting('sitemap_changefreq') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>View Sitemap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Save SEO Settings
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Character counters
                const metaTitle = document.getElementById('meta_title');
                const metaDescription = document.getElementById('meta_description');
                const titleCount = document.getElementById('titleCount');
                const descCount = document.getElementById('descCount');

                function updateCharacterCount(element, counter) {
                    counter.textContent = element.value.length;

                    // Color coding for optimal length
                    if (element === metaTitle) {
                        counter.className = element.value.length <= 60 ? 'text-success' : 'text-warning';
                    } else if (element === metaDescription) {
                        counter.className = element.value.length <= 160 ? 'text-success' : 'text-warning';
                    }
                }

                metaTitle.addEventListener('input', () => updateCharacterCount(metaTitle, titleCount));
                metaDescription.addEventListener('input', () => updateCharacterCount(metaDescription, descCount));

                // Initial count
                updateCharacterCount(metaTitle, titleCount);
                updateCharacterCount(metaDescription, descCount);
            });
        </script>
    @endpush
@endsection
