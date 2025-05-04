<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WTR Hub - Workspace Management System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-[#0F172A]">
            <!-- Navigation -->
            <nav class="bg-[#0F172A] border-b border-[#38BDF8]/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-[#F8FAFC]">WTR Hub</h1>
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="{{ route('register') }}" class="text-[#F8FAFC] hover:text-[#38BDF8] text-sm">Register</a>
                            <a href="#about" class="text-[#F8FAFC] hover:text-[#38BDF8] text-sm">About Us</a>
                            <a href="#contact" class="text-[#F8FAFC] hover:text-[#38BDF8] text-sm">Contact</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative bg-[#0F172A] overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="relative z-10 py-16 md:py-24">
                        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                            <div class="sm:text-center lg:text-left lg:col-span-6">
                                <h1 class="text-4xl tracking-tight font-extrabold text-[#F8FAFC] sm:text-5xl md:text-6xl">
                                    <span class="block">Manage Your</span>
                                    <span class="block text-[#38BDF8]">Workspace Efficiently</span>
                                </h1>
                                <p class="mt-3 text-base text-[#F8FAFC]/70 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                    Streamline your workspace management with our comprehensive solution. Perfect for companies looking to optimize their office space utilization.
                                </p>
                                <div class="mt-8">
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-[#F8FAFC] bg-[#38BDF8] hover:bg-[#22D3EE] transition-colors duration-200">
                                        Register Your Company
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-16 bg-[#0F172A]" id="features">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-[#38BDF8] font-semibold tracking-wide uppercase">Features</h2>
                        <p class="mt-2 text-3xl font-extrabold text-[#F8FAFC] sm:text-4xl">
                            Everything you need to manage your workspace
                        </p>
                    </div>

                    <div class="mt-12">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Booking Management -->
                            <div class="bg-[#1E293B] rounded-lg p-6 hover:bg-[#1E293B]/80 transition duration-300">
                                <div class="text-[#38BDF8] mb-4">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-[#F8FAFC] mb-2">Smart Booking System</h3>
                                <p class="text-[#F8FAFC]/70">Effortlessly manage workspace bookings with our intuitive scheduling system. Real-time availability updates and conflict prevention.</p>
                            </div>

                            <!-- Resource Management -->
                            <div class="bg-[#1E293B] rounded-lg p-6 hover:bg-[#1E293B]/80 transition duration-300">
                                <div class="text-[#38BDF8] mb-4">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-[#F8FAFC] mb-2">Resource Optimization</h3>
                                <p class="text-[#F8FAFC]/70">Track and manage office resources efficiently. Monitor usage patterns and optimize space allocation for maximum productivity.</p>
                            </div>

                            <!-- Analytics Dashboard -->
                            <div class="bg-[#1E293B] rounded-lg p-6 hover:bg-[#1E293B]/80 transition duration-300">
                                <div class="text-[#38BDF8] mb-4">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-[#F8FAFC] mb-2">Advanced Analytics</h3>
                                <p class="text-[#F8FAFC]/70">Gain valuable insights with detailed usage analytics. Make data-driven decisions to improve workspace efficiency.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us Section -->
            <div class="py-16 bg-[#0F172A]/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-[#38BDF8] font-semibold tracking-wide uppercase">Why Choose Us</h2>
                        <p class="mt-2 text-3xl font-extrabold text-[#F8FAFC] sm:text-4xl">
                            The Smart Choice for Modern Workspaces
                        </p>
                    </div>
                    <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-[#38BDF8] text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-[#F8FAFC]">Easy Integration</h3>
                                <p class="mt-2 text-[#F8FAFC]/70">Seamlessly integrates with your existing systems and workflows.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-[#38BDF8] text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-[#F8FAFC]">Real-time Updates</h3>
                                <p class="mt-2 text-[#F8FAFC]/70">Stay informed with instant notifications and live status updates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Us Section -->
            <div class="py-16 bg-[#0F172A]/50" id="about">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-[#38BDF8] font-semibold tracking-wide uppercase">About Us</h2>
                        <p class="mt-2 text-3xl font-extrabold text-[#F8FAFC] sm:text-4xl">
                            Who We Are
                        </p>
                        <p class="mt-4 max-w-2xl mx-auto text-xl text-[#F8FAFC]/70">
                            WTR Hub is a leading workspace management solution provider, helping companies optimize their office space utilization and improve productivity.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="py-16 bg-[#0F172A]" id="contact">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-[#38BDF8] font-semibold tracking-wide uppercase">Contact Us</h2>
                        <p class="mt-2 text-3xl font-extrabold text-[#F8FAFC] sm:text-4xl">
                            Get In Touch
                        </p>
                        <p class="mt-4 max-w-2xl mx-auto text-xl text-[#F8FAFC]/70">
                            Have questions? We're here to help.
                        </p>
                    </div>
                    <div class="mt-8 flex justify-center">
                        <a href="mailto:contact@wtrhub.com" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-[#F8FAFC] bg-[#38BDF8] hover:bg-[#22D3EE] transition-colors duration-200">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-[#0F172A] border-t border-[#38BDF8]/10">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-[#F8FAFC]/70">
                        <p>&copy; {{ date('Y') }} WTR Hub. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
