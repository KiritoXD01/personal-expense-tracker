<!DOCTYPE html>

<html class="scroll-smooth" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-nav {
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .text-gradient {
            background: linear-gradient(135deg, #051125 0%, #1b263b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body
    class="bg-background text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-container">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-sm shadow-slate-900/5 h-20">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-8 h-full">
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold tracking-tighter text-slate-900 font-headline">
                    Architect Finance
                </span>
            </div>
            <div class="hidden md:flex items-center gap-10">
                <a class="text-emerald-600 font-bold border-b-2 border-emerald-500 pb-1 font-headline text-lg"
                    href="#">Home</a>
                <a class="text-slate-600 hover:text-emerald-500 transition-colors font-headline text-lg"
                    href="#">Features</a>
                <a class="text-slate-600 hover:text-emerald-500 transition-colors font-headline text-lg"
                    href="#">About</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('filament.dashboard.auth.login') }}"
                    class="px-6 py-2.5 bg-primary text-on-primary font-semibold rounded-lg hover:opacity-80 transition-all duration-300 scale-95 active:scale-90">
                    Sign Up
                </a>
            </div>
        </div>
    </nav>
    <main>
        <!-- Hero Section -->
        <section class="relative pt-40 pb-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row items-center gap-16">
                <div class="flex-1 text-center md:text-left z-10">
                    <span
                        class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest uppercase bg-secondary-container text-on-secondary-container rounded-full font-label">
                        The Digital Private Bank
                    </span>
                    <h1
                        class="text-6xl md:text-7xl font-extrabold tracking-tight text-primary font-headline leading-[1.1] mb-8">
                        Master Your Money <br />
                        <span class="text-on-secondary-container">With Precision.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-on-surface-variant leading-relaxed mb-10 max-w-2xl">
                        Engineered with the robust performance of Laravel and the sophisticated interface of
                        FilamentPHP. Architect Finance offers an uncompromising financial ecosystem for the modern
                        investor.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('filament.dashboard.auth.login') }}"
                            class="px-8 py-4 bg-primary text-on-primary font-bold rounded-lg shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all text-lg">
                            Get Started
                        </a>
                    </div>
                </div>
                <div class="flex-1 relative w-full aspect-square">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                    <img class="relative z-10 w-full h-full object-cover rounded-xl shadow-2xl shadow-primary/10"
                        data-alt="A high-end financial dashboard UI on a modern laptop screen showing complex stock charts and balance graphs with emerald accents"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8LwV2-cvC-Szua9halh9t9Bd0E2LsPs_AvQeAuOlNcRTDuNxqPm8M81bgxSGsNVjA3aU-gslWVZUi0fgchIJyofZqQTwDO3V0Yx7jBznHpKS-jv0q4kjeoZwOqMJJcSe76-0hgRK_KaUjjIhCI7Ic1hvY3Ksr6_DXpv2vkwWkXXnO30Utyd60vX4PX3gD81ZKyBXDBoVOICv-2SqQqdd6oSUNxGgSSHHMiemZ740ksKMVVBLp7efy6Q1U3MgnrnDlLuvgd2twIIE" />
                </div>
            </div>
        </section>
        <!-- Feature Grid (Bento Style) -->
        <section class="py-24 bg-surface-container-low">
            <div class="max-w-7xl mx-auto px-8">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-bold tracking-tight text-primary font-headline mb-4">
                        Architectural Foundation</h2>
                    <p class="text-on-surface-variant text-lg">Every tool you need, built into a single cohesive
                        structure.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Automated Tracking -->
                    <div
                        class="md:col-span-8 bg-surface-container-lowest p-10 rounded-xl flex flex-col justify-between group hover:bg-surface-bright transition-all duration-300">
                        <div class="max-w-md">
                            <span class="material-symbols-outlined text-4xl text-secondary mb-6"
                                data-icon="account_balance_wallet">account_balance_wallet</span>
                            <h3 class="text-3xl font-bold text-primary mb-4 font-headline">Automated Tracking</h3>
                            <p class="text-on-surface-variant leading-relaxed">Seamlessly sync with your accounts. Our
                                Laravel-powered engine categorizes every transaction with 99.9% accuracy using advanced
                                algorithmic mapping.</p>
                        </div>
                        <div class="mt-12 h-48 w-full overflow-hidden rounded-lg">
                            <img class="w-full h-full object-cover"
                                data-alt="Close up of a minimalist data visualization showing transaction flow with smooth curves and soft gradients"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXxiV0KdPwrC-SrDfFsY47wlaRBNBA4aD53T4u6kUlvck1G-pvWZjhv4TeprmoO9cVcc-8xUMHg_aU1p5hrFR_b6K2wuBYlEkxZUFCslG2LmyPsxQaiYcyp5yPkqAvCeB0al4l4raUX3bOGlfDgy33yv6Hk5XEjzJ2_qNRt1sger71kxLI_BDR5HmpdxId1YKrcVLeXDzFeYS-XMIwO0jqql22PCmsx4fTjNHgm7DUaBAOaQQS8TERcIVBMv-jl8eA2mSqy4DF2fc" />
                        </div>
                    </div>
                    <!-- Smart Budgeting -->
                    <div
                        class="md:col-span-4 bg-secondary p-10 rounded-xl text-on-secondary flex flex-col justify-between">
                        <div>
                            <span class="material-symbols-outlined text-4xl mb-6" data-icon="insights"
                                style="font-variation-settings: 'FILL' 1;">insights</span>
                            <h3 class="text-3xl font-bold mb-4 font-headline leading-tight">Smart Budgeting</h3>
                            <p class="opacity-90 leading-relaxed">Predictive budgeting that adjusts to your lifestyle.
                                FilamentPHP interfaces make managing limits intuitive and effortless.</p>
                        </div>
                        <div
                            class="mt-8 flex items-center gap-2 font-label font-bold text-sm tracking-widest uppercase">
                            Explore Budgets <span class="material-symbols-outlined">arrow_forward</span>
                        </div>
                    </div>
                    <!-- Goal Setting -->
                    <div
                        class="md:col-span-12 bg-primary text-on-primary p-12 rounded-xl flex flex-col md:flex-row items-center gap-12 overflow-hidden">
                        <div class="flex-1">
                            <span class="material-symbols-outlined text-4xl text-secondary-fixed mb-6"
                                data-icon="flag">flag</span>
                            <h3 class="text-4xl font-bold mb-6 font-headline">Intentional Goal Setting</h3>
                            <p class="text-on-primary-container text-lg leading-relaxed max-w-xl">Whether it's a first
                                home or a secure retirement, define your architectural milestones. We provide the
                                blueprint to reach them faster.</p>
                        </div>
                        <div class="flex-1 grid grid-cols-2 gap-4 w-full">
                            <div class="bg-primary-container/50 p-6 rounded-lg border border-outline-variant/10">
                                <div class="text-secondary-fixed font-bold text-3xl mb-2 font-headline">84%</div>
                                <div class="text-sm opacity-60 font-label uppercase">Faster Savings</div>
                            </div>
                            <div class="bg-primary-container/50 p-6 rounded-lg border border-outline-variant/10">
                                <div class="text-secondary-fixed font-bold text-3xl mb-2 font-headline">12k+</div>
                                <div class="text-sm opacity-60 font-label uppercase">Goals Met</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- How it Works -->
        <section class="py-32 bg-surface-container-lowest">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex flex-col md:flex-row gap-16 items-start">
                    <div class="md:w-1/3 sticky top-32">
                        <h2 class="text-5xl font-extrabold text-primary font-headline mb-8 leading-tight">The Blueprint
                            <br />to Prosperity
                        </h2>
                        <p class="text-on-surface-variant text-lg">We’ve refined the complexity of wealth management
                            into three definitive movements.</p>
                        <div class="mt-12 h-1 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full w-1/3 bg-secondary"></div>
                        </div>
                    </div>
                    <div class="md:w-2/3 space-y-24">
                        <div class="flex gap-10">
                            <div
                                class="flex-shrink-0 w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center font-headline font-bold text-2xl text-primary">
                                01</div>
                            <div>
                                <h3 class="text-2xl font-bold text-primary mb-4 font-headline">Secure Connection</h3>
                                <p class="text-on-surface-variant leading-relaxed text-lg">Link your financial
                                    institutions through our encrypted vault. We prioritize data sovereignty, ensuring
                                    your information stays private and protected.</p>
                            </div>
                        </div>
                        <div class="flex gap-10">
                            <div
                                class="flex-shrink-0 w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center font-headline font-bold text-2xl text-primary">
                                02</div>
                            <div>
                                <h3 class="text-2xl font-bold text-primary mb-4 font-headline">AI Classification</h3>
                                <p class="text-on-surface-variant leading-relaxed text-lg">Our intelligent engine parses
                                    your spending history, building a clear visual map of where your capital flows. No
                                    manual entry required.</p>
                            </div>
                        </div>
                        <div class="flex gap-10">
                            <div
                                class="flex-shrink-0 w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center font-headline font-bold text-2xl text-primary">
                                03</div>
                            <div>
                                <h3 class="text-2xl font-bold text-primary mb-4 font-headline">Strategic Growth</h3>
                                <p class="text-on-surface-variant leading-relaxed text-lg">Receive bespoke insights and
                                    automated alerts designed to optimize your net worth. It’s private banking
                                    intelligence, accessible to everyone.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Final CTA -->
        <section class="py-32 bg-primary relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_120%,#006c4a,transparent)]">
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-8 relative z-10 text-center">
                <h2 class="text-5xl md:text-6xl font-extrabold text-on-primary font-headline mb-8 tracking-tight">Build
                    Your Financial Legacy Today.</h2>
                <p class="text-on-primary-container text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
                    Join over 50,000 users who have chosen the architectural approach to wealth management.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <button
                        class="px-10 py-5 bg-secondary text-on-secondary font-bold rounded-lg shadow-2xl shadow-secondary/40 hover:scale-105 transition-all text-xl">
                        Create Your Free Account
                    </button>
                </div>
                <p class="mt-8 text-on-primary-container font-label text-sm uppercase tracking-[0.2em]">No credit card
                    required • Secure data sync • 24/7 Support</p>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="bg-slate-50 dark:bg-slate-950 w-full py-16 tonal-shift bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex flex-col items-center md:items-start gap-4">
                <span class="font-['Manrope'] font-black text-slate-900 dark:text-white text-2xl">Architect
                    Finance.</span>
                <p class="font-['Inter'] text-sm leading-relaxed text-slate-500 dark:text-slate-400">© 2024 Architect
                    Finance. Built with Precision.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8">
                <a class="text-slate-500 dark:text-slate-400 hover:text-emerald-500 transition-colors duration-200 font-['Inter'] text-sm leading-relaxed"
                    href="#">Privacy Policy</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-emerald-500 transition-colors duration-200 font-['Inter'] text-sm leading-relaxed"
                    href="#">Terms of Service</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-emerald-500 transition-colors duration-200 font-['Inter'] text-sm leading-relaxed"
                    href="#">Security</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-emerald-500 transition-colors duration-200 font-['Inter'] text-sm leading-relaxed"
                    href="#">Contact</a>
            </div>
            <div class="flex items-center gap-6">
                <span
                    class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 cursor-pointer hover:opacity-75 transition-all"
                    data-icon="hub">hub</span>
                <span
                    class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 cursor-pointer hover:opacity-75 transition-all"
                    data-icon="language">language</span>
                <span
                    class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 cursor-pointer hover:opacity-75 transition-all"
                    data-icon="share">share</span>
            </div>
        </div>
    </footer>
</body>

</html>
