<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="h-screen flex items-center justify-center bg-gradient-to-t from-blue-900 to-emerald-900 relative">

    @if (session('success'))
        <div id="toast" class="fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out">
            <div
                class="max-w-sm w-full bg-emerald-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-start gap-3 border border-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd"
                        d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m-.232-5.36l5-6l-1.536-1.28l-4.3 5.159l-2.225-2.226l-1.414 1.414l3 3l.774.774z"
                        clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <div class="font-semibold">Berhasil</div>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
                <button id="toastClose" aria-label="Tutup"
                    class="text-white/80 hover:text-white rounded focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
    <div class="flex items-center justify-center shadow-lg">
        {{-- Gambar samping login form --}}
        <div id="slideshow"
            class="max-w-sm bg-[url(img/truk.jpg)] border-gray-600 bg-center bg-cover w-96 h-[384px] rounded-l-xl overflow-hidden flex items-center justify-center">
            <div class="bg-black backdrop-blur-sm p-4 flex justify-center items-center bg-opacity-60 h-full w-full">
                <div>
                    <h1 class="text-white text-2xl font-bold">Selamat Datang di</h1>
                    <h1 class="text-white text-2xl font-bold">TMMS-SCPK</h1>
                    <p class="text-white text-sm mt-2">Sistem Pedukung Keputusan - Kenaikan Gaji Karyawan</p>

                </div>
            </div>
        </div>
        {{-- form --}}
        <div class="bg-white  rounded-r-xl p-8 w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-center text-blue-600 mb-2">Login</h2>
            <p class="text-center text-gray-500 mb-6">Silahkan masukan email dan password anda!</p>

            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Username Input -->
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-gray-500" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m20 8l-8 5l-8-5V6l8 5l8-5m0-2H4c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2" />
                    </svg>
                    <div class="w-full">
                        <input type="email" name="email" placeholder="Email"
                            class="w-full px-2 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400 @error('email') ring-2 ring-rose-500 border-transparent @enderror" />
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Password Input -->
                <div class="flex relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-gray-500" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 17a2 2 0 0 0 2-2a2 2 0 0 0-2-2a2 2 0 0 0-2 2a2 2 0 0 0 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                    </svg>
                    <div class="w-full">
                        <input id="password" name="password" type="password" placeholder="Password"
                            class="w-full px-2 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400 @error('password') ring-2 ring-rose-500 border-transparent @enderror" />
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Eye Icon -->
                    <span onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-400 hover:text-gray-600">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-500 font-semibold text-white py-2 rounded-lg hover:bg-emerald-700 transition">
                    Login
                </button>

            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Belum punya akun?
                <a href="/register" class="text-sky-600 hover:underline">Daftar Disini</a>
            </p>
            <p class="text-center text-sm text-gray-500 mt-2">
                Lupa Password?
                <a href="{{ route('forgot.password') }}" class="text-sky-600 hover:underline">Klik</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.outerHTML = `
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.96 9.96 0 012.248-3.608m3.182-2.24A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.507 5.306M3 3l18 18" />
        </svg>`;
            } else {
                password.type = 'password';
                eyeIcon.outerHTML = `
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>`;
            }
        }
    </script>

    <script>
        // toast auto-hide + close button
        (function() {
            const toast = document.getElementById('toast');
            if (!toast) return;

            // masuk animasi (dari translate-y-4 & opacity-0 ke visible) — inisialisasi visible
            toast.firstElementChild.classList.add('opacity-100');

            const removeToast = () => {
                // add hide classes
                toast.firstElementChild.classList.add('translate-y-4', 'opacity-0');
                // remove DOM setelah animasi selesai
                setTimeout(() => toast.remove(), 300);
            };

            // auto hide setelah 2500ms
            const timer = setTimeout(removeToast, 2500);

            // tombol close
            const btn = document.getElementById('toastClose');
            if (btn) {
                btn.addEventListener('click', () => {
                    clearTimeout(timer);
                    removeToast();
                });
            }
        })();
    </script>



</body>

</html>
