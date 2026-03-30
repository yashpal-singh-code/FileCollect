  <footer class="bg-slate-950 text-slate-400 pt-14 pb-8">

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

          {{-- TOP GRID --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

              {{-- BRAND --}}
              <div class="space-y-4">

                  <a href="#" class="flex items-center gap-3">

                      <div class="flex items-center justify-center w-10 h-10">
                          <svg viewBox="0 0 24 24" class="h-9 w-9 text-blue-600" fill="none">
                              <path d="M6 5h8M6 5v14M6 11h6" stroke="currentColor" stroke-width="2.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                              <path d="M18 7a5 5 0 100 10" stroke="currentColor" stroke-width="2.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                      </div>

                      <div>
                          <p class="text-lg font-semibold text-white">FileCollect</p>
                          <p class="text-xs text-blue-500">Secure • Organize</p>
                      </div>

                  </a>

                  <p class="text-sm text-slate-400">
                      A simple and secure way to request, collect, and manage client documents.
                  </p>

              </div>

              {{-- PRODUCT --}}
              <div>
                  <h4 class="text-sm font-semibold text-white mb-4">Product</h4>
                  <ul class="space-y-2 text-sm">
                      <li><a href="/#features" class="hover:text-white transition">Features</a></li>
                      <li><a href="/#solutions" class="hover:text-white transition">Solutions</a></li>
                      <li><a href="/#pricing" class="hover:text-white transition">Pricing</a></li>
                  </ul>
              </div>

              {{-- COMPANY --}}
              <div>
                  <h4 class="text-sm font-semibold text-white mb-4">Company</h4>
                  <ul class="space-y-2 text-sm">
                      <li><a href="/#about" class="hover:text-white transition">About</a></li>
                      <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                  </ul>
              </div>

              {{-- LEGAL --}}
              <div>
                  <h4 class="text-sm font-semibold text-white mb-4">Legal</h4>
                  <ul class="space-y-2 text-sm">
                      <li>
                          <a href="{{ route('legal.privacy') }}" target="_blank" class="hover:text-white transition">
                              Privacy Policy
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('legal.terms') }}" target="_blank" class="hover:text-white transition">
                              Terms & Conditions
                          </a>
                      </li>
                  </ul>
              </div>

          </div>

          {{-- DIVIDER --}}
          <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">

              {{-- COPYRIGHT --}}
              <p class="text-xs text-slate-500 text-center sm:text-left">
                  © {{ date('Y') }} FileCollect. All rights reserved.
              </p>

              {{-- OPTIONAL SOCIAL --}}
              <div class="flex items-center gap-4">

                  {{-- Facebook --}}
                  <a href="https://www.facebook.com/filecollect/" class="text-slate-400 hover:text-blue-600 transition"
                      target="_blank">
                      <x-lucide-facebook class="w-5 h-5" />
                  </a>

                  {{-- LinkedIn --}}
                  <a href="https://www.linkedin.com/company/filecollect/"
                      class="text-slate-400 hover:text-blue-600 transition" target="_blank">
                      <x-lucide-linkedin class="w-5 h-5" />
                  </a>

              </div>

          </div>

      </div>

  </footer>
